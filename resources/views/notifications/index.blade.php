@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-blue-700 text-white py-8 px-4 md:px-10">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-lg backdrop-blur-sm">
                    <span class="material-symbols-outlined text-2xl">notifications</span>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">Notifications</h1>
                    <p class="text-blue-100 mt-1">Stay updated with society activities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-5xl mx-auto px-4 md:px-10 py-8">
        <!-- Stats Bar -->
        <div class="bg-white dark:bg-[#1a202c] rounded-lg p-6 mb-8 border border-[#e5e7eb] dark:border-[#2a3441]">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">notifications_active</span>
                </div>
                <div>
                    <p class="text-xs text-[#616f89] dark:text-gray-400">Total Notifications</p>
                    <p class="text-2xl font-bold text-[#111318] dark:text-white" id="totalCount">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex flex-wrap gap-3 mb-8">
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse ($notifications as $notification)
            <div class="notification-item bg-white dark:bg-[#1a202c] rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] hover:shadow-md transition-all">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex items-start gap-3 flex-1">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-1">
                                @if ($notification->society && $notification->society->societyPhotoPath)
                                <img src="{{ asset('storage/' . $notification->society->societyPhotoPath) }}" 
                                     alt="{{ $notification->society->societyName }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                @else
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">feed</span>
                                </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-[#111318] dark:text-white">
                                    {{ $notification->title }}
                                </h3>

                                <p class="text-sm text-[#565d66] dark:text-gray-400 mt-1">
                                    {{ $notification->message }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 mt-3 text-xs text-[#8b92a1]">
                                    @if ($notification->society)
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">group</span>
                                        {{ $notification->society->societyName }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-[#e5e7eb] dark:border-[#2a3441] pt-3">
                        <div class="flex items-center gap-2">
                            @if ($notification->post)
                            <a href="{{ route('society.post.show', ['societyID' => $notification->societyID, 'postID' => $notification->postID]) }}" 
                               class="text-sm text-primary hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                View Post
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-[#8b92a1]">notifications_none</span>
                <p class="text-[#616f89] dark:text-gray-400 mt-4">No notifications yet</p>
                <p class="text-sm text-[#8b92a1] mt-2">When you follow societies and new posts are created, you'll see them here!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
        <div class="flex justify-center items-center gap-2 mt-8">
            <!-- Previous Button -->
            @if ($notifications->onFirstPage())
            <span class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-[#8b92a1] cursor-not-allowed">
                &lt;
            </span>
            @else
            <a href="{{ $notifications->previousPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-primary hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                &lt;
            </a>
            @endif

            <!-- Page Numbers -->
            @php
                $start = max($notifications->currentPage() - 2, 1);
                $end = min($start + 4, $notifications->lastPage());
                if ($end - $start < 4) {
                    $start = max($end - 4, 1);
                }
            @endphp

            @if ($start > 1)
            <a href="{{ $notifications->url(1) }}" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                1
            </a>
            @if ($start > 2)
            <span class="px-3 py-2 text-[#8b92a1]">...</span>
            @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $notifications->currentPage())
                <span class="px-3 py-2 rounded-lg bg-primary text-white font-semibold">
                    {{ $i }}
                </span>
                @else
                <a href="{{ $notifications->url($i) }}" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    {{ $i }}
                </a>
                @endif
            @endfor

            @if ($end < $notifications->lastPage())
                @if ($end < $notifications->lastPage() - 1)
                <span class="px-3 py-2 text-[#8b92a1]">...</span>
                @endif
                <a href="{{ $notifications->url($notifications->lastPage()) }}" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    {{ $notifications->lastPage() }}
                </a>
            @endif

            <!-- Next Button -->
            @if ($notifications->hasMorePages())
            <a href="{{ $notifications->nextPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-primary hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                &gt;
            </a>
            @else
            <span class="px-3 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-[#8b92a1] cursor-not-allowed">
                &gt;
            </span>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
</script>
@endsection
