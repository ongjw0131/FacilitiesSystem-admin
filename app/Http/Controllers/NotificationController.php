<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show all notifications for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::where('userID', $user->id)
            ->with(['society', 'post.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($notificationID)
    {
        $notification = Notification::find($notificationID);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        if ($notification->userID !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        $user = Auth::user();

        Notification::where('userID', $user->id)->delete();

        return response()->json(['message' => 'All notifications cleared']);
    }

    // /**
    //  * Get all notifications as JSON (API endpoint)
    //  */
    // public function apiIndex()
    // {
    //     $user = Auth::user();

    //     $notifications = Notification::where('userID', $user->id)
    //         ->with(['society', 'post'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return response()->json([
    //         'notifications' => $notifications,
    //         'total' => $notifications->count(),
    //     ]);
    // }
}
