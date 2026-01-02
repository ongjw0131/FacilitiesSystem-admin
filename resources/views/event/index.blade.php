@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 md:px-10 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="bg-primary/10 dark:bg-primary/20 p-3 rounded-xl">
                <span class="material-symbols-outlined text-primary text-4xl">event</span>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-[#111318] dark:text-white">Upcoming Events</h1>
                {{-- SECURITY: Numeric count - safe --}}
                <p class="text-[#616f89] dark:text-gray-400 mt-1">Discover and join {{ $events->count() }} amazing events</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#616f89]">search</span>
                {{-- SECURITY: Input value uses old() with {{ }} - auto-encoded --}}
                <input
                    name="search"
                    value="{{ old('search', request('search')) }}"
                    type="text"
                    placeholder="Search events by name or description..."
                    class="w-full h-12 pl-12 pr-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <!-- Status Filter -->
            <select
                name="status"
                class="h-12 px-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all min-w-[150px]">
                <option value="">All Status</option>
                <option value="incoming" {{ request('status')=='incoming' ? 'selected' : '' }}>Incoming</option>
                <option value="open" {{ request('status')=='open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status')=='closed' ? 'selected' : '' }}>Closed</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <!-- Filter Button -->
            <button type="submit" class="h-12 px-8 rounded-lg bg-primary hover:bg-blue-700 text-white font-bold transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                <span class="material-symbols-outlined">filter_list</span>
                <span class="hidden sm:inline">Filter</span>
            </button>
        </form>
    </div>

    <!-- Events Grid -->
    @if($events->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)

        <div class="group bg-white dark:bg-[#1a202c] rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <!-- Event Image/Header -->
            <div class="relative h-48 bg-gradient-to-br from-primary via-blue-600 to-indigo-700 overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white/20 group-hover:text-white/30 transition-all" style="font-size: 120px;">event</span>
                </div>

                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold shadow-lg
                                @if($event->status === 'open') bg-green-500 text-white
                                @elseif($event->status === 'incoming') bg-blue-500 text-white
                                @elseif($event->status === 'closed') bg-yellow-500 text-white
                                @elseif($event->status === 'cancelled') bg-red-500 text-white
                                @else bg-gray-500 text-white
                                @endif">
                        {{-- SECURITY: HTML context - auto-encoded --}}
                        {{ strtoupper($event->status ?? 'TBD') }}
                    </span>
                </div>
            </div>

            <!-- Event Content -->
            <div class="p-6 space-y-4">
                <!-- Title -->
                {{-- SECURITY: HTML context - auto-encoded --}}
                <h3 class="text-xl font-bold text-[#111318] dark:text-white line-clamp-2 group-hover:text-primary transition-colors">
                    {{ $event->name }}
                </h3>

                <!-- Description -->
                {{-- SECURITY: HTML context - auto-encoded --}}
                <p class="text-sm text-[#616f89] dark:text-gray-400 line-clamp-3 leading-relaxed">
                    {{ $event->description ?: 'No description available.' }}
                </p>

                <!-- Event Details -->
                <div class="space-y-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <!-- Date -->
                    @if($event->start_date)
                    <div class="flex items-center gap-2 text-sm text-[#616f89] dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary text-base">calendar_month</span>
                        {{-- SECURITY: Date formatted by Carbon - safe --}}
                        <span class="font-medium">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y • h:i A') }}</span>
                    </div>
                    @endif

                    <!-- Location -->
                    @if($event->location)
                    <div class="flex items-center gap-2 text-sm text-[#616f89] dark:text-gray-400">
                        <span class="material-symbols-outlined text-primary text-base">place</span>
                        {{-- SECURITY: HTML context - auto-encoded --}}
                        <span class="font-medium line-clamp-1">{{ $event->location }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 p-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700">
                @auth
                {{-- SECURITY: Route helper generates safe URL --}}
                <a href="{{ route('events.show', $event) }}" class="flex-1">
                    <button type="submit"
                        class="w-full h-10 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-bold transition-all transform hover:scale-105 shadow flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-base">event_available</span>
                        Join
                    </button>
                </a>
                @else
                {{-- Not Logged In --}}
                <a href="{{ route('login') }}" class="flex-1">
                    <button
                        class="w-full h-10 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-bold transition-all transform hover:scale-105 shadow flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-base">login</span>
                        Login to Join
                    </button>
                </a>
                @endauth

                {{-- SECURITY: Route helper generates safe URL --}}
                <a href="{{ route('event.societyShow', $event->id) }}" class="flex-1">
                    <button class="w-full h-10 rounded-lg border-2 border-primary text-primary hover:bg-primary hover:text-white text-sm font-bold transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-base">info</span>
                        Details
                    </button>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-16 text-center">
        <div class="bg-gray-100 dark:bg-gray-800 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600" style="font-size: 80px;">event_busy</span>
        </div>
        <h3 class="text-2xl font-bold text-[#111318] dark:text-white mb-2">No Events Found</h3>
        <p class="text-[#616f89] dark:text-gray-400 mb-6">
            @if(request('search') || request('status'))
            No events match your current filters. Try adjusting your search criteria.
            @else
            There are no events available at the moment. Check back soon!
            @endif
        </p>
        @if(request('search') || request('status'))
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg transition-all">
            <span class="material-symbols-outlined">refresh</span>
            Clear Filters
        </a>
        @endif
    </div>
    @endif

    <!-- Mobile Create Button -->
    @auth
    <div class="fixed bottom-6 right-6 md:hidden">
        <a href="{{ route('events.create') }}" class="flex items-center justify-center w-14 h-14 bg-primary hover:bg-blue-700 text-white rounded-full shadow-2xl transition-all transform hover:scale-110">
            <span class="material-symbols-outlined text-3xl">add</span>
        </a>
    </div>
    @endauth
</div>
@endsection

{{--
SECURITY NOTES:
================
✅ All user input (event name, description, location, search terms) auto-encoded with {{ }}
✅ Form inputs use old() helper - safe
✅ Route helpers generate safe URLs
✅ Dates formatted by Carbon - safe
✅ No JavaScript injection points
✅ No inline event handlers
✅ No user-generated content in attributes without encoding
--}}