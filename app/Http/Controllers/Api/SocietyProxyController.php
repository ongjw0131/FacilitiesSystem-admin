<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Society;
use App\Models\SocietyUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocietyProxyController extends Controller
{
    /**
     * Get all societies (lightweight list: id + name + memberCount)
     */
    public function index(Request $request): JsonResponse
    {
        $societies = Society::where('isDelete', false)
            ->orderBy('societyID', 'desc')
            ->get(['societyID as id', 'societyName as name', 'created_at', 'isDelete'])
            ->map(function($society) {
                $memberCount = SocietyUser::where('societyID', $society->id)
                    ->where('status', 'active')
                    ->count();
                
                return [
                    'id' => $society->id,
                    'name' => $society->name,
                    'memberCount' => $memberCount,
                    'created_at' => $society->created_at,
                    'isDelete' => $society->isDelete
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societies' => $societies,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Search societies by name or president
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->query('q', '');

        // Get all non-deleted societies
        $allSocieties = Society::where('isDelete', false)
            ->orderBy('societyID', 'desc')
            ->get();

        // Filter by name or president name
        $societies = $allSocieties->filter(function($society) use ($search) {
            // Check if society name matches
            $nameMatches = stripos($society->societyName, $search) !== false;
            
            if ($nameMatches) {
                return true;
            }

            // Check if president name matches
            $president = SocietyUser::where('societyID', $society->societyID)
                ->where('position', 'president')
                ->where('status', 'active')
                ->with('user')
                ->first();

            if ($president && stripos($president->user->name, $search) !== false) {
                return true;
            }

            return false;
        })->values()->map(function ($society) {
            $memberCount = SocietyUser::where('societyID', $society->societyID)
                ->where('status', 'active')
                ->count();

            $president = SocietyUser::where('societyID', $society->societyID)
                ->where('position', 'president')
                ->where('status', 'active')
                ->with('user')
                ->first();

            return [
                'id' => $society->societyID,
                'name' => $society->societyName,
                'description' => $society->societyDescription,
                'photoPath' => $society->societyPhotoPath,
                'joinType' => $society->joinType,
                'whoCanPost' => $society->whoCanPost,
                'memberCount' => $memberCount,
                'president' => $president ? [
                    'id' => $president->user->id,
                    'name' => $president->user->name,
                ] : null,
                'isDelete' => $society->isDelete,
                'createdAt' => $society->created_at,
                'updatedAt' => $society->updated_at,
            ];
        });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'totalCount' => $societies->count(),
            'societies' => $societies,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get all societies with complete details
     */
    public function allSociety(Request $request): JsonResponse
    {
        $societies = Society::orderBy('societyID', 'desc')
            ->orderBy('societyID', 'desc')
            ->get()
            ->map(function ($society) {
                $memberCount = SocietyUser::where('societyID', $society->societyID)
                    ->where('status', 'active')
                    ->count();

                $president = SocietyUser::where('societyID', $society->societyID)
                    ->where('position', 'president')
                    ->where('status', 'active')
                    ->with('user')
                    ->first();

                return [
                    'id' => $society->societyID,
                    'name' => $society->societyName,
                    'description' => $society->societyDescription,
                    'photoPath' => $society->societyPhotoPath,
                    'joinType' => $society->joinType,
                    'whoCanPost' => $society->whoCanPost,
                    'memberCount' => $memberCount,
                    'president' => $president ? [
                        'id' => $president->user->id,
                        'name' => $president->user->name,
                    ] : null,
                    'isDelete' => $society->isDelete,
                    'createdAt' => $society->created_at,
                    'updatedAt' => $society->updated_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'totalCount' => $societies->count(),
            'societies' => $societies,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get single society details
     */
    public function show(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $memberCount = SocietyUser::where('societyID', $societyID)
            ->where('status', 'active')
            ->count();

        $president = SocietyUser::where('societyID', $societyID)
            ->where('position', 'president')
            ->where('status', 'active')
            ->with('user')
            ->first();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'society' => [
                'id' => $society->societyID,
                'name' => $society->societyName,
                'description' => $society->societyDescription,
                'photoPath' => $society->societyPhotoPath,
                'joinType' => $society->joinType,
                'whoCanPost' => $society->whoCanPost,
                'memberCount' => $memberCount,
                'president' => $president ? [
                    'id' => $president->user->id,
                    'name' => $president->user->name,
                ] : null,
            ],
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get society members
     */
    public function members(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $members = SocietyUser::where('societyID', $societyID)
            ->where('status', 'active')
            ->with('user')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->user->id,
                    'name' => $member->user->name,
                    'email' => $member->user->email,
                    'position' => $member->position,
                    'joinedAt' => $member->created_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'memberCount' => $members->count(),
            'members' => $members,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get society president
     */
    public function president(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $president = SocietyUser::where('societyID', $societyID)
            ->where('position', 'president')
            ->where('status', 'active')
            ->with('user')
            ->first();

        if (!$president) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'President not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'president' => [
                'id' => $president->user->id,
                'name' => $president->user->name,
                'email' => $president->user->email,
                'position' => $president->position,
            ],
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Check if user is member
     */
    public function isMember(Request $request, $societyID, $userID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $isMember = SocietyUser::where('userID', $userID)
            ->where('societyID', $societyID)
            ->where('status', 'active')
            ->exists();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'userID' => $userID,
            'isMember' => $isMember,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get user position in society
     */
    public function userPosition(Request $request, $societyID, $userID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $member = SocietyUser::where('userID', $userID)
            ->where('societyID', $societyID)
            ->where('status', 'active')
            ->first();

        if (!$member) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'User is not a member of this society',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'userID' => $userID,
            'position' => $member->position,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get committee members
     */
    public function committee(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $committee = SocietyUser::where('societyID', $societyID)
            ->where('status', 'active')
            ->whereIn('position', ['president', 'vice-president', 'secretary', 'treasurer'])
            ->with('user')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->user->id,
                    'name' => $member->user->name,
                    'email' => $member->user->email,
                    'position' => $member->position,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'committeeCount' => $committee->count(),
            'committee' => $committee,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Check if user can post
     */
    public function canPost(Request $request, $societyID, $userID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $member = SocietyUser::where('userID', $userID)
            ->where('societyID', $societyID)
            ->where('status', 'active')
            ->first();

        $canPost = false;

        if ($society->whoCanPost === 'everyone') {
            $canPost = true;
        } elseif ($member && $society->whoCanPost === 'members') {
            $canPost = true;
        } elseif ($member && $society->whoCanPost === 'committee' && $member->position !== 'member') {
            $canPost = true;
        }

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'userID' => $userID,
            'canPost' => $canPost,
            'whoCanPost' => $society->whoCanPost,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get all presidents across all societies
     */
    public function allPresidents(Request $request): JsonResponse
    {
        $presidents = SocietyUser::where('position', 'president')
            ->where('status', 'active')
            ->with(['user', 'society'])
            ->get()
            ->map(function ($president) {
                return [
                    'societyUserID' => $president->societyUserID,
                    'userID' => $president->userID,
                    'societyID' => $president->societyID,
                    'position' => $president->position,
                    'status' => $president->status,
                    'user' => [
                        'id' => $president->user->id,
                        'name' => $president->user->name,
                        'email' => $president->user->email,
                    ],
                    'society' => [
                        'id' => $president->society->societyID,
                        'name' => $president->society->societyName,
                        'description' => $president->society->societyDescription,
                    ],
                    'joinedAt' => $president->created_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'totalPresidents' => $presidents->count(),
            'presidents' => $presidents,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get all society users (SocietyUser records)
     */
    public function societyUsers(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $societyUsers = SocietyUser::where('societyID', $societyID)
            ->with('user')
            ->get()
            ->map(function ($societyUser) {
                return [
                    'societyUserID' => $societyUser->societyUserID,
                    'userID' => $societyUser->userID,
                    'societyID' => $societyUser->societyID,
                    'position' => $societyUser->position,
                    'status' => $societyUser->status,
                    'user' => [
                        'id' => $societyUser->user->id,
                        'name' => $societyUser->user->name,
                        'email' => $societyUser->user->email,
                    ],
                    'joinedAt' => $societyUser->created_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'totalCount' => $societyUsers->count(),
            'societyUsers' => $societyUsers,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get member count
     */
    public function memberCount(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $count = SocietyUser::where('societyID', $societyID)
            ->where('status', 'active')
            ->count();

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'societyID' => $societyID,
            'memberCount' => $count,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update society details
     */
    public function update(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $validated = $request->validate([
            'societyName' => 'sometimes|string|max:255',
            'societyDescription' => 'sometimes|string|max:1000',
            'joinType' => 'sometimes|in:open,closed,invitation',
            'whoCanPost' => 'sometimes|in:everyone,members,committee,president_only',
        ]);

        if (!empty($validated)) {
            $society->update($validated);
        }

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'message' => 'Society updated successfully',
            'society' => [
                'id' => $society->societyID,
                'name' => $society->societyName,
                'description' => $society->societyDescription,
                'joinType' => $society->joinType,
                'whoCanPost' => $society->whoCanPost,
            ],
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Change society president
     */
    public function changePresident(Request $request, $societyID): JsonResponse
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->first();

        if (!$society) {
            return response()->json([
                'requestID' => $this->requestId($request),
                'status' => 'E',
                'message' => 'Society not found',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ], 404);
        }

        $validated = $request->validate([
            'userID' => 'required|exists:users,id',
        ]);

        // Remove old president
        SocietyUser::where('societyID', $societyID)
            ->where('position', 'president')
            ->update(['position' => 'member']);

        // Make user president if not already member
        $societyUser = SocietyUser::where('societyID', $societyID)
            ->where('userID', $validated['userID'])
            ->first();

        if ($societyUser) {
            $societyUser->update(['position' => 'president', 'status' => 'active']);
        } else {
            SocietyUser::create([
                'societyID' => $societyID,
                'userID' => $validated['userID'],
                'position' => 'president',
                'status' => 'active',
            ]);
        }

        $newPresident = \App\Models\User::find($validated['userID']);

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'message' => 'President changed successfully',
            'president' => [
                'id' => $newPresident->id,
                'name' => $newPresident->name,
                'email' => $newPresident->email,
            ],
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }

    private function requestId(Request $request): string
    {
        return $request->header('X-Request-ID') ?? Str::uuid()->toString();
    }

    public function ban($societyID): JsonResponse
    {
        try {
            $society = Society::find($societyID);
            
            if (!$society) {
                return response()->json([
                    'status' => 'E',
                    'message' => 'Society not found',
                ], 404);
            }

            // Mark society as deleted
            $society->isDelete = 1;
            $society->save();

            // Remove all members from the society
            SocietyUser::where('societyID', $societyID)->delete();

            return response()->json([
                'status' => 'S',
                'success' => true,
                'message' => 'Society banned successfully and all members removed',
                'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'E',
                'success' => false,
                'message' => 'Error banning society: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all banned societies (isDelete = 1)
     */
    public function banned(Request $request): JsonResponse
    {
        $bannedSocieties = Society::where('isDelete', true)
            ->orderBy('societyID', 'desc')
            ->get()
            ->map(function ($society) {
                $memberCount = SocietyUser::where('societyID', $society->societyID)
                    ->count(); // Count all members including inactive

                $president = SocietyUser::where('societyID', $society->societyID)
                    ->where('position', 'president')
                    ->with('user')
                    ->first();

                return [
                    'id' => $society->societyID,
                    'name' => $society->societyName,
                    'description' => $society->societyDescription,
                    'photoPath' => $society->societyPhotoPath,
                    'joinType' => $society->joinType,
                    'whoCanPost' => $society->whoCanPost,
                    'memberCount' => $memberCount,
                    'president' => $president ? [
                        'id' => $president->user->id,
                        'name' => $president->user->name,
                    ] : null,
                    'isDelete' => $society->isDelete,
                    'createdAt' => $society->created_at,
                    'updatedAt' => $society->updated_at,
                ];
            });

        return response()->json([
            'requestID' => $this->requestId($request),
            'status' => 'S',
            'totalCount' => $bannedSocieties->count(),
            'societies' => $bannedSocieties,
            'timeStamp' => now(config('app.timezone', 'Asia/Kuala_Lumpur'))->format('Y-m-d H:i:s'),
        ]);
    }
}
