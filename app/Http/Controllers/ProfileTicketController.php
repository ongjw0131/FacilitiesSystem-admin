<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketOrder;

class ProfileTicketController extends Controller
{
    /**
     * Display logged-in user's ticket orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $orders = TicketOrder::with([
                'ticket',
                'ticket.event'
            ])
            ->where('user_id', $user->id)
            ->orderByDesc('ordered_at')
            ->get();

        return view('user.my-tickets', [
            'orders' => $orders
        ]);
    }
}
