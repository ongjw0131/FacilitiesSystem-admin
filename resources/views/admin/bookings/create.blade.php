@extends('layouts.app')

@section('title', 'Create Facility Booking')

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
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Create Booking</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Create or hold a slot for an event.</p>
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
            <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Event</label>
                        <select id="event_select" name="event_id" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select an event</option>
                        </select>
                        <p id="event_name_display" class="text-xs text-[#616f89] dark:text-gray-400 mt-1">(EventID) EventName</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Facility</label>
                        <select id="facility_select" name="facility_select" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select a facility</option>
                            @foreach ($facilityGroups as $group)
                                <option value="{{ $group->representative_id }}" data-name="{{ $group->name }}" @selected(old('facility_name') === $group->name)>
                                    {{ $group->name }} ({{ $group->type }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="facility_name" id="facility_name" value="{{ old('facility_name') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Venue</label>
                        <select id="venue_select" name="facility_id" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" disabled>
                            <option value="">Select a facility first</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Start at</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">End at</label>
                        <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', \App\Models\FacilityBooking::STATUS_PENDING) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Reject reason (if rejected)</label>
                        <textarea name="reject_reason" rows="2"
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('reject_reason') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-sm font-semibold text-[#111318] dark:text-gray-200">Cancel</a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow hover:bg-blue-700 transition-colors">
                        Save Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const facilitySelect = document.getElementById('facility_select');
            const venueSelect = document.getElementById('venue_select');
            const facilityNameInput = document.getElementById('facility_name');
            const oldFacilityId = "{{ old('facility_id') }}";
            const oldFacilityName = "{{ old('facility_name') }}";
            const eventSelect = document.getElementById('event_select');
            const eventNameDisplay = document.getElementById('event_name_display');

            const resetVenues = (placeholder = 'Select a facility first') => {
                venueSelect.innerHTML = `<option value="">${placeholder}</option>`;
                venueSelect.disabled = true;
            };

            const loadVenues = async (facilityId, facilityName) => {
                resetVenues('Loading venues...');
                facilityNameInput.value = facilityName || '';

                if (!facilityId) {
                    resetVenues();
                    return;
                }

                try {
                    const response = await fetch(`/api/facilities/${facilityId}/venues`);
                    if (!response.ok) {
                        throw new Error('Failed to load venues');
                    }
                    const data = await response.json();
                    venueSelect.innerHTML = '<option value="">Select a venue</option>';
                    (data.venues || []).forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.id;
                        opt.textContent = v.venue_id;
                        venueSelect.appendChild(opt);
                    });
                    venueSelect.disabled = false;

                    if (oldFacilityId && facilityName === oldFacilityName) {
                        venueSelect.value = oldFacilityId;
                    }
                } catch (e) {
                    resetVenues('Failed to load venues');
                }
            };

            facilitySelect.addEventListener('change', (e) => {
                const selectedOption = e.target.selectedOptions[0];
                const facilityId = e.target.value;
                const facilityName = selectedOption ? selectedOption.dataset.name : '';
                loadVenues(facilityId, facilityName);
            });

            // Preload venues if old selection exists
            if (oldFacilityName) {
                const option = Array.from(facilitySelect.options).find(opt => opt.dataset?.name === oldFacilityName);
                if (option) {
                    option.selected = true;
                    loadVenues(option.value, oldFacilityName);
                }
            } else {
                resetVenues();
            }

            const loadEventName = (eventId, name) => {
                if (!eventId) {
                    eventNameDisplay.textContent = '(EventID) EventName';
                } else {
                    eventNameDisplay.textContent = `(${eventId}) ${name || 'Event #' + eventId}`;
                }
            };

            const loadEvents = async () => {
                eventSelect.innerHTML = '<option value="">Loading events...</option>';
                try {
                    const res = await fetch('/api/events');
                    if (!res.ok) throw new Error('Failed');
                    const data = await res.json();
                    const events = data.events || data.data || [];
                    eventSelect.innerHTML = '<option value="">Select an event</option>';
                    events.forEach(ev => {
                        const opt = document.createElement('option');
                        opt.value = ev.id;
                        opt.textContent = `(${ev.id}) ${ev.name}`;
                        opt.dataset.name = ev.name;
                        eventSelect.appendChild(opt);
                    });
                    const oldEventId = "{{ old('event_id') }}";
                    if (oldEventId) {
                        const option = Array.from(eventSelect.options).find(o => o.value === oldEventId);
                        if (option) {
                            option.selected = true;
                            loadEventName(option.value, option.dataset.name);
                        }
                    } else {
                        eventSelect.value = '';
                        loadEventName('', '');
                    }
                } catch (e) {
                    eventSelect.innerHTML = '<option value="">Failed to load events</option>';
                    loadEventName('', '');
                }
            };

            eventSelect.addEventListener('change', (e) => {
                const opt = e.target.selectedOptions[0];
                loadEventName(e.target.value, opt ? opt.dataset.name : '');
            });

            // Initial load for events and venues
            loadEvents();

            if (oldFacilityName) {
                const option = Array.from(facilitySelect.options).find(opt => opt.dataset?.name === oldFacilityName);
                if (option) {
                    option.selected = true;
                    loadVenues(option.value, oldFacilityName);
                }
            } else {
                resetVenues();
            }
        });
    </script>
@endsection
