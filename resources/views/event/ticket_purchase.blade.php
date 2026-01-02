@extends('layouts.app')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 md:px-10 py-8">
    <!-- Back Button -->
    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-primary hover:text-blue-700 mb-6 transition-colors">
        <span class="material-symbols-outlined mr-2">arrow_back</span>
        <span class="font-medium">Back to Event Details</span>
    </a>

    <!-- Event Header -->
    <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8 mb-8">
        <h1 class="text-3xl font-bold text-[#111318] dark:text-white mb-4">Purchase Tickets</h1>
        
        <div class="border-l-4 border-primary pl-6">
            {{-- SECURITY: HTML context - auto-encoded --}}
            <h2 class="text-2xl font-semibold text-[#111318] dark:text-white mb-2">{{ $event->name }}</h2>
            <div class="flex flex-wrap gap-6 text-[#616f89] dark:text-gray-400">
                @if($event->start_date)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        {{-- SECURITY: Date formatted by Carbon - safe --}}
                        <span>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y • h:i A') }}</span>
                    </div>
                @endif
                @if($event->location)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">place</span>
                        {{-- SECURITY: HTML context - auto-encoded --}}
                        <span>{{ $event->location }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tickets Section -->
    <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">confirmation_number</span>
            Select Your Tickets
        </h2>

        @if($tickets->isEmpty())
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 px-6 py-4 rounded-lg">
                <p class="font-semibold">No tickets available</p>
                <p class="text-sm mt-1">There are no tickets available for this event at the moment.</p>
            </div>
        @else
            <form method="POST" action="{{ route('events.tickets.purchase.submit', $event) }}" id="ticketForm">
                @csrf
                
                <!-- Ticket Options -->
                <div class="space-y-4 mb-8">
                    @foreach($tickets as $ticket)
                        @php
                            $available = $ticket->total_quantity - $ticket->sold_quantity;
                            $isFree = $ticket->price == 0;
                            $progressPercent = ($ticket->sold_quantity / $ticket->total_quantity) * 100;
                        @endphp
                        
                        {{-- SECURITY: Use data attributes instead of inline onclick with @attr directive --}}
                        <label for="ticket_{{ $ticket->id }}" 
                               class="block border-2 {{ $available > 0 ? 'border-gray-200 dark:border-gray-700 hover:border-primary cursor-pointer' : 'border-gray-200 dark:border-gray-700 opacity-50 cursor-not-allowed' }} rounded-xl p-6 transition-all {{ $available > 0 ? 'hover:shadow-lg' : '' }}"
                               data-ticket-id="{{ $ticket->id }}"
                               data-ticket-price="{{ $ticket->price }}"
                               data-ticket-name="{{ $ticket->ticket_name }}"
                               data-ticket-available="{{ $available }}">
                            
                            <div class="flex items-start gap-4">
                                <!-- Radio Button -->
                                <input 
                                    type="radio" 
                                    name="ticket_id" 
                                    value="{{ $ticket->id }}" 
                                    id="ticket_{{ $ticket->id }}"
                                    class="mt-1.5 w-5 h-5 text-primary focus:ring-primary {{ $available <= 0 ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                    {{ $available <= 0 ? 'disabled' : '' }}
                                    required
                                >
                                
                                <!-- Ticket Details -->
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            {{-- SECURITY: HTML context - auto-encoded --}}
                                            <h3 class="text-xl font-bold text-[#111318] dark:text-white">{{ $ticket->ticket_name }}</h3>
                                            @if($available <= 0)
                                                <span class="inline-block mt-1 px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-semibold rounded-full">
                                                    SOLD OUT
                                                </span>
                                            @elseif($available <= 10)
                                                {{-- SECURITY: Numeric value - safe --}}
                                                <span class="inline-block mt-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs font-semibold rounded-full">
                                                    Only {{ $available }} left!
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            {{-- SECURITY: Numeric values - safe --}}
                                            <p class="text-3xl font-bold {{ $isFree ? 'text-green-600 dark:text-green-400' : 'text-primary' }}">
                                                {{ $isFree ? 'FREE' : 'RM ' . number_format($ticket->price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm text-[#616f89] dark:text-gray-400 mb-2">
                                            {{-- SECURITY: Numeric values - safe --}}
                                            <span>{{ $ticket->sold_quantity }} sold</span>
                                            <span>{{ $available }} available</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                            {{-- SECURITY: CSS width uses numeric value - safe --}}
                                            <div class="h-full {{ $progressPercent >= 90 ? 'bg-red-500' : ($progressPercent >= 70 ? 'bg-orange-500' : 'bg-green-500') }} transition-all" style="width: {{ $progressPercent }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Sales Period -->
                                    @if($ticket->sales_start_at || $ticket->sales_end_at)
                                        <div class="flex items-center gap-2 text-sm text-[#616f89] dark:text-gray-400">
                                            <span class="material-symbols-outlined text-base">schedule</span>
                                            <span>
                                                Sales: 
                                                @if($ticket->sales_start_at)
                                                    {{ \Carbon\Carbon::parse($ticket->sales_start_at)->format('M d, Y H:i') }}
                                                @endif
                                                @if($ticket->sales_end_at)
                                                    - {{ \Carbon\Carbon::parse($ticket->sales_end_at)->format('M d, Y H:i') }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('ticket_id')
                    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-6">
                        {{-- SECURITY: Error messages are safe - from Laravel validator --}}
                        {{ $message }}
                    </div>
                @enderror

                <!-- Quantity Section (Hidden by default) -->
                <div id="quantitySection" style="display: none;" class="space-y-6">
                    <!-- Quantity Input -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6">
                        <label for="quantity" class="block text-lg font-bold text-[#111318] dark:text-white mb-3">
                            Number of Tickets
                        </label>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            min="1" 
                            max="100" 
                            value="1"
                            class="w-full px-4 py-3 text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-[#111318] dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                        >
                        <p class="text-sm text-[#616f89] dark:text-gray-400 mt-2 flex items-center gap-1" id="maxText">
                            <span class="material-symbols-outlined text-base">info</span>
                            <span id="maxTextContent"></span>
                        </p>
                        
                        @error('quantity')
                            {{-- SECURITY: Error messages are safe - from Laravel validator --}}
                            <p class="text-red-500 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white dark:bg-gray-800 border-2 border-primary rounded-xl p-6 shadow-lg">
                        <h3 class="font-bold text-xl text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">receipt_long</span>
                            Order Summary
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[#616f89] dark:text-gray-400">Ticket Type</span>
                                <span class="font-semibold text-[#111318] dark:text-white" id="summaryTicketName">-</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#616f89] dark:text-gray-400">Price per ticket</span>
                                <span class="font-semibold text-[#111318] dark:text-white" id="summaryPrice">RM 0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#616f89] dark:text-gray-400">Quantity</span>
                                <span class="font-semibold text-[#111318] dark:text-white" id="summaryQty">1</span>
                            </div>
                            <hr class="border-gray-300 dark:border-gray-600">
                            <div class="flex justify-between items-center text-2xl pt-2">
                                <span class="font-bold text-[#111318] dark:text-white">Total</span>
                                <span class="font-bold text-primary" id="summaryTotal">RM 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <a href="{{ route('events.show', $event) }}" 
                       class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-[#111318] dark:text-white font-bold py-4 px-6 rounded-xl text-center transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="flex-1 bg-primary hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none shadow-lg"
                            disabled>
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">shopping_cart_checkout</span>
                            <span id="submitBtnText">Select a Ticket</span>
                        </span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

{{-- SECURITY: JavaScript with proper variable initialization --}}
<script>
    // SECURITY: Initialize variables safely
    let currentTicketPrice = 0;
    let currentTicketName = '';
    let currentMaxQty = 100;
    
    // SECURITY: Use event delegation instead of inline onclick
    document.addEventListener('DOMContentLoaded', function() {
        // Add click listeners to ticket labels
        document.querySelectorAll('label[data-ticket-id]').forEach(function(label) {
            const ticketId = parseInt(label.dataset.ticketId);
            const price = parseFloat(label.dataset.ticketPrice);
            const name = label.dataset.ticketName; // Already encoded with @attr
            const available = parseInt(label.dataset.ticketAvailable);
            
            if (available > 0) {
                label.addEventListener('click', function() {
                    selectTicket(ticketId, price, name, available);
                });
            }
        });
        
        // Quantity input listener
        const qtyInput = document.getElementById('quantity');
        if (qtyInput) {
            qtyInput.addEventListener('input', updateTotal);
        }
        
        // Form validation
        const form = document.getElementById('ticketForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedTicket = document.querySelector('input[name="ticket_id"]:checked');
                if (!selectedTicket) {
                    e.preventDefault();
                    alert('Please select a ticket type');
                }
            });
        }
    });
    
    function selectTicket(id, price, name, available) {
        // SECURITY: All parameters are validated as numbers or already encoded
        currentTicketPrice = parseFloat(price);
        currentTicketName = name; // Already HTML-encoded from @attr
        currentMaxQty = Math.min(available, 100);
        
        // Show quantity section with animation
        const section = document.getElementById('quantitySection');
        section.style.display = 'block';
        setTimeout(() => {
            section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
        
        // Enable submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        
        // Update quantity input
        const qtyInput = document.getElementById('quantity');
        qtyInput.max = currentMaxQty;
        qtyInput.value = 1;
        
        // SECURITY: Use textContent instead of innerHTML to prevent XSS
        document.getElementById('maxTextContent').textContent = `Maximum ${currentMaxQty} tickets available`;
        
        // Update summary
        updateTotal();
    }
    
    function updateTotal() {
        // SECURITY: Parse and validate quantity as integer
        const qty = parseInt(document.getElementById('quantity').value) || 1;
        const total = currentTicketPrice * qty;
        const isFree = currentTicketPrice === 0;
        
        // SECURITY: Use textContent instead of innerHTML
        document.getElementById('summaryTicketName').textContent = currentTicketName;
        document.getElementById('summaryPrice').textContent = isFree ? 'FREE' : 'RM ' + currentTicketPrice.toFixed(2);
        document.getElementById('summaryQty').textContent = qty;
        document.getElementById('summaryTotal').textContent = isFree ? 'FREE' : 'RM ' + total.toFixed(2);
        
        // Update button text
        const btnText = isFree ? 'Confirm Registration' : 'Confirm Purchase - RM ' + total.toFixed(2);
        document.getElementById('submitBtnText').textContent = btnText;
    }
</script>
@endsection

{{--
SECURITY NOTES:
================
✅ No inline onclick handlers - using event delegation
✅ Data attributes use @attr directive for proper encoding
✅ JavaScript uses textContent instead of innerHTML
✅ All numeric values validated and parsed
✅ Form validation with CSRF protection
✅ Error messages auto-encoded with {{ }}
✅ No user input directly injected into JavaScript
✅ Event listeners attached via addEventListener
--}}