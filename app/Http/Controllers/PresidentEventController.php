<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SocietyUser;
use App\Models\EventSociety;
use App\Models\Event;

class PresidentEventController extends Controller
{
    /**
     * Show events managed by society president
     * URL: /president/events
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Login required');
        }


        $societyIds = SocietyUser::where('userID', $user->id)
            ->where('position', 'president')
            ->where('status', 'active')
            ->pluck('societyID');

        if ($societyIds->isEmpty()) {
            // 没有管理权限，直接给空 list（不要 403，UX 比较好）
            return view('president.events.index', [
                'events' => collect(),
            ]);
        }


        $eventIds = EventSociety::whereIn('society_id', $societyIds)
            ->pluck('event_id');


        $events = Event::whereIn('id', $eventIds)
            ->where(function ($q) {
                $q->where('is_deleted', 0)
                  ->orWhereNull('is_deleted');
            })
            ->orderBy('start_date', 'desc')
            ->get();

        return view('president.events.index', compact('events'));
    }
}
