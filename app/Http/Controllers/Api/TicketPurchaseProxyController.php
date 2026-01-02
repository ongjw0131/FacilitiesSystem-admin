<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TicketPurchaseProxyController extends Controller
{
    /**
     * 1️⃣ Get available tickets for an event (buyer)
     * GET /api/events/{event}/tickets/available
     */
    public function availableTickets(Request $request, $eventId): JsonResponse
    {
        $event = Event::find($eventId);

        if (!$event) {
            return $this->error($request, 'Event not found', 404);
        }

        $tickets = $event->tickets()
            ->where('status', 'active')
            ->where('sales_start_at', '<=', now())
            ->where('sales_end_at', '>=', now())
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'ticket_name' => $t->ticket_name,
                'price' => $t->price,
                'available' => $t->total_quantity - $t->sold_quantity,
            ]);

        return $this->success($request, [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
            ],
            'tickets' => $tickets,
        ]);
    }

    /**
     * 2️⃣ Create pending order (reserve ticket)
     * POST /api/ticket-orders
     */
    public function createOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ticket_id' => 'required|exists:event_tickets,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $ticket = EventTicket::lockForUpdate()->findOrFail($data['ticket_id']);

        return DB::transaction(function () use ($ticket, $data, $request) {

            $available = $ticket->total_quantity - $ticket->sold_quantity;
            if ($data['quantity'] > $available) {
                return $this->error($request, 'Not enough tickets available', 422);
            }

            $order = TicketOrder::create([
                'ticket_id'    => $ticket->id,
                'user_id'      => Auth::id(),
                'quantity'     => $data['quantity'],
                'unit_price'   => $ticket->price,
                'total_amount' => $ticket->price * $data['quantity'],
                'status'       => 'pending',
                'ordered_at'   => now(),
                'expired_at'   => now()->addMinutes(15),
            ]);

            return $this->success($request, [
                'order' => [
                    'id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                ],
            ], 201);
        });
    }

    /**
     * 3️⃣ Create Stripe PaymentIntent
     * POST /api/ticket-orders/{order}/checkout
     */
    public function checkout(Request $request, TicketOrder $order): JsonResponse
    {
        if ($order->status !== 'pending') {
            return $this->error($request, 'Order not payable', 422);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int) ($order->total_amount * 100),
            'currency' => 'myr',
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
        ]);

        return $this->success($request, [
            'order_id' => $order->id,
            'client_secret' => $intent->client_secret,
        ]);
    }

    /**
     * 4️⃣ Mark payment success (used after Stripe confirm)
     * POST /api/ticket-orders/{order}/success
     */
    public function paymentSuccess(Request $request, TicketOrder $order): JsonResponse
    {
        if ($order->status === 'paid') {
            return $this->success($request, ['message' => 'Already paid']);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            $order->ticket->increment('sold_quantity', $order->quantity);

            if ($order->ticket->sold_quantity >= $order->ticket->total_quantity) {
                $order->ticket->update(['status' => 'sold_out']);
            }
        });

        return $this->success($request, [
            'message' => 'Payment successful',
            'order_id' => $order->id,
        ]);
    }

    /* ---------- helpers ---------- */

    private function success(Request $request, array $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            ...$data,
            'timeStamp' => now()->toDateTimeString(),
        ], $code);
    }

    private function error(Request $request, string $message, int $code): JsonResponse
    {
        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'E',
            'message' => $message,
            'timeStamp' => now()->toDateTimeString(),
        ], $code);
    }

    /**
     * 5️⃣ Get total revenue from paid orders
     * GET /api/ticket-orders/revenue/total
     */
    public function getTotalRevenue(Request $request): JsonResponse
    {
        try {
            $totalRevenue = TicketOrder::where('status', 'paid')
                ->sum('total_amount');

            return $this->success($request, [
                'total_revenue' => $totalRevenue ?? 0,
                'formatted_revenue' => 'RM ' . number_format($totalRevenue ?? 0, 2),
            ]);
        } catch (\Exception $e) {
            return $this->error($request, 'Failed to fetch revenue', 500);
        }
    }

    private function requestId(Request $request): string
    {
        return $request->header('X-Request-ID') ?? Str::uuid()->toString();
    }
}
