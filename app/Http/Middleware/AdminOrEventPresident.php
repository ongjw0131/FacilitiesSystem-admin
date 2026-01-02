<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\SocietyUser;
use Illuminate\Support\Facades\DB;

class AdminOrEventPresident
{
    /**
     * Allow access if:
     * 1. User is admin
     * OR
     * 2. User is president of the society that owns the event
     */
    public function handle(Request $request, Closure $next)
    {
        
        // 1️⃣ Must be logged in
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();

        // 2️⃣ Admin always allowed
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 3️⃣ Route must contain {event}
        $event = $request->route('event');

        if (!$event instanceof Event) {
            abort(403, 'Invalid event context');
        }

        /**
         * 4️⃣ Get society IDs linked to this event
         * 🔥 MUST match EventPresidentController logic
         */
        // 4️⃣ Get societies linked to this event (pivot table)
        $societyIds = DB::table('event_society')->where('event_id', $event->id)->pluck('society_id');


        if ($societyIds->isEmpty()) {
            abort(403, 'Event has no linked society');
        }

        /**
         * 5️⃣ Check if user is ACTIVE president of ANY linked society
         * 🔥 MUST match API logic exactly
         */
        $isPresident = SocietyUser::activePresident()
            ->where('userID', $user->id)
            ->whereIn('societyID', $societyIds)
            ->exists();

        if (!$isPresident) {
            abort(403, 'Only admin or event president allowed');
        }

        // 6️⃣ Access granted
        return $next($request);
    }
}
