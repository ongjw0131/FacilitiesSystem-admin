@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
    <div class="w-full max-w-4xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Booking #{{ $booking->id }}</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Facility booking overview and status.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-primary hover:underline text-sm">Back to list</a>
        </div>

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-[#616f89]">Status:</span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1">
                        {{ $booking->status }}
                    </span>
                </div>
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-primary hover:underline text-sm font-semibold">Edit</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-[#111318] dark:text-gray-100">
                <div class="space-y-2">
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Facility</p>
                        <p class="font-semibold">{{ $booking->facility->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Event</p>
                        @if ($booking->event)
                            <p class="font-semibold">{{ $booking->event->name }} (ID: {{ $booking->event_id }})</p>
                        @else
                            <p class="font-semibold">Event #{{ $booking->event_id }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Start</p>
                        <p class="font-semibold">{{ optional($booking->start_at)->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">End</p>
                        <p class="font-semibold">{{ optional($booking->end_at)->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Created by</p>
                        <p class="font-semibold">{{ $booking->creator->name ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Approved/Rejected by</p>
                        <p class="font-semibold">{{ $booking->approver->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[#616f89] dark:text-gray-400">Reject reason</p>
                        <p class="font-semibold leading-relaxed">{{ $booking->reject_reason ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
