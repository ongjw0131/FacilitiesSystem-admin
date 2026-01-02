@include('head')
@section('title', 'Event Management')

<div class="max-w-[1440px] mx-auto px-4 md:px-8 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#111318] dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-500">
                    admin_panel_settings
                </span>
                Event Management
            </h1>
            <p class="text-[#616f89] dark:text-slate-400 mt-1">
                Events managed by you as a Society President
            </p>
        </div>
    </div>

    {{-- Empty State --}}
    @if($events->isEmpty())
        <div class="bg-white dark:bg-surface-dark rounded-xl p-12 text-center border border-[#dbdfe6] dark:border-slate-700">
            <span class="material-symbols-outlined text-gray-400 text-6xl mb-4">
                event_busy
            </span>
            <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2">
                No Managed Events
            </h3>
            <p class="text-[#616f89] dark:text-slate-400">
                You are not managing any events yet.
            </p>
        </div>
    @else

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <div class="group bg-white dark:bg-surface-dark rounded-xl shadow hover:shadow-lg transition overflow-hidden">

            <!-- Header -->
            <div class="relative h-40 bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-white/30" style="font-size:90px">
                    event
                </span>

                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-orange-600">
                        MANAGE
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-5 space-y-3">
                <h3 class="text-lg font-bold text-[#111318] dark:text-white group-hover:text-orange-500 transition">
                    {{ $event->name }}
                </h3>

                <p class="text-sm text-[#616f89] dark:text-slate-400 line-clamp-2">
                    {{ $event->description ?? 'No description available.' }}
                </p>

                @if($event->start_date)
                <div class="flex items-center gap-2 text-sm text-[#616f89] dark:text-slate-400">
                    <span class="material-symbols-outlined text-orange-500 text-[18px]">
                        calendar_month
                    </span>
                    {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y • h:i A') }}
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex gap-2 p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                <a href="{{ route('events.admin.show', $event) }}" class="flex-1">
                    <button class="w-full h-10 rounded-lg border-2 border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white text-sm font-bold transition flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-base">
                            visibility
                        </span>
                        View
                    </button>
                </a>

                {{-- 下一步可以接 --}}
                {{-- <a href="{{ route('event-tickets.index', $event) }}" class="flex-1"> --}}
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@include('foot')
