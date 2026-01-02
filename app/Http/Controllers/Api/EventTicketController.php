<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\SocietyUser;
use App\Models\EventSociety;
use Illuminate\Support\Facades\DB;

class EventTicketProxyController extends Controller
{
    /**
     * Check if user can manage tickets (admin OR active president)
     */
    protected function canManageTickets(Event $event, $userId): bool
    {
        // 1️⃣ Admin check（API 不用 Auth）
        $user = DB::table('users')->find($userId);

        if ($user && (
            !empty($user->is_admin) ||
            (!empty($user->role) && in_array(strtolower($user->role), ['admin', 'administrator', 'superadmin']))
        )) {
            return true;
        }

        // 2️⃣ 找这个 event 关联的 society
        $societyIds = EventSociety::where('event_id', $event->id)
            ->pluck('society_id');

        if ($societyIds->isEmpty()) {
            return false;
        }

        // 3️⃣ active president
        return SocietyUser::where('userID', $userId)
            ->whereIn('societyID', $societyIds)
            ->where('position', 'president')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get all tickets for an event (admin / president only)
     * GET /api/events/{event}/tickets
     */
    public function index(Request $request, $eventId): JsonResponse
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'User ID is required',
                'timeStamp' => now()->toDateTimeString(),
            ], 400);
        }

        $event = Event::find($eventId);

        if (!$event) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Event not found',
                'timeStamp' => now()->toDateTimeString(),
            ], 404);
        }

        // 🔒 权限检查
        if (!$this->canManageTickets($event, $userId)) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Forbidden: only admin or society president can manage tickets',
                'timeStamp' => now()->toDateTimeString(),
            ], 403);
        }

        $tickets = EventTicket::where('event_id', $eventId)
            ->orderBy('sales_start_at')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_name' => $ticket->ticket_name,
                    'price' => $ticket->price,
                    'total_quantity' => $ticket->total_quantity,
                    'sold_quantity' => $ticket->sold_quantity,
                    'status' => $ticket->status,
                    'sales_start_at' => $ticket->sales_start_at,
                    'sales_end_at' => $ticket->sales_end_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
            ],
            'ticketCount' => $tickets->count(),
            'tickets' => $tickets,
            'timeStamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get single ticket detail (admin / president only)
     * GET /api/event-tickets/{ticketId}
     */
    public function show(Request $request, $ticketId): JsonResponse
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'User ID is required',
                'timeStamp' => now()->toDateTimeString(),
            ], 400);
        }

        $ticket = EventTicket::with('event')->find($ticketId);

        if (!$ticket) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Ticket not found',
                'timeStamp' => now()->toDateTimeString(),
            ], 404);
        }

        // 🔒 权限检查
        if (!$this->canManageTickets($ticket->event, $userId)) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Forbidden',
                'timeStamp' => now()->toDateTimeString(),
            ], 403);
        }

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'ticket' => [
                'id' => $ticket->id,
                'ticket_name' => $ticket->ticket_name,
                'price' => $ticket->price,
                'total_quantity' => $ticket->total_quantity,
                'sold_quantity' => $ticket->sold_quantity,
                'status' => $ticket->status,
                'sales_start_at' => $ticket->sales_start_at,
                'sales_end_at' => $ticket->sales_end_at,
                'event' => [
                    'id' => $ticket->event->id,
                    'name' => $ticket->event->name,
                ],
            ],
            'timeStamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Active tickets for buyers (no auth)
     * GET /api/events/{event}/tickets/active
     */
    public function active(Request $request, $eventId): JsonResponse
    {
        $tickets = EventTicket::where('event_id', $eventId)
            ->where('status', 'active')
            ->where('sales_start_at', '<=', now())
            ->where('sales_end_at', '>=', now())
            ->get();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'tickets' => $tickets,
            'timeStamp' => now()->toDateTimeString(),
        ]);
    }

    private function requestId(Request $request): string
    {
        return $request->header('X-Request-ID') ?? Str::uuid()->toString();
    }
}
