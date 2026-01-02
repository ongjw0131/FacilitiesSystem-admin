<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketOrder;
use App\Models\EventSociety;
use App\Models\SocietyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketOrderController extends Controller
{
    /**
     * 🔐 Check if user can manage orders for this event
     */
    protected function canManageOrders(Event $event): bool
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

        // 2️⃣ Event → Society
        $societyIds = EventSociety::where('event_id', $event->id)
            ->pluck('society_id');

        if ($societyIds->isEmpty()) {
            return false;
        }

        // 3️⃣ Active president
        return SocietyUser::where('userID', $user->id)
            ->whereIn('societyID', $societyIds)
            ->where('position', 'president')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * List all ticket orders for an event
     * GET /events/{event}/orders
     */
    public function index(Request $request, Event $event)
    {
        if (!$this->canManageOrders($event)) {
            abort(403, 'Access denied');
        }

        $user = Auth::user();

        // ✅ 只在 controller 判断
        $isAdmin = !empty($user->is_admin)
            || (!empty($user->role) && in_array(strtolower($user->role), [
                'admin',
                'administrator',
                'superadmin'
            ]));

        $query = TicketOrder::with(['ticket', 'user'])
            ->whereHas('ticket', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            });

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'user',
                    fn($uq) =>
                    $uq->where('name', 'like', "%{$search}%")
                )->orWhereHas(
                    'ticket',
                    fn($tq) =>
                    $tq->where('ticket_name', 'like', "%{$search}%")
                );
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $orders = $query
            ->orderByDesc('ordered_at')
            ->paginate(10)
            ->withQueryString();

        return view('ticket_orders.index', compact(
            'event',
            'orders',
            'isAdmin' // 🔥 关键
        ));
    }


    /**
     * Show edit form
     */
    public function edit(TicketOrder $order)
    {
        $event = $order->ticket->event;

        if (!$this->canManageOrders($event)) {
            abort(403, 'Access denied');
        }

        $user = Auth::user();

        $isAdmin = !empty($user->is_admin)
            || (!empty($user->role) && in_array(strtolower($user->role), [
                'admin',
                'administrator',
                'superadmin'
            ]));

        $order->load(['ticket', 'user']);

        return view('ticket_orders.edit', compact(
            'order',
            'isAdmin'
        ));
    }


    /**
     * Update order status
     */
    public function update(Request $request, TicketOrder $order)
    {
        $event = $order->ticket->event;

        if (!$this->canManageOrders($event)) {
            abort(403, 'Access denied');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,paid,cancelled,expired',
            'cancel_reason' => 'nullable|string|max:255',
        ]);

        if ($data['status'] !== 'cancelled') {
            $data['cancel_reason'] = null;
        }

        $order->update($data);

        return redirect()
            ->route('ticket-orders.index', $event->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Delete order (admin / president only)
     */
    public function destroy(TicketOrder $order)
    {
        $event = $order->ticket->event;

        if (!$this->canManageOrders($event)) {
            abort(403, 'Access denied');
        }

        $order->delete();

        return back()->with('success', 'Order removed.');
    }
}
