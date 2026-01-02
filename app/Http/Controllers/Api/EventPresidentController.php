<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SocietyUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventPresidentController extends Controller
{
    /**
     * Check if user is president of the society that owns this event
     *
     * GET /api/events/{event}/is-president?user_id=1
     */
    public function isPresident(Request $request, Event $event): JsonResponse
    {
        $userId = $request->query('user_id');
        
        if (!$userId) {
            return response()->json([
                'status' => 'E',
                'message' => 'user_id is required',
            ], 400);
        }

        /**
         * STEP 1:
         * Get society IDs linked to this event
         */
        $societyIds = $event->eventSocieties()
            ->pluck('society_id');

        if ($societyIds->isEmpty()) {
            return response()->json([
                'status' => 'S',
                'isPresident' => false,
                'reason' => 'Event has no linked society',
            ]);
        }

        /**
         * STEP 2:
         * Check if user is active president of ANY linked society
         */
        $isPresident = SocietyUser::activePresident()
            ->where('userID', $userId)
            ->whereIn('societyID', $societyIds)
            ->exists();

        return response()->json([
            'status' => 'S',
            'isPresident' => $isPresident,
        ]);
    }
}
