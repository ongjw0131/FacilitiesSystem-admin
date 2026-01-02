@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-blue-700 text-white py-8 px-4 md:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('society.show', $society->societyID) }}" class="hover:bg-white/20 rounded-lg p-2 transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">Manage Members</h1>
                    <p class="text-blue-100 mt-1">{{ $society->societyName }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 md:px-10 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg">
                <p class="text-green-800 dark:text-green-300 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
                <p class="text-red-800 dark:text-red-300 font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <div class="flex gap-1">
                <button 
                    class="tab-btn px-4 py-3 text-sm font-semibold transition-colors border-b-2 border-transparent hover:border-primary active"
                    data-tab="members"
                    id="membersTab"
                >
                    Members
                </button>
                <button 
                    class="tab-btn px-4 py-3 text-sm font-semibold transition-colors border-b-2 border-transparent hover:border-primary"
                    data-tab="requests"
                    id="requestsTab"
                >
                    Join Requests <span class="ml-2 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs rounded-full font-bold">{{ $pendingRequests->count() + $declinedRequests->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="mb-6 flex gap-2" id="membersSection">
            <button class="filter-btn px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg font-semibold transition-all active" data-filter="all">
                All
            </button>
            <button class="filter-btn px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg font-semibold transition-all" data-filter="president">
                President
            </button>
            <button class="filter-btn px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg font-semibold transition-all" data-filter="committee">
                Committee
            </button>
            <button class="filter-btn px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg font-semibold transition-all" data-filter="member">
                Member
            </button>
        </div>

        <!-- Members List -->
        <div id="members-tab" class="tab-content">
            <div class="space-y-3">
            @forelse($members as $member)
                <div class="member-card space-y-3" data-position="{{ $member->position }}">
                    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-5 border border-[#e5e7eb] dark:border-[#2a3441]">
                    <div class="flex items-center justify-between gap-4">
                        <!-- Member Info -->
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-[#111318] dark:text-white">{{ $member->user->name }}</h4>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $member->user->email }}</p>
                            </div>
                        </div>

                        <!-- Role Badge -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary text-xs font-semibold rounded-full">
                                {{ ucfirst($member->position) }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 flex-shrink-0">
                            @if($userIsPresident && $member->userID !== auth()->id())
                                @if($member->position === 'member')
                                    <!-- Promote to committee -->
                                    <button 
                                        class="px-4 py-2 border border-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded transition-colors"
                                        data-action="promote"
                                        data-member-id="{{ $member->userID }}"
                                        data-member-name="{{ $member->user->name }}"
                                        data-society-id="{{ $society->societyID }}"
                                        title="Promote to committee"
                                    >
                                        Promote
                                    </button>
                                    <!-- Pass president to member -->
                                    <button 
                                        class="px-4 py-2 border border-primary hover:bg-blue-50 dark:hover:bg-blue-900/20 text-primary dark:text-blue-400 text-xs font-semibold rounded transition-colors"
                                        data-action="pass-president"
                                        data-member-id="{{ $member->userID }}"
                                        data-member-name="{{ $member->user->name }}"
                                        data-society-id="{{ $society->societyID }}"
                                        title="Pass president role"
                                    >
                                        Pass President
                                    </button>
                                @elseif($member->position === 'committee')
                                    <!-- Pass president or downgrade to member -->
                                    <button 
                                        class="px-4 py-2 border border-primary hover:bg-blue-50 dark:hover:bg-blue-900/20 text-primary dark:text-blue-400 text-xs font-semibold rounded transition-colors"
                                        data-action="pass-president"
                                        data-member-id="{{ $member->userID }}"
                                        data-member-name="{{ $member->user->name }}"
                                        data-society-id="{{ $society->societyID }}"
                                        title="Pass president role"
                                    >
                                        Pass President
                                    </button>
                                    <button 
                                        class="px-4 py-2 border border-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 text-orange-700 dark:text-orange-400 text-xs font-semibold rounded transition-colors"
                                        data-action="downgrade"
                                        data-member-id="{{ $member->userID }}"
                                        data-member-name="{{ $member->user->name }}"
                                        data-society-id="{{ $society->societyID }}"
                                        title="Downgrade to member"
                                    >
                                        Downgrade
                                    </button>
                                @endif

                                <!-- Kick button -->
                                <button 
                                    class="px-4 py-2 border border-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-700 dark:text-red-400 text-xs font-semibold rounded transition-colors"
                                    data-action="kick"
                                    data-member-id="{{ $member->userID }}"
                                    data-member-name="{{ $member->user->name }}"
                                    data-member-role="{{ $member->position }}"
                                    data-society-id="{{ $society->societyID }}"
                                    title="Remove from society"
                                >
                                    Kick
                                </button>
                            @elseif($member->position === 'committee' && $userIsCommittee && $member->userID !== auth()->id())
                                <!-- Committee can kick members only -->
                                @if($member->position === 'member')
                                    <button 
                                        class="px-4 py-2 border border-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-700 dark:text-red-400 text-xs font-semibold rounded transition-colors"
                                        data-action="kick"
                                        data-member-id="{{ $member->userID }}"
                                        data-member-name="{{ $member->user->name }}"
                                        data-member-role="{{ $member->position }}"
                                        data-society-id="{{ $society->societyID }}"
                                        title="Remove from society"
                                    >
                                        Kick
                                    </button>
                                @endif
                            @elseif($member->userID === auth()->id() && ($member->position === 'member' || $member->position === 'committee'))
                                <!-- Member or committee can leave -->
                                <button 
                                    class="p-2 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 rounded-lg transition-colors"
                                    data-action="leave"
                                    data-member-name="{{ $member->user->name }}"
                                    data-user-role="{{ $member->position }}"
                                    data-society-id="{{ $society->societyID }}"
                                    title="Leave society"
                                >
                                    <span class="material-symbols-outlined text-yellow-600">logout</span>
                                </button>
                            @elseif($member->userID === auth()->id() && $member->position === 'president')
                                <!-- President cannot leave until role is passed -->
                            @endif
                        </div>
                    </div>
                </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-8 text-center border border-[#e5e7eb] dark:border-[#2a3441]">
                    <span class="material-symbols-outlined text-5xl text-[#d0d7de] dark:text-[#2a3441] block mb-3">people</span>
                    <p class="text-[#616f89] dark:text-gray-400">No members in this society</p>
                </div>
            @endforelse
            </div>

        <!-- No Filter Results Message -->
        <div id="noFilterResults" class="hidden bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-8 text-center border border-[#e5e7eb] dark:border-[#2a3441]">
            <span class="material-symbols-outlined text-5xl text-[#d0d7de] dark:text-[#2a3441] block mb-3">people_outline</span>
            <p class="text-[#616f89] dark:text-gray-400">No members found in this category</p>
        </div>
        </div>

        <!-- Join Requests Tab -->
        <div id="requests-tab" class="tab-content hidden">
            @if($pendingRequests->count() > 0 || $declinedRequests->count() > 0)
                <div class="space-y-3">
                    <!-- Pending Requests Section -->
                    @if($pendingRequests->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-600">schedule</span>
                                Pending Requests
                            </h3>
                            <div class="space-y-3">
                                @foreach($pendingRequests as $request)
                                    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-5 border border-[#e5e7eb] dark:border-[#2a3441]">
                                        <div class="flex items-center justify-between gap-4">
                                            <!-- Request Info -->
                                            <div class="flex items-center gap-4 flex-1">
                                                <div class="w-12 h-12 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-[#111318] dark:text-white">{{ $request->user->name }}</h4>
                                                    <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $request->user->email }}</p>
                                                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                        <span class="material-symbols-outlined text-xs align-text-bottom">schedule</span>
                                                        Requested on {{ $request->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full">
                                                    Pending
                                                </span>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex gap-2 flex-shrink-0">
                                                <!-- Accept Request -->
                                                <form action="{{ route('society.acceptRequest', [$society->societyID, $request->userID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="px-4 py-2 border border-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 text-green-700 dark:text-green-400 text-xs font-semibold rounded transition-colors flex items-center gap-1"
                                                        title="Accept request"
                                                    >
                                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                                        Accept
                                                    </button>
                                                </form>

                                                <!-- Decline Request -->
                                                <form action="{{ route('society.declineRequest', [$society->societyID, $request->userID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="px-4 py-2 border border-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-700 dark:text-red-400 text-xs font-semibold rounded transition-colors flex items-center gap-1"
                                                        title="Decline request"
                                                    >
                                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                                        Decline
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Declined Requests Section -->
                    @if($declinedRequests->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-red-600">cancel</span>
                                Declined Requests
                            </h3>
                            <div class="space-y-3">
                                @foreach($declinedRequests as $request)
                                    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-5 border border-red-200 dark:border-red-900/30">
                                        <div class="flex items-center justify-between gap-4">
                                            <!-- Request Info -->
                                            <div class="flex items-center gap-4 flex-1">
                                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-[#111318] dark:text-white">{{ $request->user->name }}</h4>
                                                    <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $request->user->email }}</p>
                                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                        <span class="material-symbols-outlined text-xs align-text-bottom">cancel</span>
                                                        Declined
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full">
                                                    Declined
                                                </span>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex gap-2 flex-shrink-0">
                                                <!-- Accept Declined Request (Undecline) -->
                                                <form action="{{ route('society.acceptRequest', [$society->societyID, $request->userID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="px-4 py-2 border border-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 text-green-700 dark:text-green-400 text-xs font-semibold rounded transition-colors flex items-center gap-1"
                                                        title="Accept this user as member"
                                                    >
                                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                                        Accept
                                                    </button>
                                                </form>

                                                <!-- Remove Declined Request -->
                                                <form action="{{ route('society.removeDeclined', [$society->societyID, $request->userID]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="px-4 py-2 border border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900/20 text-gray-700 dark:text-gray-400 text-xs font-semibold rounded transition-colors flex items-center gap-1"
                                                        title="Remove declined request record"
                                                    >
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-8 text-center border border-[#e5e7eb] dark:border-[#2a3441]">
                    <span class="material-symbols-outlined text-5xl text-[#d0d7de] dark:text-[#2a3441] block mb-3">mail_outline</span>
                    <p class="text-[#616f89] dark:text-gray-400">No join requests</p>
                </div>
            @endif
        </div>

<!-- Promote Modal -->
<div id="promoteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Promote to Committee</h2>
            <button id="promoteClose" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p id="promoteMessage" class="text-[#616f89] dark:text-gray-400 mb-6"></p>
            <form id="promoteForm" method="POST" class="space-y-4">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('promoteModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors font-semibold">
                        Promote
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pass President Modal -->
<div id="passPresidentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Pass President Role</h2>
            <button id="passPresidentClose" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p id="passPresidentMessage" class="text-[#616f89] dark:text-gray-400 mb-6"></p>
            <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                    <strong>Note:</strong> You will be downgraded to committee level after passing the president role.
                </p>
            </div>
            <form id="passPresidentForm" method="POST" class="space-y-4">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('passPresidentModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary hover:bg-blue-700 text-white rounded-lg transition-colors font-semibold">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Downgrade Modal -->
<div id="downgradeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Downgrade Committee</h2>
            <button id="downgradeClose" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p id="downgradeMessage" class="text-[#616f89] dark:text-gray-400 mb-6"></p>
            <form id="downgradeForm" method="POST" class="space-y-4">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('downgradeModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-semibold">
                        Downgrade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Kick Modal -->
<div id="kickModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Remove Member</h2>
            <button id="kickClose" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p id="kickMessage" class="text-[#616f89] dark:text-gray-400 mb-6"></p>
            <form id="kickForm" method="POST" class="space-y-4">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('kickModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-semibold">
                        Remove
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leave Modal -->
<div id="leaveModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Leave Society</h2>
            <button id="leaveClose" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p id="leaveMessage" class="text-[#616f89] dark:text-gray-400 mb-6"></p>
            <form id="leaveForm" method="POST" class="space-y-4">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('leaveModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-[#d0d7de] dark:border-[#2a3441] text-[#616f89] dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors font-semibold">
                        Leave
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@vite('resources/js/society-people.js')

<script>
    // Member Filtering
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const memberCards = document.querySelectorAll('.member-card');
        const noFilterResults = document.getElementById('noFilterResults');

        // Set initial active state on "All" button
        const allBtn = document.querySelector('[data-filter="all"]');
        if (allBtn) {
            allBtn.classList.add('active', 'bg-primary', 'text-white', 'border-primary');
            allBtn.classList.remove('border-[#d0d7de]', 'dark:border-[#2a3441]', 'text-[#616f89]', 'dark:text-gray-400');
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                let visibleCount = 0;

                // Update active button
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'bg-primary', 'text-white', 'border-primary');
                    b.classList.add('border-[#d0d7de]', 'dark:border-[#2a3441]', 'text-[#616f89]', 'dark:text-gray-400');
                });
                this.classList.add('active', 'bg-primary', 'text-white', 'border-primary');
                this.classList.remove('border-[#d0d7de]', 'dark:border-[#2a3441]', 'text-[#616f89]', 'dark:text-gray-400');

                // Filter members
                memberCards.forEach(card => {
                    if (filter === 'all' || card.dataset.position === filter) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0 && filter !== 'all') {
                    noFilterResults.classList.remove('hidden');
                } else {
                    noFilterResults.classList.add('hidden');
                }
            });
        });

        // Tab Switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const membersSection = document.getElementById('membersSection');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;

                // Remove active state from all buttons
                tabBtns.forEach(b => {
                    b.classList.remove('active', 'border-primary', 'text-primary', 'dark:text-blue-400');
                    b.classList.add('border-transparent', 'text-[#616f89]', 'dark:text-gray-400');
                });

                // Hide all tabs
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Add active state to clicked button
                this.classList.add('active', 'border-primary', 'text-primary', 'dark:text-blue-400');
                this.classList.remove('border-transparent', 'text-[#616f89]', 'dark:text-gray-400');

                // Show selected tab
                const selectedTab = document.getElementById(tab + '-tab');
                if (selectedTab) {
                    selectedTab.classList.remove('hidden');
                }

                // Show/hide filter buttons based on tab
                if (tab === 'members') {
                    membersSection.classList.remove('hidden');
                } else {
                    membersSection.classList.add('hidden');
                }
            });
        });

        // Set initial active tab
        const membersTab = document.getElementById('membersTab');
        if (membersTab) {
            membersTab.classList.add('active', 'border-primary', 'text-primary', 'dark:text-blue-400');
            membersTab.classList.remove('border-transparent', 'text-[#616f89]', 'dark:text-gray-400');
        }
    });

</script>

@endsection
