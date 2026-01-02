@extends('layouts.app')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4 md:px-10 py-8">
    <!-- Header -->
    <div class="mb-8">
        @php
            $societyId = request()->query('society_id');
            $society = $societyId ? \App\Models\Society::find($societyId) : null;
        @endphp
        <a href="{{ $society ? route('society.show', $society->societyID) . '#events' : route('events.index') }}" class="inline-flex items-center text-primary hover:text-blue-700 mb-4 transition-colors">
            <span class="material-symbols-outlined mr-2">arrow_back</span>
            <span class="font-medium">{{ $society ? 'Back to ' . $society->societyName : 'Back to Events' }}</span>
        </a>
        
        <div class="flex items-center gap-4">
            <div class="bg-primary/10 dark:bg-primary/20 p-3 rounded-xl">
                <span class="material-symbols-outlined text-primary text-4xl">add_circle</span>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-[#111318] dark:text-white">Create New Event</h1>
                <p class="text-[#616f89] dark:text-gray-400 mt-1">Fill in the details to create your event</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500">error</span>
                <div class="flex-1">
                    <h3 class="font-semibold text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h3>
                    <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('event.storeUser') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="eventForm">
        @csrf

        <!-- Hidden society_id field if coming from society context -->
        @if(request()->has('society_id'))
            <input type="hidden" name="society_id" value="{{ request()->query('society_id') }}">
        @endif

        <!-- Basic Information -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Basic Information
            </h2>

            <div class="space-y-5">
                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Event Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        value="{{ old('name') }}" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                        placeholder="Enter event name"
                        required
                    >
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Description
                    </label>
                    <textarea 
                        id="description"
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="Describe your event..."
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Location
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#616f89]">place</span>
                        <input 
                            type="text" 
                            id="location"
                            name="location" 
                            value="{{ old('location') }}" 
                            class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Event venue or address"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">calendar_month</span>
                Date & Time
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Start Date & Time
                    </label>
                    <input 
                        type="datetime-local" 
                        id="start_date"
                        name="start_date" 
                        value="{{ old('start_date') }}" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                    >
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        End Date & Time
                    </label>
                    <input 
                        type="datetime-local" 
                        id="end_date"
                        name="end_date" 
                        value="{{ old('end_date') }}" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                    >
                </div>
            </div>
        </div>

        <!-- Event Settings -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">settings</span>
                Event Settings
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Capacity
                    </label>
                    <input 
                        type="number" 
                        id="capacity"
                        name="capacity" 
                        min="0"
                        value="{{ old('capacity') }}" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="Max attendees"
                    >
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status"
                        name="status" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                        required
                    >
                        <option value="incoming" {{ old('status')=='incoming' ? 'selected' : '' }}>Incoming</option>
                        <option value="open" {{ old('status')=='open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status')=='closed' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelled" {{ old('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Entry Type -->
                
            </div>
        </div>

        <!-- Event Image -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">image</span>
                Event Image
            </h2>

            <div>
                <label for="image" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                    Upload Image (Optional)
                </label>
                <input 
                    type="file" 
                    id="image"
                    name="image" 
                    accept="image/*" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-[#111318] dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700 transition-all"
                >
                <p class="text-sm text-[#616f89] dark:text-gray-400 mt-2">Recommended: 1200x600px, Max 2MB</p>
            </div>
        </div>

    <!-- Event Tickets Section (Always visible, mandatory) -->
    <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">confirmation_number</span>
                    Event Tickets <span class="text-red-500">*</span>
                </h2>
                <p class="text-sm text-[#616f89] dark:text-gray-400 mt-1">
                    Every event must have at least one ticket. Set price to 0 for free events.
                </p>
            </div>
            <button 
                type="button" 
                id="addTicketBtn" 
                class="bg-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-all flex items-center gap-2"
            >
                <span class="material-symbols-outlined">add</span>
                Add Ticket
            </button>
        </div>

        <div id="ticketsContainer" class="space-y-4">
            <!-- Tickets will be added here dynamically -->
        </div>

        <div class="mt-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-500">lightbulb</span>
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    <p class="font-semibold mb-1">Tips:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>For free events, create a ticket with price RM 0.00</li>
                        <li>Create multiple ticket types (e.g., Early Bird, VIP, Regular) to offer different pricing tiers</li>
                        <li>Set sales start/end dates to control when tickets become available</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <!-- Facility Booking (Optional) -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">meeting_room</span>
                Facility Booking (Optional)
            </h2>

            <div class="space-y-5">
                <!-- Needs Facility Checkbox -->
                <div class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        id="needs_facility"
                        name="needs_facility" 
                        value="1" 
                        {{ old('needs_facility') ? 'checked' : '' }}
                        class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        onchange="document.getElementById('facility_section').style.display = this.checked ? 'block' : 'none';"
                    >
                    <label for="needs_facility" class="text-sm font-medium text-[#111318] dark:text-white">
                        This event requires facility booking
                    </label>
                </div>

                <!-- Facility Details (Hidden by default) -->
                <div id="facility_section" style="display: {{ old('needs_facility') ? 'block' : 'none' }};" class="space-y-5 pl-8 border-l-4 border-primary/30">
                    <!-- Facility Selection -->
                    <div>
                        <label for="facility_id" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                            Select Facility
                        </label>
                        <select 
                            id="facility_id"
                            name="facility_id" 
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >
                            <option value="">-- Choose a facility --</option>
                            @foreach($facilities as $f)
                                <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>
                                    {{ $f->name }} — {{ $f->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Facility Start -->
                        <div>
                            <label for="facility_start_at" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Facility Start Time
                            </label>
                            <input 
                                type="datetime-local" 
                                id="facility_start_at"
                                name="facility_start_at" 
                                value="{{ old('facility_start_at') }}" 
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                            >
                        </div>

                        <!-- Facility End -->
                        <div>
                            <label for="facility_end_at" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Facility End Time
                            </label>
                            <input 
                                type="datetime-local" 
                                id="facility_end_at"
                                name="facility_end_at" 
                                value="{{ old('facility_end_at') }}" 
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ $society ? route('society.show', $society->societyID) . '#events' : route('events.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-[#111318] dark:text-white font-bold py-4 px-6 rounded-xl text-center transition-all">
                Cancel
            </a>
            <button type="submit" class="flex-1 bg-primary hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">add_circle</span>
                Create Event
            </button>
        </div>
    </form>
</div>

<script>
let ticketCounter = 0;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    addTicket();
    
    // Wire up Add Ticket button
    const addTicketBtn = document.getElementById('addTicketBtn');
    if (addTicketBtn) {
        addTicketBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addTicket();
        });
    }
    
    // Wire up form submission validation
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', validateForm);
    }
});

function addTicket() {
    const container = document.getElementById('ticketsContainer');
    const ticketId = ticketCounter++;
    
    const ticketHtml = `
        <div class="ticket-item border-2 border-gray-200 dark:border-gray-700 rounded-lg p-6 bg-gray-50 dark:bg-gray-800/50" data-ticket-id="${ticketId}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">confirmation_number</span>
                    Ticket #${ticketId + 1}
                </h3>
                <button 
                    type="button" 
                    onclick="removeTicket(${ticketId})"
                    class="text-red-500 hover:text-red-700 transition-colors"
                    ${container.children.length === 0 ? 'disabled' : ''}
                >
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Ticket Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="tickets[${ticketId}][ticket_name]" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="e.g., General Admission, Early Bird, VIP"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Price (RM) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="tickets[${ticketId}][price]" 
                        step="0.01"
                        min="0"
                        value="0.00"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="0.00"
                        required
                    >
                    <p class="text-xs text-[#616f89] dark:text-gray-400 mt-1">Set to 0 for free tickets</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Total Quantity <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="tickets[${ticketId}][total_quantity]" 
                        min="1"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="100"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Sales Start Date
                    </label>
                    <input 
                        type="datetime-local" 
                        name="tickets[${ticketId}][sales_start_at]" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                        Sales End Date
                    </label>
                    <input 
                        type="datetime-local" 
                        name="tickets[${ticketId}][sales_end_at]" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                    >
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', ticketHtml);
}

function removeTicket(ticketId) {
    const container = document.getElementById('ticketsContainer');
    
    // Prevent removing the last ticket
    if (container.children.length <= 1) {
        alert('Every event must have at least one ticket.');
        return;
    }
    
    const ticketItem = document.querySelector(`[data-ticket-id="${ticketId}"]`);
    if (ticketItem) {
        ticketItem.remove();
    }
}

function validateForm(e) {
    const ticketsContainer = document.getElementById('ticketsContainer');
    const ticketItems = ticketsContainer.querySelectorAll('.ticket-item');
    
    if (ticketItems.length === 0) {
        e.preventDefault();
        alert('You must add at least one ticket before creating an event. Please add a ticket to continue.');
        return false;
    }
    
    // Validate each ticket has required fields filled
    let hasValidTicket = false;
    ticketItems.forEach(item => {
        const nameInput = item.querySelector('input[name*="[ticket_name]"]');
        const priceInput = item.querySelector('input[name*="[price]"]');
        const quantityInput = item.querySelector('input[name*="[total_quantity]"]');
        
        if (nameInput?.value && priceInput?.value !== '' && quantityInput?.value) {
            hasValidTicket = true;
        }
    });
    
    if (!hasValidTicket) {
        e.preventDefault();
        alert('Please fill in all required ticket information (Name, Price, and Quantity).');
        return false;
    }
    
    return true;
}
</script>
@endsection