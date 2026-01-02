<?php

namespace App\Services\Payments;

use App\Models\TicketOrder;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class FreePaymentStrategy implements PaymentStrategy
{
    public function process(TicketOrder $order, Event $event)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            $ticket = $order->ticket;
            $ticket->increment('sold_quantity', $order->quantity);

            if ($ticket->sold_quantity >= $ticket->total_quantity) {
                $ticket->update(['status' => 'sold_out']);
            }
        });

        return [
            'type' => 'free',
            'redirect' => route('events.show', $event),
            'message' => 'Registration successful!'
        ];
    }
}
