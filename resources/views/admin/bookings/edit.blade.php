@extends('layouts.app')

@section('title', 'Edit Facility Booking')

@section('content')
    @php
        $statusOptions = [
            \App\Models\FacilityBooking::STATUS_PENDING => 'Pending',
            \App\Models\FacilityBooking::STATUS_APPROVED => 'Approved',
            \App\Models\FacilityBooking::STATUS_REJECTED => 'Rejected',
            \App\Models\FacilityBooking::STATUS_CANCELLED => 'Cancelled',
        ];
    @endphp
    <div class="w-full max-w-4xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Edit Booking</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Adjust timing, facility, or status.</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-primary hover:underline text-sm">Back to list</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-6 space-y-4">
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Event Name</label>
                        <input type="text" value="{{ $booking->event?->name ?? 'Event #' . $booking->event_id }}" readonly
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-gray-100 dark:bg-[#0f172a] px-3 py-2 text-[#111318] dark:text-gray-200" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Facility</label>
                        <select name="facility_id" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}" @selected(old('facility_id', $booking->facility_id) == $facility->id)>{{ $facility->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Start at</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at', optional($booking->start_at)->format('Y-m-d\\TH:i')) }}"
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">End at</label>
                        <input type="datetime-local" name="end_at" value="{{ old('end_at', optional($booking->end_at)->format('Y-m-d\\TH:i')) }}"
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Reject reason (if rejected)</label>
                        <textarea name="reject_reason" rows="2"
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('reject_reason', $booking->reject_reason) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-sm font-semibold text-[#111318] dark:text-gray-200">Cancel</a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow hover:bg-blue-700 transition-colors">
                        Update Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
