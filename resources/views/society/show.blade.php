@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Society Header -->
    <div class="bg-gradient-to-r from-primary to-blue-700 text-white py-8 px-4 md:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-lg backdrop-blur-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-4xl">groups</span>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold">{{ $society->societyName }}</h1>
                        <p class="text-blue-100 mt-1">{{ $members->count() }} member{{ $members->count() !== 1 ? 's' : '' }}</p>
                    </div>
                </div>
                @php
                    $userMembership = $society->members->where('userID', auth()->id())->first();
                @endphp
                <div class="flex items-center gap-2">
                    @auth
                    <!-- Follow/Unfollow Button -->
                    <button 
                        id="followBtn"
                        class="flex items-center gap-2 bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition-colors flex-shrink-0"
                        onclick="toggleFollow({{ $society->societyID }})"
                    >
                        <span class="material-symbols-outlined text-sm" id="followIcon">favorite</span>
                        <span class="hidden md:inline text-sm font-medium" id="followText">Follow</span>
                    </button>
                    
                    <!-- Request Join Button (for non-members) -->
                    @if(!$userMembership)
                    <form action="{{ route('society.requestJoin', $society->societyID) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition-colors flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                            <span class="hidden md:inline text-sm font-medium">Request Join</span>
                        </button>
                    </form>
                    @endif
                    @endauth
                    @if($userMembership && $userMembership->position === 'president')
                    <a href="{{ route('society.settings', $society->societyID) }}" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-sm">settings</span>
                        <span class="hidden md:inline text-sm font-medium">Settings</span>
                    </a>
                    @elseif($userMembership && in_array($userMembership->position, ['committee', 'member']))
                    <button 
                        onclick="leaveSociety({{ $society->societyID }})"
                        class="flex items-center gap-2 bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition-colors flex-shrink-0"
                        title="Leave this society"
                    >
                        <span class="material-symbols-outlined text-sm">logout</span>
                        <span class="hidden md:inline text-sm font-medium">Leave</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Society Photo Banner -->
    @if($society->societyPhotoPath)
        <div class="w-full h-48 md:h-64 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $society->societyPhotoPath) }}'); background-attachment: fixed;">
            <div class="h-full w-full bg-gradient-to-b from-black/20 to-black/40"></div>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="sticky top-[60px] z-40 bg-white dark:bg-[#1a202c] border-b border-[#e5e7eb] dark:border-[#2a3441]">
        <div class="max-w-7xl mx-auto px-4 md:px-10">
            <div class="flex gap-1">
                <button 
                    class="tab-btn px-6 py-4 text-sm font-semibold transition-colors border-b-2 border-transparent" 
                    data-tab="stream"
                    id="streamTab"
                >
                    Stream
                </button>
                <button 
                    class="tab-btn px-6 py-4 text-sm font-semibold transition-colors border-b-2 border-transparent" 
                    data-tab="people"
                    id="peopleTab"
                >
                    People
                </button>
                <button 
                    class="tab-btn px-6 py-4 text-sm font-semibold transition-colors border-b-2 border-transparent" 
                    data-tab="events"
                    id="eventsTab"
                >
                    Events
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 md:px-10 py-8">
        <!-- Stream Tab -->
        <div id="stream-tab" class="tab-content">
            <!-- Create Post Button -->
            @php
                $canUserPost = false;
                if ($userMembership) {
                    if ($society->whoCanPost === 'president_only') {
                        $canUserPost = $userMembership->position === 'president_only';
                    } else {
                        $canUserPost = in_array($userMembership->position, ['president_only', 'committee']);
                    }
                }
            @endphp
            @if($canUserPost)
            <div class="mb-6">
                <button 
                    id="createPostBtn"
                    class="w-full flex items-center gap-3 bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-4 border border-[#e5e7eb] dark:border-[#2a3441] hover:shadow-md transition-all"
                >
                    <div class="w-10 h-10 bg-blue-200 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-sm text-primary">edit</span>
                    </div>
                    <span class="text-[#616f89] dark:text-gray-400 text-sm font-medium">Announce something to your society...</span>
                </button>
            </div>
            @endif

            <!-- Posts Feed -->
            <div class="space-y-4">
                @forelse($posts as $post)
                    <div class="bg-white dark:bg-[#1a202c] rounded-lg p-4 border border-[#e5e7eb] dark:border-[#2a3441] hover:shadow-md transition-shadow">
                        <!-- Post Header -->
                        <div class="flex items-start gap-3 mb-3">
                            @if($post->user->profile_picture_file_path)
                                <img src="{{ asset('storage/' . $post->user->profile_picture_file_path) }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-[#111318] dark:text-white text-sm">{{ $post->user->name }}</p>
                                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-primary text-xs font-semibold rounded">
                                        @php
                                            $memberPos = $society->members->where('userID', $post->userID)->first();
                                        @endphp
                                        {{ ucfirst($memberPos?->position ?? 'member') }}
                                    </span>
                                </div>
                                <p class="text-xs text-[#616f89] dark:text-gray-400 mt-0.5">{{ $post->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <!-- Three Dot Menu -->
                            @php
                                $canDeletePost = $userMembership && ($userMembership->position === 'president' || $post->userID === auth()->id());
                            @endphp
                            @if($canDeletePost)
                                <div class="relative group">
                                    <button class="p-1 text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-xl">more_vert</span>
                                    </button>
                                    <div class="hidden group-hover:block absolute right-0 mt-1 bg-white dark:bg-[#1a202c] border border-[#e5e7eb] dark:border-[#2a3441] rounded-lg shadow-lg z-20">
                                        <form action="{{ route('society.post.destroy', [$society->societyID, $post->postID]) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors" onclick="return confirm('Are you sure you want to delete this post?');">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Delete Post
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Post Content -->
                        <a href="{{ route('society.post.show', [$society->societyID, $post->postID]) }}" class="block group mb-3">
                            <h3 class="text-sm font-semibold text-[#111318] dark:text-white group-hover:text-primary transition-colors">{{ $post->title }}</h3>
                            <p class="text-sm text-[#616f89] dark:text-gray-400 mt-1 line-clamp-2">{{ $post->content }}</p>
                        </a>

                        <!-- Post Images & Files -->
                        @if($post->images->count() > 0 || $post->files->count() > 0)
                            <div class="mb-3 space-y-2">
                                <!-- Images Grid -->
                                @if($post->images->count() > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($post->images as $image)
                                            <img src="{{ asset('storage/' . $image->filePath) }}" alt="Post image" class="w-full h-24 rounded-lg object-cover cursor-pointer hover:opacity-90 transition-opacity">
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Files List -->
                                @if($post->files->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($post->files as $file)
                                            @php
                                                $fileExtension = strtolower(pathinfo($file->originalName ?? $file->filePath, PATHINFO_EXTENSION));
                                                $previewable = in_array($fileExtension, ['pdf', 'txt', 'json', 'csv', 'xml', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'log', 'md']);
                                            @endphp
                                            <button type="button" onclick="openFilePreview('{{ route('file.view', $file->fileID) }}', '{{ $fileExtension }}', '{{ $file->originalName ?? basename($file->filePath) }}', '{{ route('file.preview', $file->fileID) }}'); event.stopPropagation();" class="w-full text-left flex items-center gap-2 p-2 bg-[#f6f8fa] dark:bg-[#0f1419] rounded border border-[#e5e7eb] dark:border-[#2a3441] hover:bg-gray-100 dark:hover:bg-[#242f3d] transition-colors">
                                                <span class="material-symbols-outlined text-primary text-lg flex-shrink-0">description</span>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-medium text-[#111318] dark:text-white truncate">{{ $file->originalName ?? basename($file->filePath) }}</p>
                                                    <p class="text-xs text-[#616f89] dark:text-gray-400">{{ round($file->fileSize / (1024 * 1024), 2) }} MB</p>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Post Actions -->
                        <div class="flex gap-4 text-sm pt-2 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                            <a href="{{ route('society.post.show', [$society->societyID, $post->postID]) }}" class="text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors flex items-center gap-1 text-xs">
                                <span class="material-symbols-outlined text-sm">chat_bubble_outline</span>
                                <span>{{ $post->comments->where('isDelete', false)->count() }}</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-5xl text-[#d0d7de] dark:text-[#2a3441] block mb-3">feed</span>
                        <p class="text-[#616f89] dark:text-gray-400">No posts yet. Be the first to share!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Events Tab -->
        <div id="events-tab" class="tab-content hidden">
            <!-- Header with create event button for president -->
            @php
                $userMembership = $society->members->where('userID', auth()->id())->first();
            @endphp
            @if($userMembership && $userMembership->position === 'president')
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-[#111318] dark:text-white">Society Events</h3>
                    <a href="{{ route('event.create', ['society_id' => $society->societyID]) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-700 text-white rounded-lg px-4 py-2 transition-colors">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span>Create Event</span>
                    </a>
                </div>
            @endif

            <!-- Events Grid -->
            <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            <!-- Events Loading State -->
            <div id="eventsLoading" class="hidden flex items-center justify-center py-16">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                    <p class="text-[#616f89] dark:text-gray-400">Loading events...</p>
                </div>
            </div>

            <!-- Events Empty State -->
            <div id="eventsEmpty" class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-[#d0d7de] dark:text-[#2a3441] block mb-4">event_note</span>
                <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-2">No Events Yet</h3>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Check back later for upcoming society events</p>
            </div>
        </div>

        <!-- People Tab -->
        <div id="people-tab" class="tab-content hidden">
            <!-- Header with manage button for president -->
            @php
                $userMembership = $members->where('userID', auth()->id())->first();
            @endphp
            @if($userMembership && $userMembership->position === 'president')
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-[#111318] dark:text-white">Society Members</h3>
                    <a href="{{ route('society.people', $society->societyID) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-700 text-white rounded-lg px-4 py-2 transition-colors">
                        <span class="material-symbols-outlined text-sm">manage_accounts</span>
                        <span class="text-sm font-medium">Manage Members</span>
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    // Sort members by position hierarchy
                    $sortedMembers = $members->sortBy(function($member) {
                        $positionOrder = ['president' => 1, 'committee' => 2, 'member' => 3];
                        return $positionOrder[$member->position] ?? 999;
                    })->groupBy('position');
                @endphp

                @foreach(['president', 'committee', 'member'] as $position)
                    @if($sortedMembers->has($position))
                        <!-- Position Group Header -->
                        <div class="col-span-full mt-4 mb-2">
                            <h3 class="text-sm font-semibold text-[#616f89] dark:text-gray-400 uppercase tracking-wide">
                                {{ ucfirst($position) }}{{ $sortedMembers[$position]->count() > 1 ? 's' : '' }}
                            </h3>
                            <div class="mt-2 border-t border-[#e5e7eb] dark:border-[#2a3441]"></div>
                        </div>

                        @foreach($sortedMembers[$position] as $member)
                    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-5 border border-[#e5e7eb] dark:border-[#2a3441] hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-[#111318] dark:text-white">{{ $member->user->name }}</h4>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $member->user->email }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary text-xs font-semibold rounded-full">
                                        {{ ucfirst($member->position) }}
                                    </span>
                                    <button 
                                        onclick="openMemberProfile({{ $member->user->id }})"
                                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-primary text-xs font-semibold rounded-full transition-colors"
                                        title="View profile"
                                    >
                                        <span class="material-symbols-outlined text-xs">info</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Create Post Modal -->
<div id="postModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441] sticky top-0 bg-white dark:bg-[#1a202c]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Create Announcement</h2>
            <button 
                id="closePostModal"
                class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
            >
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Content -->
        <form id="postForm" action="{{ route('society.post.store', $society->societyID) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Title Input -->
            <div>
                <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">Title</label>
                <input 
                    type="text" 
                    name="title"
                    placeholder="Enter announcement title..."
                    class="w-full h-10 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 text-sm text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    required
                >
            </div>

            <!-- Content Textarea -->
            <div>
                <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">Announcement</label>
                <textarea 
                    name="content"
                    placeholder="Write your announcement here..."
                    rows="6"
                    class="w-full rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-3 text-sm text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                    required
                ></textarea>
            </div>

           
            <!-- Attachments Section -->
            <div class="space-y-3 p-4 bg-[#f6f8fa] dark:bg-[#0f1419] rounded-lg border border-[#e5e7eb] dark:border-[#2a3441]">
                <p class="text-sm font-semibold text-[#111318] dark:text-white">Attachments</p>
                
                <!-- Image Upload -->
                <div>
                    <label class="text-xs font-medium text-[#616f89] dark:text-gray-400 block mb-2">Upload Image (Max 6MB)</label>
                    <label class="flex items-center justify-center gap-2 h-10 border-2 border-dashed border-[#d0d7de] dark:border-[#2a3441] rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                        <span class="material-symbols-outlined text-sm text-primary">image</span>
                        <span class="text-xs text-primary font-medium">Upload Image</span>
                        <input type="file" accept="image/*" class="hidden" id="imageInput" name="image">
                    </label>
                    <div id="imagePreview" class="mt-2"></div>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="text-xs font-medium text-[#616f89] dark:text-gray-400 block mb-2">Upload File (Max 10MB)</label>
                    <label class="flex items-center justify-center gap-2 h-10 border-2 border-dashed border-[#d0d7de] dark:border-[#2a3441] rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                        <span class="material-symbols-outlined text-sm text-primary">attach_file</span>
                        <span class="text-xs text-primary font-medium">Upload File</span>
                        <input type="file" class="hidden" id="fileInput" name="file">
                    </label>
                    <div id="filePreview" class="mt-2"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 justify-end pt-4 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                <button 
                    type="button"
                    id="cancelPostBtn"
                    class="px-6 py-2 text-[#616f89] dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="px-6 py-2 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-sm">send</span>
                    Post Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Member Profile Modal -->
<div id="memberProfileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-xl max-w-md w-full">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-[#e5e7eb] dark:border-[#2a3441]">
            <h2 class="text-xl font-bold text-[#111318] dark:text-white">Member Profile</h2>
            <button onclick="closeMemberProfile()" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Content -->
        <div id="memberProfileContent" class="p-6">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Track whether events have been loaded
    let eventsLoaded = false;

    function openMemberProfile(userId) {
        const modal = document.getElementById('memberProfileModal');
        const content = document.getElementById('memberProfileContent');
        
        modal.classList.remove('hidden');
        content.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div></div>';
        
        // Fetch user data from API
        fetch(`/api/users/${userId}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch user data');
                return response.json();
            })
            .then(data => {
                // Assuming the API returns the user data in a 'data' or direct format
                const user = data.data || data;
                
                const profileHTML = `
                    <div class="space-y-4">
                        <!-- Avatar -->
                        <div class="flex justify-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-4xl">
                                ${user.name.charAt(0).toUpperCase()}
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-[#111318] dark:text-white">${user.name}</h3>
                            <p class="text-sm text-[#616f89] dark:text-gray-400">${user.email}</p>
                        </div>

                        <!-- Details -->
                        <div class="space-y-3 pt-4 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                            ${user.contact_number ? `
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">phone</span>
                                    <div>
                                        <p class="text-xs text-[#616f89] dark:text-gray-400">Contact</p>
                                        <p class="text-sm font-semibold text-[#111318] dark:text-white">${user.contact_number}</p>
                                    </div>
                                </div>
                            ` : ''}

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">badge</span>
                                <div>
                                    <p class="text-xs text-[#616f89] dark:text-gray-400">Role</p>
                                    <p class="text-sm font-semibold text-[#111318] dark:text-white capitalize">${user.role || 'User'}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                <div>
                                    <p class="text-xs text-[#616f89] dark:text-gray-400">Status</p>
                                    <p class="text-sm font-semibold text-[#111318] dark:text-white capitalize">${user.status || 'Active'}</p>
                                </div>
                            </div>

                            ${user.created_at ? `
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">calendar_today</span>
                                    <div>
                                        <p class="text-xs text-[#616f89] dark:text-gray-400">Member Since</p>
                                        <p class="text-sm font-semibold text-[#111318] dark:text-white">${new Date(user.created_at).toLocaleDateString()}</p>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                content.innerHTML = profileHTML;
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = `
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-5xl text-red-500 block mb-3">error</span>
                        <p class="text-[#616f89] dark:text-gray-400">Failed to load profile</p>
                    </div>
                `;
            });
    }

    function closeMemberProfile() {
        const modal = document.getElementById('memberProfileModal');
        modal.classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('memberProfileModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'memberProfileModal') {
            closeMemberProfile();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMemberProfile();
        }
    });
</script>

<script>
    // Modal Controls
    const postModal = document.getElementById('postModal');
    const createPostBtn = document.getElementById('createPostBtn');
    const closePostModal = document.getElementById('closePostModal');
    const cancelPostBtn = document.getElementById('cancelPostBtn');
    const postForm = document.getElementById('postForm');
    const imageInput = document.getElementById('imageInput');
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const filePreview = document.getElementById('filePreview');

    const IMAGE_MAX_SIZE = 6 * 1024 * 1024; // 6MB
    const FILE_MAX_SIZE = 10 * 1024 * 1024; // 10MB

    // Handle image file selection
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) return;

        // Check file size
        if (file.size > IMAGE_MAX_SIZE) {
            alert('Image size must not exceed 6MB');
            imageInput.value = '';
            imagePreview.innerHTML = '';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = function(event) {
            imagePreview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${event.target.result}" alt="Preview" class="max-h-32 rounded-lg border border-[#d0d7de] dark:border-[#2a3441]">
                    <button type="button" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 transition-colors" onclick="removeImage()">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
        });
    }

    // Handle file selection
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!file) return;

        // Check file size
        if (file.size > FILE_MAX_SIZE) {
            alert('File size must not exceed 10MB');
            fileInput.value = '';
            filePreview.innerHTML = '';
            return;
        }

        // Create preview
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        filePreview.innerHTML = `
            <div class="flex items-center justify-between bg-white dark:bg-[#1a202c] p-3 rounded-lg border border-[#d0d7de] dark:border-[#2a3441]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">attach_file</span>
                    <div>
                        <p class="text-xs font-semibold text-[#111318] dark:text-white truncate">${file.name}</p>
                        <p class="text-xs text-[#616f89] dark:text-gray-400">${fileSize} MB</p>
                    </div>
                </div>
                <button type="button" class="p-1 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors" onclick="removeFile()">
                    <span class="material-symbols-outlined text-sm text-red-600">close</span>
                </button>
            </div>
        `;
        });
    }

    // Remove image preview
    window.removeImage = function() {
        imageInput.value = '';
        imagePreview.innerHTML = '';
    };

    // Remove file preview
    window.removeFile = function() {
        if (fileInput) {
            fileInput.value = '';
            filePreview.innerHTML = '';
        }
    };

    // Open modal
    if (createPostBtn) {
        createPostBtn.addEventListener('click', () => {
            postModal.classList.remove('hidden');
        });
    }

    // Close modal
    function closeModal() {
        postModal.classList.add('hidden');
        postForm.reset();
        imagePreview.innerHTML = '';
        filePreview.innerHTML = '';
    }

    if (closePostModal) {
        closePostModal.addEventListener('click', closeModal);
    }
    if (cancelPostBtn) {
        cancelPostBtn.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside
    if (postModal) {
        postModal.addEventListener('click', (e) => {
            if (e.target === postModal) {
                closeModal();
            }
        });
    }

    // Handle form submission
    if (postForm) {
        postForm.addEventListener('submit', (e) => {
            // Allow normal form submission to POST endpoint
            // Form will submit naturally
        });
    }

    // File Preview Modal
    function openFilePreview(fileUrl, extension, fileName, previewApiUrl) {
        // Create modal if it doesn't exist
        let modal = document.getElementById('filePreviewModal');
        if (!modal) {
            document.body.insertAdjacentHTML('beforeend', `
                <div id="filePreviewModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
                    <div class="relative max-w-4xl max-h-[90vh] w-full h-full flex flex-col items-center justify-center">
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-10">
                            <h3 id="previewFileName" class="text-white font-semibold truncate max-w-md"></h3>
                            <button onclick="closeFilePreview()" class="p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div id="previewContent" class="w-full h-full flex items-center justify-center overflow-auto"></div>
                    </div>
                </div>
            `);
            modal = document.getElementById('filePreviewModal');
            modal.addEventListener('click', (e) => {
                if (e.target.id === 'filePreviewModal') closeFilePreview();
            });
        }

        const previewContent = document.getElementById('previewContent');
        const previewFileName = document.getElementById('previewFileName');
        
        previewFileName.textContent = fileName;
        previewContent.innerHTML = '';
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
            previewContent.innerHTML = `<img src="${fileUrl}" class="max-w-full max-h-full object-contain" alt="${fileName}">`;
        } else if (extension === 'pdf') {
            previewContent.innerHTML = `<iframe src="${fileUrl}" class="w-full h-full" frameborder="0"></iframe>`;
        } else if (['txt', 'csv', 'json', 'xml', 'log', 'md'].includes(extension)) {
            fetch(previewApiUrl)
                .then(r => r.ok ? r.text() : Promise.reject(r.statusText))
                .then(content => {
                    previewContent.innerHTML = `<div class="w-full h-full bg-[#1a202c] p-6 rounded-lg overflow-auto"><pre class="text-gray-300 font-mono text-sm whitespace-pre-wrap break-words">${escapeHtml(content)}</pre></div>`;
                })
                .catch(err => {
                    previewContent.innerHTML = `<div class="text-center text-white"><p class="mb-2">Error: ${err}</p></div>`;
                });
        } else {
            previewContent.innerHTML = `<div class="text-center text-white"><span class="material-symbols-outlined text-6xl mb-4">description</span><p>Preview not available</p></div>`;
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFilePreview() {
        const modal = document.getElementById('filePreviewModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function escapeHtml(text) {
        const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeFilePreview();
    });

    // Leave Society functionality
    function leaveSociety(societyID) {
        if (!confirm('Are you sure you want to leave this society? This action cannot be undone.')) return;
        
        fetch(`/society/${societyID}/leave`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('✓ ' + data.message, 'success');
                // Redirect to joined societies page after 1.5 seconds
                setTimeout(() => {
                    window.location.href = '/society/joined';
                }, 1500);
            } else {
                showNotification('Error: ' + (data.message || 'Failed to leave society'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while leaving the society', 'error');
        });
    }

    // Follow/Unfollow functionality
    function toggleFollow(societyID) {
        checkFollowStatus(societyID, (isFollowing) => {
            if (isFollowing) {
                unfollowSociety(societyID);
            } else {
                followSociety(societyID);
            }
        });
    }

    function followSociety(societyID) {
        fetch(`/society/${societyID}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateFollowButton(true);
                showNotification('✓ ' + data.message, 'success');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function unfollowSociety(societyID) {
        if (!confirm('Unfollow this society?')) return;
        
        fetch(`/society/${societyID}/unfollow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateFollowButton(false);
                showNotification('✓ ' + data.message, 'info');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function checkFollowStatus(societyID, callback) {
        fetch(`/society/${societyID}/is-following`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        })
        .then(response => response.json())
        .then(data => {
            callback(data.isFollowing);
        })
        .catch(error => console.error('Error:', error));
    }

    function updateFollowButton(isFollowing) {
        const followBtn = document.getElementById('followBtn');
        const followIcon = document.getElementById('followIcon');
        const followText = document.getElementById('followText');
        
        if (isFollowing) {
            followBtn.classList.add('bg-white/40');
            followIcon.textContent = 'favorite';
            followText.textContent = 'Following';
        } else {
            followBtn.classList.remove('bg-white/40');
            followIcon.textContent = 'favorite_border';
            followText.textContent = 'Follow';
        }
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 animate-pulse ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Initialize follow button status and tab switching
    document.addEventListener('DOMContentLoaded', function() {
        const followBtn = document.getElementById('followBtn');
        if (followBtn) {
            const societyID = {{ $society->societyID }};
            checkFollowStatus(societyID, (isFollowing) => {
                updateFollowButton(isFollowing);
            });
        }

        // Initialize tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const tabName = this.getAttribute('data-tab');
                switchTab(tabName);
            });
        });

        // Check if there's a hash in the URL to switch to a specific tab
        const hash = window.location.hash.slice(1); // Remove the # character
        const validTabs = ['stream', 'events', 'people'];
        if (hash && validTabs.includes(hash)) {
            switchTab(hash);
        } else {
            // Set initial active tab (Stream tab)
            switchTab('stream');
        }
    });

    // Tab switching
    function switchTab(tabName) {
        // Hide all tabs and remove active state
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-[#616f89]', 'dark:text-gray-400');
        });

        // Show selected tab and set active state
        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }

        const activeBtn = document.getElementById(tabName + 'Tab');
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-[#616f89]', 'dark:text-gray-400');
            activeBtn.classList.add('border-primary', 'text-primary');
        }

        // Load events when switching to events tab
        if (tabName === 'events' && !eventsLoaded) {
            loadEvents();
        }
    }

    // Load events from API
    async function loadEvents() {
        const container = document.getElementById('eventsContainer');
        const loading = document.getElementById('eventsLoading');
        const empty = document.getElementById('eventsEmpty');

        loading.classList.remove('hidden');

        try {
            const societyID = {{ $society->societyID }};
            const response = await fetch(`/api/events?society_id=${societyID}`);
            const data = await response.json();

            if (data.status === 'S' && data.events) {
                const events = data.events;
                
                if (events.length > 0) {
                    empty.classList.add('hidden');
                    container.innerHTML = '';
                    events.forEach(event => {
                        const card = createEventCard(event);
                        container.appendChild(card);
                    });
                } else {
                    container.innerHTML = '';
                    empty.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading events:', error);
            empty.classList.remove('hidden');
        } finally {
            loading.classList.add('hidden');
            eventsLoaded = true;
        }
    }

    // Create event card element
    function createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'event-card flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] hover:shadow-lg transition-shadow group';
        
        const statusColor = {
            'incoming': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'open': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'closed': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'completed': 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
        };

        const dateStart = event.start_date ? new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'TBA';
        const statusClass = statusColor[event.status] || statusColor['incoming'];
        const eventType = event.entry_type === 'FREE' ? 'Free Event' : 'Ticketed Event';

        card.innerHTML = `
            <div class="h-40 w-full bg-cover bg-center relative" style="background-image: url('${event.image_url_path || 'https://via.placeholder.com/300x200?text=Event'}'); background-position: center;">
                <div class="h-full w-full bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold ${statusClass}">
                        ${event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                    </span>
                </div>
            </div>

            <div class="flex flex-col flex-1 p-5 gap-3">
                <h3 class="text-lg font-bold text-[#111318] dark:text-white line-clamp-2">${event.name}</h3>
                
                <p class="text-sm text-[#616f89] dark:text-gray-400 line-clamp-2">${event.description || 'No description'}</p>

                <div class="space-y-2 pt-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                        <span class="text-sm text-[#616f89] dark:text-gray-400">${dateStart}</span>
                    </div>
                    
                    ${event.location ? `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                            <span class="text-sm text-[#616f89] dark:text-gray-400 truncate">${event.location}</span>
                        </div>
                    ` : ''}

                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-primary">local_offer</span>
                        <span class="text-sm text-[#616f89] dark:text-gray-400">${eventType}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 p-4 bg-[#f6f8fa] dark:bg-[#0f1419] border-t border-[#e5e7eb] dark:border-[#2a3441]">
                ${event.status === 'open' ? `
                    <a href="/event/${event.id}" class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-sm">login</span>
                        Join
                    </a>
                ` : `
                    <button disabled class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-gray-300 dark:bg-gray-700 text-white text-sm font-semibold cursor-not-allowed opacity-50">
                        <span class="material-symbols-outlined text-sm">${event.status === 'incoming' ? 'schedule' : 'done'}</span>
                        ${event.status === 'incoming' ? 'Upcoming' : event.status === 'completed' ? 'Completed' : 'Closed'}
                    </button>
                `}
                <a href="/event/${event.id}" class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] text-primary hover:bg-blue-50 dark:hover:bg-blue-900/30 text-sm font-semibold transition-colors">
                    <span class="material-symbols-outlined text-sm">info</span>
                    Details
                </a>
            </div>
        `;

        return card;
    }
</script>
@endsection
