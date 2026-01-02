<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketOrder;
use Illuminate\Support\Collection;

/**
 * Ticket Service
 * 
 * Handles all ticket-related operations
 */
class TicketService
{
    /**
     * Get available tickets for an event
     */
    public function getAvailableTickets(Event $event): Collection
    {
        return EventTicket::where('event_id', $event->id)
            ->where('status', 'active')
            ->whereRaw('total_quantity > sold_quantity')
            ->where(function ($q) {
                $q->whereNull('sales_start_at')
                    ->orWhere('sales_start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sales_end_at')
                    ->orWhere('sales_end_at', '>=', now());
            })
            ->orderBy('price', 'asc')
            ->get();
    }

    /**
     * Get all tickets for an event (admin view)
     */
    public function getAllTicketsForEvent(Event $event): Collection
    {
        return EventTicket::where('event_id', $event->id)
            ->orderBy('price', 'asc')
            ->get();
    }

    /**
     * Get all ticket orders for an event
     */
    public function getEventOrders(Event $event): Collection
    {
        return TicketOrder::whereHas('ticket', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->with(['ticket', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total attendees count for an event
     */
    public function getEventAttendeesCount(Event $event): int
    {
        return TicketOrder::whereHas('ticket', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->where('status', 'paid')
            ->sum('quantity');
    }

    /**
     * Create tickets for an event
     */
    public function createTicketsForEvent(Event $event, array $ticketsData, $user): int
    {
        $ticketCount = 0;

        foreach ($ticketsData as $ticketData) {
            EventTicket::create([
                'event_id' => $event->id,
                'ticket_name' => $ticketData['ticket_name'],
                'price' => $ticketData['price'],
                'total_quantity' => $ticketData['total_quantity'],
                'sold_quantity' => 0,
                'sales_start_at' => $ticketData['sales_start_at'] ?? null,
                'sales_end_at' => $ticketData['sales_end_at'] ?? null,
                'status' => 'active',
                'created_by' => $user?->id ?? null,
            ]);
            $ticketCount++;
        }

        return $ticketCount;
    }

    /**
     * Create a ticket order
     */
    public function createTicketOrder(Event $event, int $ticketId, int $quantity, $user): array
    {
        $ticket = EventTicket::findOrFail($ticketId);

        // Verify ticket belongs to event
        if ($ticket->event_id !== $event->id) {
            throw new \Exception('Invalid ticket for this event');
        }

        // Check availability
        $remainingQuantity = $ticket->total_quantity - $ticket->sold_quantity;
        if ($quantity > $remainingQuantity) {
            throw new \Exception('Not enough tickets available');
        }

        // Calculate total
        $unitPrice = $ticket->price;
        $totalAmount = $unitPrice * $quantity;

        // Create order with all required fields
        $order = TicketOrder::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalAmount,  // For backward compatibility
            'total_amount' => $totalAmount, // Required field
            'status' => 'pending',
        ]);

        return [
            'order' => $order,
            'ticket' => $ticket,
        ];
    }

    /**
     * Mark order as paid and update ticket inventory
     */
    public function markOrderAsPaid(TicketOrder $order): bool
    {
        // Mark order as paid
        $order->update(['status' => 'paid']);

        // Update ticket sold quantity
        $ticket = $order->ticket;
        $ticket->increment('sold_quantity', $order->quantity);

        // Update ticket status if sold out
        if ($ticket->sold_quantity >= $ticket->total_quantity) {
            $ticket->update(['status' => 'sold_out']);
        }

        return true;
    }

    /**
     * Check if a ticket is available for purchase
     */
    public function isTicketAvailable(EventTicket $ticket): bool
    {
        // Check status
        if ($ticket->status !== 'active') {
            return false;
        }

        // Check quantity
        if ($ticket->sold_quantity >= $ticket->total_quantity) {
            return false;
        }

        // Check sales period
        $now = now();
        
        if ($ticket->sales_start_at && $now->lt($ticket->sales_start_at)) {
            return false;
        }

        if ($ticket->sales_end_at && $now->gt($ticket->sales_end_at)) {
            return false;
        }

        return true;
    }
}