<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketOrder;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

/**
 * Payment Service
 * 
 * Handles payment processing via Stripe
 */
class PaymentService
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        // Only set Stripe API key if it's configured and valid (not test key with asterisks)
        if (env('STRIPE_SECRET') && !str_contains(env('STRIPE_SECRET'), '*')) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
        }
    }

    /**
     * Create a Stripe checkout session
     */
    public function createCheckoutSession(TicketOrder $order, Event $event): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $order->ticket->ticket_name,
                        'description' => $event->name,
                    ],
                    'unit_amount' => $order->unit_price * 100, // Convert to cents
                ],
                'quantity' => $order->quantity,
            ]],
            'mode' => 'payment',
            'success_url' => route('events.show', ['event' => $event->id]) . '?success=1&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('events.show', ['event' => $event->id]) . '?canceled=1',
            'metadata' => [
                'order_id' => $order->id,
                'event_id' => $event->id,
            ],
        ]);
    }

    /**
     * Process successful payment from Stripe callback
     */
    public function processSuccessfulPayment(string $sessionId, Event $event): bool
    {
        try {
            $session = Session::retrieve($sessionId);
            $orderId = $session->metadata->order_id ?? null;

            if (!$orderId) {
                return false;
            }

            $order = TicketOrder::where('id', $orderId)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return false;
            }

            DB::transaction(function () use ($order) {
                $this->ticketService->markOrderAsPaid($order);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Payment processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment status for an order
     */
    public function getOrderPaymentStatus(TicketOrder $order): string
    {
        return $order->status;
    }

    /**
     * Refund an order
     */
    public function refundOrder(TicketOrder $order): bool
    {
        try {
            // Implementation would depend on storing Stripe payment intent ID
            // For now, just mark as refunded
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'refunded']);
                
                // Return ticket inventory
                $ticket = $order->ticket;
                $ticket->decrement('sold_quantity', $order->quantity);
                
                // Update ticket status if it was sold out
                if ($ticket->status === 'sold_out' && $ticket->sold_quantity < $ticket->total_quantity) {
                    $ticket->update(['status' => 'active']);
                }
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Refund failed: ' . $e->getMessage());
            return false;
        }
    }
}