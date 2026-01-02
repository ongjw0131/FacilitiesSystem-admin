<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\User;
use App\Models\SocietyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocietyController extends Controller
{
    public function index()
    {
        $societies = Society::where('isDelete', false)
                           ->with('members')
                           ->get();

        $pendingRequestSocietyIds = [];
        $declinedRequestSocietyIds = [];

        // Filter out societies where user is an active member
        if (auth()->check()) {
            $activeMemberSocietyIds = SocietyUser::where('userID', auth()->id())
                                                 ->where('status', 'active')
                                                 ->pluck('societyID')
                                                 ->toArray();
            
            // Get pending requests for the current user
            $pendingRequestSocietyIds = SocietyUser::where('userID', auth()->id())
                                                   ->where('status', 'pending')
                                                   ->pluck('societyID')
                                                   ->toArray();
            
            // Get declined requests for the current user
            $declinedRequestSocietyIds = SocietyUser::where('userID', auth()->id())
                                                    ->where('status', 'declined')
                                                    ->pluck('societyID')
                                                    ->toArray();
            
            $societies = $societies->filter(function ($society) use ($activeMemberSocietyIds) {
                return !in_array($society->societyID, $activeMemberSocietyIds);
            });
        }

        return view('society.index', compact('societies', 'pendingRequestSocietyIds', 'declinedRequestSocietyIds'));
    }

    public function joined()
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login to view your joined societies.');
        }

        // Get all societies the user has joined with active status and has a role
        $joinedSocietyIds = SocietyUser::where('userID', auth()->id())
                                       ->where('status', 'active')
                                       ->whereNotNull('position')
                                       ->pluck('societyID')
                                       ->toArray();

        $societies = Society::where('isDelete', false)
                           ->whereIn('societyID', $joinedSocietyIds)
                           ->with('members')
                           ->get();

        return view('society.joined', compact('societies'));
    }

    public function join($societyID)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login to join a society.');
        }

        $society = Society::where('societyID', $societyID)
                         ->where('isDelete', false)
                         ->firstOrFail();

        // Check if user is already a member
        $existingMember = SocietyUser::where('userID', auth()->id())
                                     ->where('societyID', $societyID)
                                     ->first();

        if ($existingMember) {
            return redirect()->back()->with('error', 'You are already a member of this society.');
        }

        // Determine initial status based on join type
        $status = 'active'; // For now, all joins are active

        // Create society_user record
        SocietyUser::create([
            'userID' => auth()->id(),
            'societyID' => $societyID,
            'position' => 'member',
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Successfully joined the society!');
    }

    public function show($societyID)
    {
        $society = Society::where('societyID', $societyID)
                         ->where('isDelete', false)
                         ->with('members', 'posts')
                         ->firstOrFail();
        
        // Check if user is authenticated and has active status with a role
        if (auth()->check()) {
            $userMembership = $society->members->where('userID', auth()->id())->first();
            
            // Check if user is active member with a role (member, committee, president)
            if (!$userMembership || $userMembership->status !== 'active' || !in_array($userMembership->position, ['member', 'committee', 'president'])) {
                return redirect()->route('society.index')->with('error', 'You do not have access to this society.');
            }
        } else {
            // Not authenticated, redirect to login
            return redirect()->route('user.login')->with('error', 'Please login to view this society.');
        }
        
        $posts = $society->posts()
                        ->where('isDelete', false)
                        ->with('user', 'comments', 'images', 'files')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        $members = $society->members;
        $isMember = false;
        
        if (auth()->check()) {
            $isMember = $society->members->where('userID', auth()->id())->isNotEmpty();
        }
        
        return view('society.show', compact('society', 'posts', 'members', 'isMember'));
    }

    public function create()
    {
        return view('society.create');
    }

    public function store(Request $request)
    {
        try {
            // Validation - societyName must be unique
            $validated = $request->validate([
                'societyName' => 'required|string|max:255|unique:societies,societyName',
                'societyDescription' => 'required|string',
                'joinType' => 'required|in:open,approval,closed',
                'presidentID' => 'required|exists:users,id',
            ], [
                'societyName.unique' => 'A society with this name already exists. Please choose a different name.',
            ]);

            // Create the society
            $society = Society::create([
                'societyName' => $validated['societyName'],
                'societyDescription' => $validated['societyDescription'],
                'joinType' => $validated['joinType'],
            ]);

            // Add the selected user as president of the society
            SocietyUser::create([
                'userID' => $validated['presidentID'],
                'societyID' => $society->societyID,
                'position' => 'president',
                'status' => 'active',
            ]);

            return redirect()->route('society.create')->with('success', 'Society created successfully with president assigned!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the society. Please try again.');
        }
    }

    public function settings($societyID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login to access settings.');
        }

        $society = Society::where('societyID', $societyID)
                         ->where('isDelete', false)
                         ->with('members')
                         ->firstOrFail();

        // Check if user is president
        $userMembership = $society->members->where('userID', auth()->id())->first();
        if (!$userMembership || $userMembership->position !== 'president') {
            return redirect()->back()->with('error', 'Only presidents can access society settings.');
        }

        return view('society.settings', compact('society'));
    }

    public function updateSettings(Request $request, $societyID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->with('members')
                             ->firstOrFail();

            // Check if user is president
            $userMembership = $society->members->where('userID', auth()->id())->first();
            if (!$userMembership || $userMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only presidents can modify settings.');
            }

            // Validate input
            $validated = $request->validate([
                'societyDescription' => 'required|string|max:500',
                'joinType' => 'required|in:open,approval,closed',
                'whoCanPost' => 'required|in:president_only,committee,all',
                'societyPhotoPath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6144',
                'deleteCurrentPhoto' => 'nullable|in:0,1',
            ]);

            // Handle photo operations
            $photoPath = $society->societyPhotoPath;
            \Log::info('deleteCurrentPhoto value: ' . $request->input('deleteCurrentPhoto'));
            
            // Delete current photo if requested
            if ($request->input('deleteCurrentPhoto') == 1) {
                \Log::info('Deleting photo: ' . $photoPath);
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                    \Log::info('Photo deleted successfully');
                }
                $photoPath = null;
            }
            
            // Upload new photo (replaces current if exists)
            if ($request->hasFile('societyPhotoPath')) {
                // Delete old photo if exists
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                
                // Store new photo
                $photoPath = $request->file('societyPhotoPath')->store('society-photos', 'public');
            }

            // Update society
            $society->update([
                'societyDescription' => $validated['societyDescription'],
                'joinType' => $validated['joinType'],
                'whoCanPost' => $validated['whoCanPost'],
                'societyPhotoPath' => $photoPath,
            ]);

            // If changing to open, remove all declined join requests
            if ($validated['joinType'] === 'open') {
                SocietyUser::where('societyID', $societyID)
                           ->where('status', 'declined')
                           ->delete();
            }

            return redirect()->back()->with('success', 'Society settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the people management page for a society (President only)
     */
    public function people($societyID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login to view society members.');
        }

        $society = Society::where('societyID', $societyID)
                         ->where('isDelete', false)
                         ->firstOrFail();

        // Check if user is a member of this society
        $userMembership = SocietyUser::where('userID', auth()->id())
                                     ->where('societyID', $societyID)
                                     ->first();

        if (!$userMembership) {
            return redirect()->route('society.show', $societyID)
                           ->with('error', 'You are not a member of this society.');
        }

        // Only president can access this page
        if ($userMembership->position !== 'president') {
            return redirect()->route('society.show', $societyID)
                           ->with('error', 'Only presidents can manage society members.');
        }

        // Get all members sorted by position hierarchy
        $members = SocietyUser::where('societyID', $societyID)
                             ->with('user')
                             ->whereIn('status', ['active', 'declined'])
                             ->get()
                             ->sortBy(function($member) {
                                 $positionOrder = ['president' => 1, 'committee' => 2, 'member' => 3];
                                 return $positionOrder[$member->position] ?? 999;
                             })
                             ->values();

        // Get pending join requests
        $pendingRequests = SocietyUser::where('societyID', $societyID)
                                      ->where('status', 'pending')
                                      ->with('user')
                                      ->get();

        // Get declined join requests
        $declinedRequests = SocietyUser::where('societyID', $societyID)
                                       ->where('status', 'declined')
                                       ->with('user')
                                       ->get();

        $userIsPresident = $userMembership->position === 'president';
        $userIsCommittee = $userMembership->position === 'committee';

        return view('society.people', compact('society', 'members', 'pendingRequests', 'declinedRequests', 'userIsPresident', 'userIsCommittee'));
    }

    /**
     * Pass president role to a committee or member (Requirement 8 & 9)
     */
    public function passPresidentRole($societyID, $userID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            // Check if current user is president
            $currentUserMembership = SocietyUser::where('userID', auth()->id())
                                               ->where('societyID', $societyID)
                                               ->firstOrFail();

            if ($currentUserMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only presidents can pass the president role.');
            }

            // Get the member to pass president role to
            $targetMember = SocietyUser::where('userID', $userID)
                                       ->where('societyID', $societyID)
                                       ->firstOrFail();

            // Can only pass to committee or member
            if (!in_array($targetMember->position, ['committee', 'member'])) {
                return redirect()->back()->with('error', 'Can only pass president role to committee or members.');
            }

            // Prevent passing to self
            if ($userID === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot pass the role to yourself.');
            }

            // Pass the role: target becomes president, current president becomes committee
            $targetMember->update(['position' => 'president']);
            $currentUserMembership->update(['position' => 'committee']);

            return redirect()->back()->with('success', "President role passed to {$targetMember->user->name}. You are now committee.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Promote a member to committee (President only)
     */
    public function promoteToCommittee($societyID, $userID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            // Check if current user is president
            $currentUserMembership = SocietyUser::where('userID', auth()->id())
                                               ->where('societyID', $societyID)
                                               ->firstOrFail();

            if ($currentUserMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only presidents can promote members to committee.');
            }

            // Get the member to promote
            $targetMember = SocietyUser::where('userID', $userID)
                                       ->where('societyID', $societyID)
                                       ->firstOrFail();

            // Can only promote members, not already committee or president
            if ($targetMember->position !== 'member') {
                return redirect()->back()->with('error', 'Only regular members can be promoted to committee.');
            }

            // Prevent promoting self
            if ($userID === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot promote yourself.');
            }

            // Promote to committee
            $targetMember->update(['position' => 'committee', 'appointedBy' => auth()->id()]);

            return redirect()->back()->with('success', "{$targetMember->user->name} has been promoted to committee.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Downgrade committee to member (Requirement 14)
     */
    public function downgradeCommittee($societyID, $userID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            // Check if current user is president
            $currentUserMembership = SocietyUser::where('userID', auth()->id())
                                               ->where('societyID', $societyID)
                                               ->firstOrFail();

            if ($currentUserMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only presidents can downgrade committee members.');
            }

            // Get the committee member to downgrade
            $targetMember = SocietyUser::where('userID', $userID)
                                       ->where('societyID', $societyID)
                                       ->firstOrFail();

            // Can only downgrade committee members
            if ($targetMember->position !== 'committee') {
                return redirect()->back()->with('error', 'Only committee members can be downgraded.');
            }

            // Prevent downgrading self
            if ($userID === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot downgrade yourself.');
            }

            // Downgrade to member
            $targetMember->update(['position' => 'member']);

            return redirect()->back()->with('success', "{$targetMember->user->name} has been downgraded to member.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Kick a member out of the society (Requirement 12 & 13)
     * President can kick anyone (except themselves)
     * Committee can kick members only
     */
    public function kickMember($societyID, $userID)
    {
        if (!auth()->check()) {
            return redirect()->route('user.login')->with('error', 'Please login.');
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            // Get current user membership
            $currentUserMembership = SocietyUser::where('userID', auth()->id())
                                               ->where('societyID', $societyID)
                                               ->firstOrFail();

            // Only president and committee can kick
            if (!in_array($currentUserMembership->position, ['president', 'committee'])) {
                return redirect()->back()->with('error', 'Only president and committee can kick members.');
            }

            // Get the member to kick
            $targetMember = SocietyUser::where('userID', $userID)
                                       ->where('societyID', $societyID)
                                       ->firstOrFail();

            // Prevent kicking self
            if ($userID === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot kick yourself.');
            }

            // Committee can only kick members, not committee or president
            if ($currentUserMembership->position === 'committee') {
                if ($targetMember->position !== 'member') {
                    return redirect()->back()->with('error', 'Committee can only kick members.');
                }
            }

            // Delete the membership record
            $targetMember->delete();

            return redirect()->back()->with('success', "{$targetMember->user->name} has been removed from the society.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Leave a society (Requirement 10 & 11)
     * President cannot leave unless role has been passed
     * Committee and members can leave anytime
     */
    public function leaveSociety($societyID)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please login.'], 401);
        }

        try {
            $society = Society::where('societyID', $societyID)
                             ->where('isDelete', false)
                             ->firstOrFail();

            // Get current user membership
            $userMembership = SocietyUser::where('userID', auth()->id())
                                         ->where('societyID', $societyID)
                                         ->firstOrFail();

            // President cannot leave unless role is passed (i.e., they're not president anymore)
            if ($userMembership->position === 'president') {
                return response()->json([
                    'success' => false,
                    'message' => 'President cannot leave the society. You must pass the president role to someone else first.'
                ], 403);
            }

            // Committee and members can leave anytime
            $userMembership->delete();

            return response()->json([
                'success' => true,
                'message' => "You have left {$society->societyName}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request to join a society
     */
    public function requestJoin($societyID)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('user.login')->with('error', 'Please login first.');
            }

            $society = Society::find($societyID);
            if (!$society) {
                return redirect()->back()->with('error', 'Society not found.');
            }

            // Check if user is already an active member
            $activeMembership = SocietyUser::where('userID', $user->id)
                ->where('societyID', $societyID)
                ->where('status', 'active')
                ->first();

            if ($activeMembership) {
                return redirect()->back()->with('error', 'You are already a member of this society.');
            }

            // Check if user already has a pending request
            $pendingRequest = SocietyUser::where('userID', $user->id)
                ->where('societyID', $societyID)
                ->where('status', 'pending')
                ->first();

            if ($pendingRequest) {
                return redirect()->back()->with('error', 'You already have a pending join request for this society.');
            }

            // Check if user has declined request - allow them to request again
            $declinedRequest = SocietyUser::where('userID', $user->id)
                ->where('societyID', $societyID)
                ->where('status', 'declined')
                ->first();

            if ($declinedRequest) {
                // Update the declined request to pending again
                $declinedRequest->update([
                    'status' => 'pending',
                    'created_at' => now(),
                ]);
                return redirect()->back()->with('success', 'Join request submitted. Waiting for president approval.');
            }

            // Create new pending join request
            SocietyUser::create([
                'userID' => $user->id,
                'societyID' => $societyID,
                'position' => null,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Join request submitted. Waiting for president approval.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Direct join for open societies (no approval needed)
     */
    public function directJoin($societyID)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('user.login')->with('error', 'Please login first.');
            }

            $society = Society::find($societyID);
            if (!$society) {
                return redirect()->back()->with('error', 'Society not found.');
            }

            // Check if society is open
            if ($society->joinType !== 'open') {
                return redirect()->back()->with('error', 'This society does not allow direct joining.');
            }

            // Check if user is already a member
            $existingMembership = SocietyUser::where('userID', $user->id)
                ->where('societyID', $societyID)
                ->whereIn('status', ['active', 'pending'])
                ->first();

            if ($existingMembership) {
                return redirect()->back()->with('error', 'You are already a member or have a pending request for this society.');
            }

            // Check if user has declined request - allow them to join again
            $declinedRequest = SocietyUser::where('userID', $user->id)
                ->where('societyID', $societyID)
                ->where('status', 'declined')
                ->first();

            if ($declinedRequest) {
                // Update the declined request to active
                $declinedRequest->update([
                    'status' => 'active',
                    'position' => 'member',
                    'created_at' => now(),
                ]);
                return redirect()->back()->with('success', 'Successfully joined the society!');
            }

            // Create new active membership
            SocietyUser::create([
                'userID' => $user->id,
                'societyID' => $societyID,
                'position' => 'member',
                'status' => 'active',
            ]);

            return redirect()->back()->with('success', 'Successfully joined the society!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Accept join request
     */
    public function acceptJoinRequest($societyID, $userID)
    {
        try {
            $society = Society::find($societyID);
            if (!$society) {
                return redirect()->back()->with('error', 'Society not found.');
            }

            // Check if current user is president
            $userMembership = $society->members->where('userID', auth()->id())->first();
            if (!$userMembership || $userMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only president can accept requests.');
            }

            $joinRequest = SocietyUser::where('societyID', $societyID)
                ->where('userID', $userID)
                ->where('status', 'pending')
                ->first();

            if (!$joinRequest) {
                return redirect()->back()->with('error', 'Join request not found.');
            }

            // Update request - set position to member and status to active
            $joinRequest->update([
                'position' => 'member',
                'status' => 'active',
            ]);

            $user = User::find($userID);

            return redirect()->back()->with('success', "{$user->name} has been accepted as a member.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Decline join request
     */
    public function declineJoinRequest($societyID, $userID)
    {
        try {
            $society = Society::find($societyID);
            if (!$society) {
                return redirect()->back()->with('error', 'Society not found.');
            }

            // Check if current user is president
            $userMembership = $society->members->where('userID', auth()->id())->first();
            if (!$userMembership || $userMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only president can decline requests.');
            }

            $joinRequest = SocietyUser::where('societyID', $societyID)
                ->where('userID', $userID)
                ->where('status', 'pending')
                ->first();

            if (!$joinRequest) {
                return redirect()->back()->with('error', 'Join request not found.');
            }

            // Update status to declined
            $joinRequest->update([
                'status' => 'declined',
            ]);

            $user = User::find($userID);

            return redirect()->back()->with('success', "{$user->name}'s request has been declined.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove/Clear a declined join request
     */
    public function removeDeclinedRequest($societyID, $userID)
    {
        try {
            $society = Society::find($societyID);
            if (!$society) {
                return redirect()->back()->with('error', 'Society not found.');
            }

            // Check if current user is president
            $userMembership = $society->members->where('userID', auth()->id())->first();
            if (!$userMembership || $userMembership->position !== 'president') {
                return redirect()->back()->with('error', 'Only president can remove declined requests.');
            }

            $declinedRequest = SocietyUser::where('societyID', $societyID)
                ->where('userID', $userID)
                ->where('status', 'declined')
                ->first();

            if (!$declinedRequest) {
                return redirect()->back()->with('error', 'Declined request not found.');
            }

            // Delete the declined request record
            $declinedRequest->delete();

            $user = User::find($userID);

            return redirect()->back()->with('success', "{$user->name}'s declined request has been removed.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

