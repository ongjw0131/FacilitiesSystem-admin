<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class TicketPurchaseController extends Controller
{
    /**
     * 图2：Show available tickets for an event
     */
    public function showTickets(Event $event)
    {
        $tickets = $event->tickets()
            ->where('status', 'active')
            ->where('sales_start_at', '<=', now())
            ->where('sales_end_at', '>=', now())
            ->get();

        return view('ticket_orders.select_ticket', compact('event', 'tickets'));
    }

    /**
     * 图3：Select quantity for a ticket
     */
    public function selectQuantity(EventTicket $ticket)
    {
        // basic guard
        if ($ticket->status !== 'active') {
            abort(403, 'Ticket not available.');
        }

        return view('ticket_orders.select_quantity', compact('ticket'));
    }

    /**
     * Store order (insert ticket_orders)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $ticket = EventTicket::lockForUpdate()->findOrFail($data['ticket_id']);

        // stock check
        $available = $ticket->total_quantity - $ticket->sold_quantity;
        if ($data['quantity'] > $available) {
            return back()->with('error', 'Not enough tickets available.');
        }

        DB::transaction(function () use ($ticket, $data) {

            TicketOrder::create([
                'ticket_id'    => $ticket->id,
                'user_id'      => Auth::id(),
                'quantity'     => $data['quantity'],
                'unit_price'   => $ticket->price,
                'total_amount' => $ticket->price * $data['quantity'],
                'status'       => 'pending',
                'ordered_at'   => now(),
                'expired_at'   => now()->addMinutes(15),
            ]);

            // increase sold_quantity
            $ticket->increment('sold_quantity', $data['quantity']);

            // auto sold_out
            if ($ticket->sold_quantity >= $ticket->total_quantity) {
                $ticket->update(['status' => 'sold_out']);
            }
        });

        return redirect()
            ->route('tickets.buy.list', $ticket->event_id)
            ->with('success', 'Ticket reserved successfully.');
    }

        /**
     * ✅ 创建 pending order + Stripe PaymentIntent
     */
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $ticket = EventTicket::findOrFail($data['ticket_id']);

        // stock check（重要）
        $available = $ticket->total_quantity - $ticket->sold_quantity;
        if ($data['quantity'] > $available) {
            return back()->with('error', 'Not enough tickets available.');
        }

        $totalAmount = $ticket->price * $data['quantity'];

        // 1️⃣ Create pending order（只一次）
        $order = TicketOrder::create([
            'ticket_id'    => $ticket->id,
            'user_id'      => Auth::id(),
            'quantity'     => $data['quantity'],
            'unit_price'   => $ticket->price,
            'total_amount' => $totalAmount,
            'status'       => 'pending',
            'ordered_at'   => now(),
        ]);

        // 2️⃣ Stripe PaymentIntent
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int) ($totalAmount * 100),
            'currency' => 'myr',
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        return view('ticket_orders.payment', [
            'order' => $order,
            'clientSecret' => $intent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * ✅ Payment success callback
     */
    public function paymentSuccess(TicketOrder $order)
    {
        if ($order->status === 'paid') {
            return redirect()
                ->route('tickets.buy.list', $order->ticket->event_id);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            $order->ticket->increment('sold_quantity', $order->quantity);

            if ($order->ticket->sold_quantity >= $order->ticket->total_quantity) {
                $order->ticket->update(['status' => 'sold_out']);
            }
        });

        return redirect()
            ->route('tickets.buy.list', $order->ticket->event_id)
            ->with('success', 'Payment successful!');
    }

    /**
     * Display user's purchased tickets
     */
    public function myTickets()
    {
        $user = Auth::user();
        $ticketOrders = TicketOrder::where('user_id', $user->id)
            ->with(['ticket.event', 'ticket'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.my-tickets', [
            'ticketOrders' => $ticketOrders,
        ]);
    }
}
