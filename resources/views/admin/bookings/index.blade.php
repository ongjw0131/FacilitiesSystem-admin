@extends('layouts.app')

@section('title', 'Facility Bookings')

@section('content')
    @php
        $isAdmin = auth()->user()?->role === 'admin';
    @endphp
    <div class="w-full max-w-6xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Facility Bookings</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">View booked facilities by date (PENDING/APPROVED).</p>
            </div>
            @if ($isAdmin)
                <a href="{{ route('admin.bookings.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white text-sm font-semibold shadow hover:bg-blue-700 transition-colors">
                    <span class="material-symbols-outlined text-base">add</span>
                    New Booking
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-4">
            <div>
                <label class="block text-xs font-semibold text-[#616f89] dark:text-gray-400 mb-1">Date</label>
                <input type="date" name="date" value="{{ $selectedDateInput }}"
                    class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 text-sm" />
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-primary text-white text-sm font-semibold px-3 py-2 shadow hover:bg-blue-700 transition-colors">Apply</button>
                <a href="{{ route('admin.bookings.index') }}" class="rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] px-3 py-2 text-sm font-semibold text-[#111318] dark:text-gray-100">Reset</a>
            </div>
        </form>

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-left">
                <thead class="bg-[#f9fafb] dark:bg-[#111827] text-xs uppercase text-[#616f89] dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Facility</th>
                        <th class="px-6 py-3">Venue ID</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Start</th>
                        <th class="px-6 py-3">End</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Event</th>
                        @if ($isAdmin)
                            <th class="px-6 py-3 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f2f4] dark:divide-[#2a3441]">
                    @forelse ($bookings as $booking)
                        <tr class="text-sm text-[#111318] dark:text-gray-100">
                            <td class="px-6 py-4 font-semibold">{{ $booking->facility->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $booking->facility->venue_id ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $booking->facility->type ?? '-' }}</td>
                            <td class="px-6 py-4">{{ optional($booking->start_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">{{ optional($booking->end_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1">
                                    {{ ucfirst(strtolower($booking->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($booking->event)
                                    <span class="font-semibold">{{ $booking->event->name }}</span>
                                    <span class="text-[#616f89] text-xs block">Event #{{ $booking->event_id }}</span>
                                @else
                                    Event #{{ $booking->event_id }}
                                @endif
                            </td>
                            @if ($isAdmin)
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-primary hover:underline text-sm">View</a>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" class="px-6 py-6 text-center text-[#616f89]">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection
