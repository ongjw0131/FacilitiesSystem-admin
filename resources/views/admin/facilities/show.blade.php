@extends('layouts.app')

@section('title', 'Facility Timetable')

@php
    $hourGroups = collect($timeSlots)->groupBy(fn($slot) => $slot['start']->format('H'));
@endphp

@section('content')
    <div class="w-full max-w-6xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">{{ $facility->name }} Timetable</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Daily availability from 08:00 to 22:00. Deactivated venues are greyed out.</p>
            </div>
            <a href="{{ route('admin.facilities.index') }}" class="text-primary hover:underline text-sm">Back to list</a>
        </div>

        <form method="GET" action="{{ route('admin.facilities.show', $facility) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-4">
            <div>
                <label class="block text-xs font-semibold text-[#616f89] dark:text-gray-400 mb-1">Date</label>
                <input type="date" name="date" value="{{ $selectedDateInput }}"
                    class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 text-sm">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-primary text-white text-sm font-semibold px-3 py-2 shadow hover:bg-blue-700 transition-colors">Apply</button>
                <a href="{{ route('admin.facilities.show', $facility) }}" class="rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] px-3 py-2 text-sm font-semibold text-[#111318] dark:text-gray-100">Reset</a>
            </div>
        </form>

        <div class="flex items-center gap-4 text-xs">
            <span class="inline-flex items-center gap-2"><span class="inline-block w-4 h-4 rounded bg-green-500"></span> Available</span>
            <span class="inline-flex items-center gap-2"><span class="inline-block w-4 h-4 rounded bg-blue-500"></span> Booked</span>
            <span class="inline-flex items-center gap-2"><span class="inline-block w-4 h-4 rounded bg-gray-300"></span> Inactive</span>
        </div>

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-4 flex flex-col md:flex-row gap-4 items-start">
            @if ($facility->facility_image_path)
                <img src="{{ asset('storage/' . $facility->facility_image_path) }}" alt="Facility image"
                    class="w-64 h-40 object-cover rounded border border-[#e5e7eb] dark:border-[#2a3441]">
            @else
                <div class="w-64 h-40 flex items-center justify-center text-sm text-[#616f89] dark:text-gray-400 border border-dashed border-[#e5e7eb] dark:border-[#2a3441] rounded">
                    No facility image uploaded.
                </div>
            @endif
            <div class="text-sm text-[#616f89] dark:text-gray-300">
                <p><span class="font-semibold text-[#111318] dark:text-white">Name:</span> {{ $facility->name }}</p>
                <p><span class="font-semibold text-[#111318] dark:text-white">Location:</span> {{ $facility->location }}</p>
                <p><span class="font-semibold text-[#111318] dark:text-white">Type:</span> {{ $facility->type }}</p>
                <p><span class="font-semibold text-[#111318] dark:text-white">Capacity:</span> {{ $facility->capacity }}</p>
            </div>
        </div>

        <style>
            table.timetable td.slot { height: 32px; padding: 0.25rem; }
            table.timetable td.slot.available { background: #22c55e !important; color: #fff; }
            table.timetable td.slot.booked { background: #3b82f6 !important; color: #fff; }
            table.timetable td.slot.inactive { background: transparent !important; opacity: 0.35; }
        </style>

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm overflow-x-auto">
            <table class="min-w-full text-left border-collapse timetable">
                <thead class="text-xs uppercase text-[#616f89] dark:text-gray-400">
                    <tr class="bg-[#f9fafb] dark:bg-[#111827]">
                        <th class="px-4 py-2 w-48 align-bottom">Venue/Time</th>
                        @foreach ($hourGroups as $hour => $slots)
                            <th class="text-center px-1 py-2 border-l border-[#e5e7eb] dark:border-[#2a3441]" colspan="{{ $slots->count() }}">
                                {{ \Carbon\Carbon::createFromFormat('H', $hour)->format('H:00') }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-[#f9fafb] dark:bg-[#111827]">
                        <th class="px-4 py-1"></th>
                        @foreach ($timeSlots as $slot)
                            <th class="px-1 py-1 text-center border-l border-[#e5e7eb] dark:border-[#2a3441]">
                                {{ $slot['start']->format('H:i') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-sm text-[#111318] dark:text-gray-100">
                    @forelse ($facilities as $rowFacility)
                        @php
                            $states = $slotStates[$rowFacility->id]['states'] ?? [];
                            $bookings = $slotStates[$rowFacility->id]['bookings'] ?? [];
                            $inactiveRow = !$rowFacility->is_active;
                        @endphp
                        <tr class="{{ $inactiveRow ? 'opacity-70' : '' }}">
                            <td class="px-4 py-3 font-semibold whitespace-nowrap border-t border-[#e5e7eb] dark:border-[#2a3441]">
                                {{ $rowFacility->name }} <span class="text-xs text-[#616f89]">({{ $rowFacility->venue_id }})</span>
                            </td>
                            @foreach ($timeSlots as $index => $slot)
                                @php
                                    $state = $states[$index] ?? 'available';
                                    $booking = $bookings[$index] ?? null;
                                @endphp
                                @php
                                    $cellStatus = $state;
                                @endphp
                                <td class="slot {{ $cellStatus }} px-1 py-1 min-w-16 border-t border-l border-[#e5e7eb] dark:border-[#2a3441]">
                                    @if ($cellStatus === 'booked' && $booking)
                                        <div class="w-full h-full text-center text-xs font-semibold" title="Booking #{{ $booking->id }} for event {{ $booking->event_id }}">
                                            Booked
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($timeSlots) + 1 }}" class="px-4 py-6 text-center text-[#616f89]">No facilities match this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
