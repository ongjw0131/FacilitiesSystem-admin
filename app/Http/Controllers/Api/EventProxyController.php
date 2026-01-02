<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\EventTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class EventProxyController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = Event::query();
        $query->where(function ($q) {
            $q->where('is_deleted', 0)->orWhereNull('is_deleted');
        });

        // Filter by society if provided
        if ($societyId = $request->query('society_id')) {
            $query->whereHas('societies', function ($q) use ($societyId) {
                $q->where('society_id', $societyId);
            });
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($entry = $request->query('entry_type')) {
            $query->where('entry_type', strtoupper($entry));
        }

        $events = $query->orderBy('start_date', 'asc')->get();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'events' => $events,
            'timeStamp' => $this->timestamp(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $today = Carbon::now('Asia/Kuala_Lumpur')->toDateString();
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'nullable|string|max:255',
                'capacity' => 'nullable|integer|min:0',
                'status' => ['required', Rule::in(['incoming', 'open', 'closed', 'cancelled', 'completed'])],
                'image' => 'nullable|image|max:2048',
                'needs_facility' => 'nullable|boolean',
                'facility_id' => 'nullable|integer|exists:facilities,id',
                'facility_start_at' => ['nullable', 'date', 'after_or_equal:' . $today],
                'facility_end_at' => ['nullable', 'date', 'after_or_equal:facility_start_at'],
                'organizer_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($request->hasFile('image')) {
                $data['image_url_path'] = $this->handleImageUpload($request->file('image'));
            }

            $data['organizer_id'] = $data['organizer_id'] ?? null;
            $data['is_deleted'] = 0;
            $event = Event::create($data);

            if (!empty($data['needs_facility']) && !empty($data['facility_id']) && Schema::hasTable('facility_bookings')) {
                $this->createFacilityBooking($event, $data);
            }

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Event created successfully',
                'event' => $event,
                'timeStamp' => $this->timestamp(),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($request, 'Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Failed to create event', $e->getMessage(), 500);
        }
    }

    public function show(Request $request, Event $event): JsonResponse
    {
        
        if ($event->is_deleted) {
            return $this->errorResponse($request, 'Event not found', null, 404);
        }

        $userId = $request->input('user_id');
        $hasJoined = false;

        if ($userId && Schema::hasTable('attendees')) {
            $hasJoined = Attendee::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->exists();
        }

        $tickets = collect([]);

            $tickets = EventTicket::where('event_id', $event->id)
                ->where('status', 'active')
                ->whereRaw('total_quantity > sold_quantity')
                ->where(function ($q) {
                    $q->whereNull('sales_start_at')->orWhere('sales_start_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('sales_end_at')->orWhere('sales_end_at', '>=', now());
                })
                ->orderBy('price', 'asc')
                ->get();
        

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'event' => $event,
            'hasJoined' => $hasJoined,
            'tickets' => $tickets,
            'timeStamp' => $this->timestamp(),
        ]);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'nullable|string|max:255',
                'capacity' => 'nullable|integer|min:0',
                'status' => ['required', Rule::in(['incoming', 'open', 'closed', 'cancelled', 'completed'])],
                'image' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $data['image_url_path'] = $this->handleImageUpload($request->file('image'));
            }

            $event->fill($data);
            $event->save();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Event updated successfully',
                'event' => $event,
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($request, 'Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Failed to update event', $e->getMessage(), 500);
        }
    }

    public function join(Request $request, Event $event): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            if (!$userId) {
                return $this->errorResponse($request, 'User ID is required', null, 400);
            }

            $alreadyJoined = Attendee::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyJoined) {
                return $this->errorResponse($request, 'User has already joined this event', null, 400);
            }


            $attendee = Attendee::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'status' => 'registered',
            ]);

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Successfully joined the event',
                'attendee' => $attendee,
                'timeStamp' => $this->timestamp(),
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Failed to join event', $e->getMessage(), 500);
        }
    }

    public function getTickets(Request $request, Event $event): JsonResponse
    {
        $tickets = EventTicket::where('event_id', $event->id)
            ->where('status', 'active')
            ->whereRaw('total_quantity > sold_quantity')
            ->where(function ($q) {
                $q->whereNull('sales_start_at')->orWhere('sales_start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sales_end_at')->orWhere('sales_end_at', '>=', now());
            })
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'tickets' => $tickets,
            'timeStamp' => $this->timestamp(),
        ]);
    }

    public function purchaseTickets(Request $request, Event $event): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            if (!$userId) {
                return $this->errorResponse($request, 'User ID is required', null, 400);
            }

            $validated = $request->validate([
                'ticket_id' => 'required|exists:event_tickets,id',
                'quantity' => 'required|integer|min:1|max:100',
                'user_email' => 'nullable|email',
            ]);

            $ticket = EventTicket::findOrFail($validated['ticket_id']);
            $quantity = (int)$validated['quantity'];

            if ($ticket->event_id != $event->id) {
                return $this->errorResponse($request, 'Invalid ticket selection', null, 400);
            }

            $availableQuantity = $ticket->total_quantity - $ticket->sold_quantity;
            if ($quantity > $availableQuantity) {
                return $this->errorResponse($request, "Only {$availableQuantity} tickets remaining", null, 400);
            }

            $now = now();
            if ($ticket->sales_start_at && $ticket->sales_start_at > $now) {
                return $this->errorResponse($request, 'Ticket sales have not started yet', null, 400);
            }
            if ($ticket->sales_end_at && $ticket->sales_end_at < $now) {
                return $this->errorResponse($request, 'Ticket sales have ended', null, 400);
            }

            if (Attendee::where('event_id', $event->id)->where('user_id', $userId)->exists()) {
                return $this->errorResponse($request, 'User has already registered', null, 400);
            }

            $price = floatval($ticket->price);
            $total = $price * $quantity;

            if ($price > 0) {
                return $this->createStripeSession($request, $event, $ticket, $userId, $quantity, $total, $validated);
            }

            return $this->processFreeTicket($request, $event, $ticket, $userId, $quantity);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($request, 'Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Failed to process purchase', $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, Event $event): JsonResponse
    {
        try {
            $this->ensureAdmin($request);
            $event->is_deleted = 1;
            $event->save();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Event marked as deleted',
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($request, $e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    public function restore(Request $request, Event $event): JsonResponse
    {
        try {
            $this->ensureAdmin($request);
            $event->is_deleted = 0;
            $event->save();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Event restored successfully',
                'event' => $event,
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($request, $e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    public function permanentDelete(Request $request, Event $event): JsonResponse
    {
        try {
            $this->ensureAdmin($request);
            $eventName = $event->name;

            DB::table('attendees')->where('event_id', $event->id)->delete();
            DB::table('event_tickets')->where('event_id', $event->id)->delete();
            if (Schema::hasTable('facility_bookings')) {
                DB::table('facility_bookings')->where('event_id', $event->id)->delete();
            }
            $event->delete();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => "Event '{$eventName}' permanently deleted",
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($request, $e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    public function getAttendees(Request $request, Event $event): JsonResponse
    {
        try {
            $this->ensureAdmin($request);
            if ($event->is_deleted) {
                return $this->errorResponse($request, 'Event not found', null, 404);
            }

            $attendees = Attendee::where('event_id', $event->id)
                ->with(['user', 'ticket'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'attendees' => $attendees,
                'count' => $attendees->count(),
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($request, $e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    // Helper methods
    private function requestId(Request $request): string
    {
        return $request->header('X-Request-ID') ?? Str::uuid()->toString();
    }

    private function timestamp(): string
    {
        return now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s');
    }

    private function errorResponse(Request $request, string $message, $errors = null, int $code = 500): JsonResponse
    {
        $response = [
            'requestID' => $this->requestId($request),
            'status' => 'E',
            'message' => $message,
            'timeStamp' => $this->timestamp(),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    private function handleImageUpload($file): string
    {
        $path = $file->store('events', 'public');
        $basename = basename($path);
        $storageFile = storage_path('app/public/' . $path);

        $publicDir = public_path('events');
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        $publicFile = $publicDir . '/' . $basename;
        if (file_exists($storageFile)) {
            @copy($storageFile, $publicFile);
        }

        $publicStorageDir = public_path('storage/events');
        if (!is_dir($publicStorageDir)) {
            @mkdir($publicStorageDir, 0755, true);
        }
        $publicStorageFile = $publicStorageDir . '/' . $basename;
        if (file_exists($storageFile)) {
            @copy($storageFile, $publicStorageFile);
        }

        return Storage::url($path);
    }

    private function createFacilityBooking(Event $event, array $data): void
    {
        DB::table('facility_bookings')->insert([
            'event_id' => $event->id,
            'facility_id' => $data['facility_id'],
            'start_at' => $data['facility_start_at'] ?? null,
            'end_at' => $data['facility_end_at'] ?? null,
            'status' => 'pending',
            'reject_reason' => null,
            'created_by' => $data['organizer_id'] ?? null,
            'approved_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createStripeSession(Request $request, Event $event, EventTicket $ticket, $userId, int $quantity, float $total, array $validated): JsonResponse
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $amount = intval(round($total * 100));
            $successUrl = $request->input('success_url', config('app.url') . '/events/' . $event->id);
            $cancelUrl = $request->input('cancel_url', config('app.url') . '/events/' . $event->id);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => $ticket->ticket_name,
                            'description' => substr($event->name . ' — ' . $ticket->ticket_name, 0, 255),
                        ],
                        'unit_amount' => $amount / $quantity,
                    ],
                    'quantity' => $quantity,
                ]],
                'customer_email' => $validated['user_email'] ?? null,
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}&success=1',
                'cancel_url' => $cancelUrl . '?canceled=1',
                'metadata' => [
                    'event_id' => (string)$event->id,
                    'ticket_id' => (string)$ticket->id,
                    'user_id' => (string)$userId,
                    'quantity' => (string)$quantity,
                ],
            ]);

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => 'Stripe checkout session created',
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'timeStamp' => $this->timestamp(),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($request, 'Failed to initiate payment', $e->getMessage(), 500);
        }
    }

    private function processFreeTicket(Request $request, Event $event, EventTicket $ticket, $userId, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $ticket->increment('sold_quantity', $quantity);
            $attendees = [];
            for ($i = 0; $i < $quantity; $i++) {
                $attendees[] = Attendee::create([
                    'event_id' => $event->id,
                    'user_id' => $userId,
                    'ticket_id' => $ticket->id,
                    'status' => 'registered',
                ]);
            }
            DB::commit();

            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'S',
                'message' => "Registration successful! {$ticket->ticket_name} x {$quantity}",
                'attendees' => $attendees,
                'timeStamp' => $this->timestamp(),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function ensureAdmin(Request $request): void
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            throw new \Exception('Forbidden: User ID required', 403);
        }

        $user = DB::table('users')->find($userId);
        if (!$user) {
            throw new \Exception('Forbidden: User not found', 403);
        }

        $isAdmin = !empty($user->is_admin) || 
                   !empty($user->isAdmin) || 
                   (isset($user->is_admin) && $user->is_admin == 1) ||
                   (!empty($user->role) && in_array(strtolower((string)$user->role), ['admin', 'administrator', 'superadmin'])) ||
                   (isset($user->role) && ((string)$user->role === '1' || (int)$user->role === 1));

        if (!$isAdmin) {
            throw new \Exception('Forbidden: Admin only', 403);
        }
    }
}