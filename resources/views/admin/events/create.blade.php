<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Create Event - UniEvents Manager</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;900&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#1d4ed8",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2230",
                        "border-light": "#e5e7eb",
                        "border-dark": "#2d3748",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Lexend', 'Noto Sans', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white h-screen flex overflow-hidden">
    <aside class="flex w-72 flex-col border-r border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark transition-colors duration-300">
        <div class="flex items-center gap-3 px-6 py-6">
            <div class="bg-center bg-no-repeat bg-cover rounded-lg size-10 shadow-sm" data-alt="University crest logo abstract blue and white" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCMkVeislnnaakM2YXnluxWNB7NhdgYy47tXoczBKrfbvHXgBjhLPSi1A53AZr0JWkj9Af_tPh41QHFg1BgZgZR9MDsdnRFF9gGvuqUTj5zP9wMNlFCCVRz79dMLK8HYCEXDraZ5k4XwhT9oQ_DJCb9QlpYUGvI8SDXFWRv6nR_11pT0dcX6OXZ4v97qVKhb5ytp3Cw63oSk1vgONPU-StsR0abvPUs1cn7Um0fR5RSXsGAAIK0rJCMKROlDGngG1mAaTulSdtYC65Y");'></div>
            <div class="flex flex-col">
                <h1 class="text-slate-900 dark:text-white text-base font-bold leading-none">UniEvents</h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-normal mt-1">Admin Console v2.4</p>
            </div>
        </div>
        <div class="flex flex-col flex-1 gap-2 px-4 py-4 overflow-y-auto">
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <p class="text-sm font-medium leading-normal">Dashboard</p>
            </a>
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_user') }}">
                <span class="material-symbols-outlined filled">group</span>
                <p class="text-sm font-medium leading-normal">User Management</p>
            </a>
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_society') }}">
                <span class="material-symbols-outlined">diversity_3</span>
                <p class="text-sm font-medium leading-normal">Society Management</p>
            </a>
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer transition-all" href="{{ route('user.admin_event') }}">
                <span class="material-symbols-outlined">event_note</span>
                <p class="text-sm font-medium leading-normal">Event Oversight</p>
            </a>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg h-10 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="truncate">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col h-full min-w-0">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4 z-10">
            <div class="flex items-center gap-4">
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Create Event</h2>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border border-white dark:border-surface-dark"></span>
                </button>
                <div class="flex items-center gap-3 pl-2 border-l border-slate-200 dark:border-slate-700">
                    <div class="text-right hidden lg:block">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">System Administrator</p>
                    </div>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-white dark:border-slate-700 shadow-sm" data-alt="Profile picture of a person looking professional" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1WT5ZuTEcKB1jtvKBFRKkz1uw214PYkNuPpjCQ9kjirfhpiJyGwi7UJH4qpcG-j-GPVCJk6eB9rUxYpfe0EupgcwX9lYvGUWnw7r5w7pbmMTNkyNXuQ6HdvGL4bXtGJV7Tzl9basBSqIdH1c8F-BzDzwXlsladA7kQteelhc6ztPlzPP3ZPeqZKso0a3oZ33BytAU20DZv8gQTlYf3ZBuRiEegXrCNbWiuKUrXkvYJeIuBJA-tJ8pZyZyqgL8jhNKmPSkosy1_j4u");'></div>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark scroll-smooth">
<div class="w-full max-w-5xl mx-auto px-4 md:px-10 py-8">
    <!-- Header -->
    <div class="mb-8">
        <button onclick="history.back()" class="inline-flex items-center text-primary hover:text-blue-700 mb-4 transition-colors">
            <span class="material-symbols-outlined mr-2">arrow_back</span>
            <span class="font-medium">Back</span>
        </button>
        
        <div class="flex items-center gap-4">
            <div class="bg-primary/10 dark:bg-primary/20 p-3 rounded-xl">
                <span class="material-symbols-outlined text-primary text-4xl">add_circle</span>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-[#111318] dark:text-white">Create New Event</h1>
                <p class="text-[#616f89] dark:text-gray-400 mt-1">Admin: Create and configure a new event</p>
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
    <form action="{{ route('events.admin.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Right Sidebar (Image & Meta) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Event Image -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">image</span>
                        Event Image
                    </h3>
                    
                    <div class="mb-4">
                        <div class="w-full h-48 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-3" id="imagePreview">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600" style="font-size: 64px;">event</span>
                        </div>
                        
                        <input 
                            type="file" 
                            id="image"
                            name="image" 
                            accept="image/*" 
                            class="w-full px-4 py-3 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-[#111318] dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700 transition-all"
                            onchange="previewImage(event)"
                        >
                        <p class="text-xs text-[#616f89] dark:text-gray-400 mt-2">Max 4MB • JPG, PNG, WEBP</p>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Meta Information
                    </h3>
                    
                    <div class="text-sm space-y-2">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-[#616f89] dark:text-gray-400">Organizer</span>
                            <span class="font-semibold text-[#111318] dark:text-white">{{ optional(auth()->user())->name ?? 'System' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-[#616f89] dark:text-gray-400">Created By</span>
                            <span class="font-semibold text-[#111318] dark:text-white">Admin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (Form Fields) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
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
                                rows="6"
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
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        Date & Time
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        Event Settings
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                                <option value="incoming" selected>Incoming</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Event Tickets Section (Always visible, mandatory) -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
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

                <!-- Facility Booking -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">meeting_room</span>
                        Facility Booking (Optional)
                    </h2>

                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                id="needs_facility"
                                name="needs_facility" 
                                value="1" 
                                class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary"
                                onchange="toggleFacilitySection(this.checked)"
                            >
                            <label for="needs_facility" class="text-sm font-medium text-[#111318] dark:text-white">
                                This event requires facility booking
                            </label>
                        </div>

                        <div id="facility_section" style="display: none;" class="space-y-5 pl-8 border-l-4 border-primary/30">
                            <div>
                                <label for="facility_id" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                    Select Facility
                                </label>
                                <select 
                                    id="facility_id"
                                    name="facility_id" 
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    onchange="checkFacilityAvailability()"
                                >
                                    <option value="">-- Choose a facility --</option>
                                    @foreach($facilities as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }} — {{ $f->location }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="facility_start_at" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                        Facility Start Time
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="facility_start_at"
                                        name="facility_start_at" 
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                        onchange="checkFacilityAvailability()"
                                    >
                                </div>

                                <div>
                                    <label for="facility_end_at" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                        Facility End Time
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="facility_end_at"
                                        name="facility_end_at" 
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                        onchange="checkFacilityAvailability()"
                                    >
                                </div>
                            </div>

                            <!-- Availability Status Display -->
                            <div id="availabilityStatus" class="hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('events.admin.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-[#111318] dark:text-white font-bold py-4 px-6 rounded-xl text-center transition-all">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        Create Event
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let ticketCounter = 0;

// Add one default ticket on page load
document.addEventListener('DOMContentLoaded', function() {
    addTicket();
    
    // Wire up Add Ticket button
    const addTicketBtn = document.getElementById('addTicketBtn');
    if (addTicketBtn) {
        addTicketBtn.addEventListener('click', addTicket);
    }
    
    // Wire up form submission validation
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', validateForm);
    }
});

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-48 object-cover rounded-lg">`;
        }
        reader.readAsDataURL(file);
    }
}

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

function toggleFacilitySection(show) {
    const section = document.getElementById('facility_section');
    section.style.display = show ? 'block' : 'none';
    
    if (!show) {
        document.getElementById('availabilityStatus').classList.add('hidden');
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
    
    const needsFacility = document.getElementById('needs_facility').checked;
    const statusDiv = document.getElementById('availabilityStatus');
    
    if (needsFacility && statusDiv.innerHTML.includes('Facility Not Available')) {
        const confirmed = confirm('The selected facility is not available for this time slot. Do you still want to create this event? The facility booking will be pending approval.');
        if (!confirmed) {
            e.preventDefault();
        }
    }
}

let availabilityCheckTimeout = null;

function checkFacilityAvailability() {
    // Clear previous timeout
    if (availabilityCheckTimeout) {
        clearTimeout(availabilityCheckTimeout);
    }

    // Debounce API calls
    availabilityCheckTimeout = setTimeout(async () => {
        const facilityId = document.getElementById('facility_id').value;
        const startAt = document.getElementById('facility_start_at').value;
        const endAt = document.getElementById('facility_end_at').value;
        const statusDiv = document.getElementById('availabilityStatus');

        // Validate inputs
        if (!facilityId || !startAt || !endAt) {
            statusDiv.classList.add('hidden');
            return;
        }

        // Show loading state
        statusDiv.classList.remove('hidden');
        statusDiv.innerHTML = `
            <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 rounded-lg p-4 flex items-center gap-3">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                <span class="text-blue-700 dark:text-blue-300 font-medium">Checking availability...</span>
            </div>
        `;

        try {
            // Laravel API routes are automatically prefixed with /api/
            const response = await fetch('/api/facilities/availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin', // Include cookies for session-based auth
                body: JSON.stringify({
                    facilityId: parseInt(facilityId),
                    startAt: startAt,
                    endAt: endAt
                })
            });

            const data = await response.json();

            if (data.status === 'S') {
                // Success response
                if (data.isAvailable) {
                    statusDiv.innerHTML = `
                        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <div>
                                    <p class="font-semibold text-green-800 dark:text-green-200">Facility Available</p>
                                    <p class="text-sm text-green-700 dark:text-green-300">This facility is available for the selected time slot.</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Show conflicts
                    let conflictsHtml = data.conflicts.map(conflict => `
                        <li class="text-sm">
                            Event #${conflict.event_id}: 
                            ${new Date(conflict.start_at).toLocaleString()} - ${new Date(conflict.end_at).toLocaleString()}
                            <span class="ml-2 px-2 py-0.5 bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 text-xs rounded">${conflict.status}</span>
                        </li>
                    `).join('');

                    statusDiv.innerHTML = `
                        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-red-500">cancel</span>
                                <div class="flex-1">
                                    <p class="font-semibold text-red-800 dark:text-red-200 mb-2">Facility Not Available</p>
                                    <p class="text-sm text-red-700 dark:text-red-300 mb-3">There are ${data.conflicts.length} conflicting booking(s):</p>
                                    <ul class="space-y-1 text-red-700 dark:text-red-300">
                                        ${conflictsHtml}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;

                    // Optionally disable submit button
                    // document.getElementById('submitBtn').disabled = true;
                }
            } else {
                // Error response
                statusDiv.innerHTML = `
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-500 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-yellow-500">warning</span>
                            <div>
                                <p class="font-semibold text-yellow-800 dark:text-yellow-200">Error</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">${data.message || 'Could not check availability'}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Availability check error:', error);
            statusDiv.innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-500">error</span>
                        <div>
                            <p class="font-semibold text-red-800 dark:text-red-200">Connection Error</p>
                            <p class="text-sm text-red-700 dark:text-red-300">Failed to check facility availability. Please try again.</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }, 500); // Wait 500ms after user stops typing
}

// Optional: Prevent form submission if facility is not available
document.getElementById('eventForm')?.addEventListener('submit', function(e) {
    const needsFacility = document.getElementById('needs_facility').checked;
    const statusDiv = document.getElementById('availabilityStatus');
    
    if (needsFacility && statusDiv.innerHTML.includes('Facility Not Available')) {
        const confirmed = confirm('The selected facility is not available for this time slot. Do you still want to create this event? The facility booking will be pending approval.');
        if (!confirmed) {
            e.preventDefault();
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('imagePreview').innerHTML = 
                        `<img src="${event.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:0.5rem;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('imagePreview').innerHTML = 
                        `<img src="${event.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:0.5rem;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
        </div>
        </main>
    </div>

</body>
</html>