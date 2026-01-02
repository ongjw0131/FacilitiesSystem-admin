<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Facades\EventFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SocietyUser;
use App\Services\Payments\{
    PaymentContext,
    FreePaymentStrategy,
    StripePaymentStrategy
};

/**
 * Event Controller (Refactored with Facade Pattern)
 * 
 * This controller now uses the EventFacade to simplify complex operations.
 * All business logic is delegated to the facade and its underlying services.
 */
class EventController extends Controller
{
    protected EventFacade $eventFacade;

    public function __construct(EventFacade $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * Show listing with optional filters
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
        ];

        $events = $this->eventFacade->getEvents($filters);

        return view('event.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create(Request $request)
    {
        $eventData = $this->eventFacade->getEventForEdit(new Event());

        return view('event.create', $eventData);
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        try {
            $event = $this->eventFacade->createEvent($request, $request->all(), false);

            // Create event tickets if provided
            if ($request->has('tickets') && is_array($request->tickets)) {
                foreach ($request->tickets as $ticketData) {
                    if (!empty($ticketData['ticket_name']) && isset($ticketData['price']) && !empty($ticketData['total_quantity'])) {
                        \App\Models\EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_name' => $ticketData['ticket_name'],
                            'price' => $ticketData['price'],
                            'total_quantity' => $ticketData['total_quantity'],
                            'sold_quantity' => 0,
                            'sales_start_at' => $ticketData['sales_start_at'] ?? null,
                            'sales_end_at' => $ticketData['sales_end_at'] ?? null,
                            'status' => 'active',
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            // If event was created with a society context, redirect to society show page with events tab
            if ($request->has('society_id') && $request->society_id) {
                return redirect()
                    ->route('society.show', $request->society_id)
                    ->with('success', 'Event created successfully!')
                    ->fragment('events');
            }

            return redirect()
                ->route('society.show', $event)
                ->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $event = $this->eventFacade->createEvent($request, $request->all(), false);

            // Create event tickets if provided
            if ($request->has('tickets') && is_array($request->tickets)) {
                foreach ($request->tickets as $ticketData) {
                    if (!empty($ticketData['ticket_name']) && isset($ticketData['price']) && !empty($ticketData['total_quantity'])) {
                        \App\Models\EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_name' => $ticketData['ticket_name'],
                            'price' => $ticketData['price'],
                            'total_quantity' => $ticketData['total_quantity'],
                            'sold_quantity' => 0,
                            'sales_start_at' => $ticketData['sales_start_at'] ?? null,
                            'sales_end_at' => $ticketData['sales_end_at'] ?? null,
                            'status' => 'active',
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            // If event was created with a society context, redirect to society show page with events tab
            if ($request->has('society_id') && $request->input('society_id')) {
                return redirect("/society/{$request->input('society_id')}#events")
                    ->with('success', 'Event created successfully!');
            }

            // Fallback: redirect to event show (no society context)
            return redirect("/events/{$event->id}")
                ->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            Log::error('Event creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified event
     */
    public function show(Event $event, Request $request)
    {
        // Handle payment success callback
        if ($request->query('success') == 1 && $request->query('session_id')) {
            $success = $this->eventFacade->handlePaymentSuccess(
                $event,
                $request->query('session_id')
            );

            if ($success) {
                return redirect()
                    ->route('events.show', $event)
                    ->with('success', 'Payment successful! Your ticket has been confirmed.');
            }
        }

        // Handle payment cancellation
        if ($request->query('canceled')) {
            return redirect()
                ->route('events.show', $event)
                ->with('error', 'Payment was cancelled.');
        }

        // Get event details with tickets
        $eventData = $this->eventFacade->getEventDetails($event, Auth::id());

        return view('event.show', $eventData);
    }

    public function showSo(Event $event, Request $request)
    {
        // Handle payment success callback
        if ($request->query('success') == 1 && $request->query('session_id')) {
            $success = $this->eventFacade->handlePaymentSuccess(
                $event,
                $request->query('session_id')
            );

            if ($success) {
                return redirect()
                    ->route('event.societyShow', $event)
                    ->with('success', 'Payment successful! Your ticket has been confirmed.');
            }
        }

        // Handle payment cancellation
        if ($request->query('canceled')) {
            return redirect()
                ->route('events.show', $event)
                ->with('error', 'Payment was cancelled.');
        }

        // Get event details with tickets
        $eventData = $this->eventFacade->getEventDetails($event, Auth::id());

        return view('event.societyShow', $eventData);
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        $eventData = $this->eventFacade->getEventForEdit($event);

        return view('admin.events.edit', $eventData);
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        try {
            $this->eventFacade->updateEvent($event, $request, $request->all());

            return redirect()
                ->route('events.show', $event)
                ->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified event
     */
    public function destroy(Event $event)
    {
        $this->eventFacade->softDeleteEvent($event);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Show ticket purchase form
     */
    /**
     * Show ticket purchase form
     */
    /**
     * Show ticket purchase form
     */
    public function showPurchaseForm(Request $request, Event $event)
    {
        $eventData = $this->eventFacade->getEventDetails($event, Auth::id());

        // Update this to match your actual view file location
        return view('event.ticket_purchase', $eventData);
    }

    /**
     * Process ticket purchase and redirect to payment
     */
    public function processPurchase(Request $request, Event $event)
    {
        try {
            // ✅ Input validation
            $validated = $request->validate([
                'ticket_id' => 'required|exists:event_tickets,id',
                'quantity'  => 'required|integer|min:1|max:100',
            ]);

            // ✅ Create order via Facade (business logic separated)
            $result = $this->eventFacade->purchaseTickets(
                $event,
                $validated['ticket_id'],
                $validated['quantity'],
                Auth::user()
            );

            $order  = $result['order'];
            $ticket = $result['ticket'];

            // ✅ Strategy selection (runtime)
            $strategy = $ticket->price == 0
                ? app(FreePaymentStrategy::class)
                : app(StripePaymentStrategy::class);

            // ✅ Execute payment strategy
            $context = new PaymentContext($strategy);
            $response = $context->execute($order, $event);

            // ✅ Handle response (controller no longer knows payment details)
            if ($response['type'] === 'free') {
                return redirect($response['redirect'])
                    ->with('success', $response['message']);
            }

            return redirect($response['redirect']);
        } catch (\Exception $e) {
            Log::error('Ticket purchase failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Provide more specific error messages
            $errorMessage = 'Ticket purchase failed. Please try again.';
            
            if (str_contains($e->getMessage(), 'No API key provided')) {
                $errorMessage = 'Payment gateway is not configured. Please configure Stripe API keys.';
            } elseif (str_contains($e->getMessage(), 'Invalid API Key')) {
                $errorMessage = 'Invalid Stripe API key. Please check your configuration.';
            } elseif (str_contains($e->getMessage(), 'insufficient_funds')) {
                $errorMessage = 'Insufficient funds for this transaction.';
            } elseif (str_contains($e->getMessage(), 'card_declined')) {
                $errorMessage = 'Your card was declined. Please try another payment method.';
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    // ==================== ADMIN ROUTES ====================

    /**
     * Display admin events listing
     */
    public function adminIndex(Request $request)
    {
        $this->ensureAdmin($request);

        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
        ];

        $events = $this->eventFacade->getEvents($filters);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show admin event details
     */
    public function adminShow(Request $request, Event $event)
    {
        $this->ensureAdminOrEventPresident($request, $event);

        $eventData = $this->eventFacade->getAdminEventDetails($event);

        return view('admin.events.show', $eventData);
    }

    /**
     * Show admin event creation form
     */
    public function adminCreate(Request $request)
    {
        $this->ensureAdmin($request);

        $eventData = $this->eventFacade->getEventForEdit(new Event());

        return view('admin.events.create', $eventData);
    }

    /**
     * Store event via admin
     */
    public function adminStore(Request $request)
    {
        $this->ensureAdmin($request);

        try {
            $event = $this->eventFacade->createEvent($request, $request->all(), true);

            $ticketCount = count($request->tickets ?? []);

            return redirect()
                ->route('user.admin_event')
                ->with('success', "Event created successfully with {$ticketCount} ticket type(s)!");
        } catch (\Exception $e) {
            Log::error('Admin event creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    /**
     * Show admin event edit form
     */
    public function adminEdit(Request $request, Event $event)
    {
        $this->ensureAdminOrEventPresident($request, $event);

        $eventData = $this->eventFacade->getEventForEdit($event);

        return view('admin.events.edit', $eventData);
    }

    /**
     * Update event via admin
     */
    public function adminUpdate(Request $request, Event $event)
    {
        $this->ensureAdmin($request);

        try {
            $this->eventFacade->updateEvent($event, $request, $request->all());

            return redirect()
                ->route('user.admin_event')
                ->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    /**
     * Delete event via admin
     */
    public function adminDestroy(Request $request, Event $event)
    {
        $this->ensureAdminOrEventPresident($request, $event);

        $this->eventFacade->softDeleteEvent($event);

        return redirect()
            ->route('events.admin.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Ensure the user is an admin
     */
    protected function ensureAdmin(Request $request)
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }
    protected function ensureAdminOrEventPresident(Request $request, Event $event)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // ✅ Admin always allowed
        if ($user->role === 'admin') {
            return;
        }

        // 🔥 Same logic as AdminOrEventPresident middleware
        $societyIds = DB::table('event_society')
            ->where('event_id', $event->id)
            ->pluck('society_id');

        if ($societyIds->isEmpty()) {
            abort(403, 'Event has no linked society');
        }

        $isPresident = SocietyUser::activePresident()
            ->where('userID', $user->id)
            ->whereIn('societyID', $societyIds)
            ->exists();

        if (!$isPresident) {
            abort(403, 'Unauthorized');
        }
    }
}
