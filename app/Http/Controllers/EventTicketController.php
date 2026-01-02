<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketOrder;
use App\Models\EventSociety;
use App\Models\SocietyUser;

class EventTicketController extends Controller
{
    /**
     * 🔐 Check if user can manage tickets for this event
     */
    protected function canManageTickets(Event $event): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // 1️⃣ Admin
        if (
            !empty($user->is_admin) ||
            (!empty($user->role) &&
                in_array(strtolower($user->role), ['admin', 'administrator', 'superadmin']))
        ) {
            return true;
        }

        // 2️⃣ 找 event 关联的 society
        $societyIds = EventSociety::where('event_id', $event->id)
            ->pluck('society_id');

        if ($societyIds->isEmpty()) {
            return false;
        }

        // 3️⃣ active president
        return SocietyUser::where('userID', $user->id)
            ->whereIn('societyID', $societyIds)
            ->where('position', 'president')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Display all tickets for a specific event
     * GET /events/{event}/tickets
     */
    public function index(Event $event)
    {
        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        $event->load('tickets');

        return view('event_tickets.index', compact('event'));
    }

    /**
     * Show create ticket form
     * GET /events/{event}/tickets/create
     */
    public function create(Event $event)
    {
        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        return view('event_tickets.create', compact('event'));
    }

    /**
     * Store newly created tickets
     * POST /event-tickets
     */
    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event_id);

        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array|min:1',

            'tickets.*.ticket_name' => 'required|string|max:100',

            // 💰 price: cannot below 0
            'tickets.*.price' => 'required|numeric|min:0',

            // 🔢 quantity: default min 1
            'tickets.*.total_quantity' => 'required|integer|min:1',

            // 🕒 sales start: cannot earlier than now
            'tickets.*.sales_start_at' => 'required|date|after_or_equal:now',

            // 🕒 sales end: after start AND after now
            'tickets.*.sales_end_at' => 'required|date|after:tickets.*.sales_start_at|after_or_equal:now',

            'tickets.*.status' => 'required|in:draft,active,paused',
        ]);


        foreach ($validated['tickets'] as $ticketData) {
            EventTicket::create([
                'event_id'        => $validated['event_id'],
                'ticket_name'     => $ticketData['ticket_name'],
                'price'           => $ticketData['price'],
                'total_quantity'  => $ticketData['total_quantity'],
                'sold_quantity'   => 0,
                'sales_start_at'  => $ticketData['sales_start_at'],
                'sales_end_at'    => $ticketData['sales_end_at'],
                'status'          => $ticketData['status'],
                'created_by'      => Auth::id(),
            ]);
        }

        return redirect()
            ->route('event-tickets.index', $validated['event_id'])
            ->with('success', 'Tickets created successfully.');
    }

    /**
     * Show edit ticket form
     * GET /event-tickets/{ticket}/edit
     */
    public function edit(EventTicket $ticket)
    {
        $event = Event::findOrFail($ticket->event_id);

        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        return view('event_tickets.edit', compact('ticket'));
    }

    /**
     * Update ticket
     * PUT /event-tickets/{ticket}
     */
    public function update(Request $request, EventTicket $ticket)
    {
        $event = Event::findOrFail($ticket->event_id);

        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }
$validated = $request->validate([
    'ticket_name' => 'required|string|max:100',

    // 💰 price cannot below 0
    'price' => 'required|numeric|min:0',

    // 🔢 quantity cannot below 1
    'total_quantity' => 'required|integer|min:1',

    // 🕒 allow past start, but must be valid date
    'sales_start_at' => 'required|date',

    // 🕒 end must be after start
    'sales_end_at' => 'required|date|after:sales_start_at',

    'status' => 'required|in:draft,active,paused,sold_out,expired',
]);



        $ticket->update($validated);

        return redirect()
            ->route('event-tickets.index', $ticket->event_id)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Update ticket status only
     * PATCH /event-tickets/{ticket}/status
     */
    public function updateStatus(Request $request, EventTicket $ticket)
    {
        $event = Event::findOrFail($ticket->event_id);

        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,active,paused,sold_out,expired',
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Ticket status updated.');
    }

    /**
     * Delete ticket
     * DELETE /event-tickets/{ticket}
     */
    public function destroy(EventTicket $ticket)
    {
        $event = Event::findOrFail($ticket->event_id);

        if (!$this->canManageTickets($event)) {
            abort(403, 'Forbidden');
        }

        // ❌ cannot delete if has order records
        $hasOrders = TicketOrder::where('ticket_id', $ticket->id)->exists();

        if ($hasOrders) {
            return back()->with(
                'error',
                'Cannot delete this ticket because it has order records.'
            );
        }

        $ticket->delete();

        return redirect()
            ->route('event-tickets.index', $ticket->event_id)
            ->with('success', 'Ticket deleted successfully.');
    }
}
