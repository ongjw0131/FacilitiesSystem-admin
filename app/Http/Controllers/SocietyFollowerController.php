<?php

namespace App\Http\Controllers;

use App\Models\SocietyFollower;
use App\Models\Society;
use Illuminate\Support\Facades\Auth;

class SocietyFollowerController extends Controller
{
    /**
     * Follow a society
     */
    public function follow($societyID)
    {
        $user = Auth::user();
        $society = Society::findOrFail($societyID);

        // Check if already following
        $exists = SocietyFollower::where('userID', $user->id)
            ->where('societyID', $societyID)
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Already following this society',
            ], 400);
        }

        // Create follow relationship
        SocietyFollower::create([
            'userID' => $user->id,
            'societyID' => $societyID,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Now following ' . $society->societyName,
        ]);
    }

    /**
     * Unfollow a society
     */
    public function unfollow($societyID)
    {
        $user = Auth::user();

        $follower = SocietyFollower::where('userID', $user->id)
            ->where('societyID', $societyID)
            ->first();

        if (!$follower) {
            return response()->json([
                'success' => false,
                'message' => 'Not following this society',
            ], 400);
        }

        $follower->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unfollowed society',
        ]);
    }

    /**
     * Check if user is following a society
     */
    public function isFollowing($societyID)
    {
        $user = Auth::user();

        $isFollowing = SocietyFollower::where('userID', $user->id)
            ->where('societyID', $societyID)
            ->exists();

        return response()->json([
            'societyID' => $societyID,
            'isFollowing' => $isFollowing,
        ]);
    }

    /**
     * Get all societies followed by user
     */
    public function getFollowed()
    {
        $user = Auth::user();

        $followedSocieties = $user->followedSocieties()
            ->with('society')
            ->get()
            ->map(function ($follow) {
                return [
                    'societyID' => $follow->society->societyID,
                    'societyName' => $follow->society->societyName,
                    'followedDate' => $follow->followedDate,
                ];
            });

        return response()->json([
            'followed' => $followedSocieties,
            'total' => $followedSocieties->count(),
        ]);
    }
}
