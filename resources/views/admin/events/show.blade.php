@php
    $user = auth()->user();
@endphp

@if($user && $user->role === 'admin')
    {{-- ADMIN HEADER --}}
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Event Details - UniEvents Manager</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">{{ $event->name }}</h2>
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

@else
    {{-- PRESIDENT / NORMAL USER HEADER --}}
    @include('head')
    @section('title', 'Manage Tickets')
@endif
        <main class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark scroll-smooth">
<div class="w-full max-w-7xl mx-auto px-4 md:px-10 py-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <button onclick="history.back()" class="inline-flex items-center text-primary hover:text-blue-700 mb-2 transition-colors">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                <span class="font-medium">Back</span>
            </button>
            <h1 class="text-4xl font-bold text-[#111318] dark:text-white">{{ $event->name }}</h1>
            <p class="text-[#616f89] dark:text-gray-400 mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">place</span>
                {{ $event->location ?? 'No location specified' }}
                @if($event->start_date)
                <span class="mx-2">•</span>
                <span class="material-symbols-outlined text-base">calendar_month</span>
                {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                @endif
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Image & Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Image -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg overflow-hidden">
                @php
                $imgUrl = null;
                if (!empty($event->image_url_path)) {
                $candidate = $event->image_url_path;
                if (strpos($candidate, 'http://') === 0 || strpos($candidate, 'https://') === 0 || strpos($candidate, '/') === 0) {
                $url = $candidate;
                } else {
                $url = asset($candidate);
                }
                $path = parse_url($url, PHP_URL_PATH) ?: '';
                $localPath = public_path(ltrim($path, '/'));
                if ($path && file_exists($localPath)) {
                $imgUrl = $url;
                } else {
                $basename = basename($candidate);
                if (file_exists(public_path('events/'.$basename))) {
                $imgUrl = asset('events/'.$basename);
                } elseif (file_exists(public_path('storage/events/'.$basename))) {
                $imgUrl = asset('storage/events/'.$basename);
                } elseif (file_exists(storage_path('app/public/events/'.$basename))) {
                $imgUrl = asset('storage/events/'.$basename);
                }
                }
                }
                @endphp

                @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $event->name }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600" style="font-size: 120px;">event</span>
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">description</span>
                    Description
                </h2>
                <p class="text-[#616f89] dark:text-gray-300 leading-relaxed whitespace-pre-line">
                    {{ $event->description ?? 'No description provided for this event.' }}
                </p>
            </div>

            <!-- Event Tickets -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">confirmation_number</span>
                        Event Tickets
                    </h2>
                    <a href="{{ route('event-tickets.index', $event) }}" class="text-primary hover:text-blue-700 font-semibold text-sm flex items-center gap-1">
                        <span>Manage Tickets</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>

                @if(isset($tickets) && $tickets->count() > 0)
                <div class="space-y-3">
                    @foreach($tickets as $ticket)
                    <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-primary/50 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-[#111318] dark:text-white">{{ $ticket->ticket_name }}</h3>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($ticket->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($ticket->status === 'sold_out') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 text-sm text-[#616f89] dark:text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">payments</span>
                                        <span class="font-semibold text-[#111318] dark:text-white">
                                            @if($ticket->price > 0)
                                            RM {{ number_format($ticket->price, 2) }}
                                            @else
                                            Free
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">inventory_2</span>
                                        <span>{{ $ticket->sold_quantity }} / {{ $ticket->total_quantity }} sold</span>
                                    </div>
                                </div>

                                @if($ticket->sales_start_at || $ticket->sales_end_at)
                                <div class="mt-2 text-xs text-[#616f89] dark:text-gray-400">
                                    @if($ticket->sales_start_at)
                                    <span>Sales start: {{ \Carbon\Carbon::parse($ticket->sales_start_at)->format('M d, Y H:i') }}</span>
                                    @endif
                                    @if($ticket->sales_end_at)
                                    <span class="ml-2">• End: {{ \Carbon\Carbon::parse($ticket->sales_end_at)->format('M d, Y H:i') }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div class="ml-4">
                                <!-- Progress Circle -->
                                @php
                                $percentage = $ticket->total_quantity > 0 ? ($ticket->sold_quantity / $ticket->total_quantity) * 100 : 0;
                                @endphp
                                <div class="relative w-16 h-16">
                                    <svg class="transform -rotate-90 w-16 h-16">
                                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" class="text-gray-200 dark:text-gray-700" />
                                        <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent"
                                            class="text-primary"
                                            stroke-dasharray="{{ 2 * 3.14159 * 28 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - $percentage / 100) }}"
                                            stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-xs font-bold text-[#111318] dark:text-white">{{ round($percentage) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Ticket Summary -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">{{ $tickets->count() }}</p>
                            <p class="text-xs text-[#616f89] dark:text-gray-400">Ticket Types</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">{{ $tickets->sum('sold_quantity') }}</p>
                            <p class="text-xs text-[#616f89] dark:text-gray-400">Tickets Sold</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-[#111318] dark:text-white">{{ $tickets->sum('total_quantity') }}</p>
                            <p class="text-xs text-[#616f89] dark:text-gray-400">Total Available</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 mb-2" style="font-size: 48px;">confirmation_number</span>
                    <p class="text-sm text-[#616f89] dark:text-gray-400 mb-3">No tickets created for this event</p>
                    <a href="{{ route('event-tickets.index', $event) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                        <span class="material-symbols-outlined">add</span>
                        Create Tickets
                    </a>
                </div>
                @endif
            </div>

            <!-- Facility Bookings -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">meeting_room</span>
                    Facility Bookings
                </h2>

                @if(!empty($bookings) && $bookings->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-[#616f89] dark:text-gray-400">ID</th>
                                <th class="px-4 py-3 text-left font-semibold text-[#616f89] dark:text-gray-400">Facility</th>
                                <th class="px-4 py-3 text-left font-semibold text-[#616f89] dark:text-gray-400">Start</th>
                                <th class="px-4 py-3 text-left font-semibold text-[#616f89] dark:text-gray-400">End</th>
                                <th class="px-4 py-3 text-left font-semibold text-[#616f89] dark:text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($bookings as $b)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-[#111318] dark:text-white">#{{ $b->id }}</td>
                                <td class="px-4 py-3 text-[#111318] dark:text-white">Facility {{ $b->facility_id }}</td>
                                <td class="px-4 py-3 text-[#616f89] dark:text-gray-400">{{ $b->start_at }}</td>
                                <td class="px-4 py-3 text-[#616f89] dark:text-gray-400">{{ $b->end_at }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($b->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($b->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @endif">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 mb-2" style="font-size: 48px;">meeting_room</span>
                    <p class="text-sm text-[#616f89] dark:text-gray-400">No facility bookings for this event</p>
                </div>
                @endif
            </div>

            <!-- Attendees List (from Ticket Orders) -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">groups</span>
                    Attendees ({{ $attendeesCount ?? 0 }})
                </h2>
                    <a href="{{ route('ticket-orders.index', $event) }}" class="text-primary hover:text-blue-700 font-semibold text-sm flex items-center gap-1">
                        <span>Manage Tickets</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>

                @if(isset($ticketOrders) && $ticketOrders->count() > 0)
                <div id="attendeesList" class="space-y-2 mb-4">
                    @foreach($ticketOrders as $order)
                    <div class="attendee-item flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg {{ $loop->index >= 10 ? 'hidden' : '' }}" data-index="{{ $loop->index }}">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">person</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-[#111318] dark:text-white">{{ $order->user->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $order->user->email ?? '' }}</p>
                                @if($order->ticket)
                                <p class="text-xs text-[#616f89] dark:text-gray-400 mt-1">
                                    <span class="material-symbols-outlined text-xs align-middle">confirmation_number</span>
                                    {{ $order->ticket->ticket_name }} × {{ $order->quantity }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            @if($order->total_amount)
                            <span class="text-xs font-semibold text-[#111318] dark:text-white">
                                RM {{ number_format($order->total_amount, 2) }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($ticketOrders->count() > 10)
                <!-- Pagination Controls -->
                <div class="flex items-center justify-center gap-2 pt-2">
                    <!-- Previous Button -->
                    <button
                        id="prevBtn"
                        onclick="changePage(-1)"
                        class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </button>

                    <!-- Page Numbers -->
                    <div class="flex items-center gap-1">
                        @php
                        $totalPages = ceil($ticketOrders->count() / 10);
                        @endphp
                        @for($i = 1; $i <= min($totalPages, 5); $i++)
                            <button
                            onclick="goToPage({{ $i }})"
                            class="page-btn px-3 py-2 text-sm rounded-lg font-semibold transition-all {{ $i === 1 ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                            data-page="{{ $i }}">
                            {{ $i }}
                            </button>
                            @endfor

                            @if($totalPages > 5)
                            <span class="px-2 text-[#616f89] dark:text-gray-400">...</span>
                            <button
                                onclick="goToPage({{ $totalPages }})"
                                class="page-btn px-3 py-2 text-sm rounded-lg font-semibold transition-all bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700"
                                data-page="{{ $totalPages }}">
                                {{ $totalPages }}
                            </button>
                            @endif
                    </div>

                    <!-- Next Button -->
                    <button
                        id="nextBtn"
                        onclick="changePage(1)"
                        class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                </div>

                <!-- Page Info Text -->
                <p class="text-sm text-[#616f89] dark:text-gray-400 text-center pt-2">
                    Page <span id="currentPageText">1</span> of {{ $totalPages }}
                </p>
                @endif
                @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 mb-2" style="font-size: 48px;">group_off</span>
                    <p class="text-sm text-[#616f89] dark:text-gray-400">No attendees for this event</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Admin Actions & Metadata -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">settings</span>
                    Admin Actions
                </h3>

                <div class="space-y-3">
                    <!-- Edit Event Button -->
                    <a href="{{ route('events.edit', $event) }}" class="w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg transition-all shadow-md">
                        <span class="material-symbols-outlined">edit</span>
                        Edit Event
                    </a>

                    <!-- Delete Event Button -->
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg transition-all flex items-center justify-center gap-2 shadow-md">
                            <span class="material-symbols-outlined">delete</span>
                            Delete Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Event Metadata -->
            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    Event Details
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Event ID</span>
                        <span class="font-semibold text-[#111318] dark:text-white">#{{ $event->id }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($event->status === 'open') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($event->status === 'incoming') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @elseif($event->status === 'closed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @elseif($event->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @elseif($event->status === 'completed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                    @endif">
                            {{ strtoupper($event->status ?? 'UNKNOWN') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Capacity</span>
                        <span class="font-semibold text-[#111318] dark:text-white">{{ $event->capacity ?? 'Unlimited' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Attendees</span>
                        <span class="font-semibold text-[#111318] dark:text-white">{{ $attendeesCount ?? 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Organizer</span>
                        <span class="font-semibold text-[#111318] dark:text-white">{{ optional($event->organizer)->name ?? "User #{$event->organizer_id}" }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-[#616f89] dark:text-gray-400">Created</span>
                        <span class="font-semibold text-[#111318] dark:text-white">{{ $event->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <span class="text-[#616f89] dark:text-gray-400">Last Updated</span>
                        <span class="font-semibold text-[#111318] dark:text-white">{{ $event->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($ticketOrders) && $ticketOrders->count() > 10)
<script>
    let currentPage = 1;
    const itemsPerPage = 10;
    const totalItems = {{ $ticketOrders->count() }};
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    function changePage(offset) {
        const newPage = currentPage + offset;
        if (newPage >= 1 && newPage <= totalPages) {
            goToPage(newPage);
        }
    }

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;

        currentPage = page;

        // Hide all items
        const items = document.querySelectorAll('.attendee-item');
        items.forEach(item => item.classList.add('hidden'));

        // Show items for current page
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

        for (let i = startIndex; i < endIndex; i++) {
            const item = document.querySelector(`.attendee-item[data-index="${i}"]`);
            if (item) item.classList.remove('hidden');
        }

        // Update page buttons
        const pageButtons = document.querySelectorAll('.page-btn');
        pageButtons.forEach(btn => {
            const btnPage = parseInt(btn.dataset.page);
            if (btnPage === page) {
                btn.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'text-[#616f89]', 'dark:text-gray-400');
                btn.classList.add('bg-primary', 'text-white');
            } else {
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-gray-100', 'dark:bg-gray-800', 'text-[#616f89]', 'dark:text-gray-400');
            }
        });

        // Update buttons state
        document.getElementById('prevBtn').disabled = (page === 1);
        document.getElementById('nextBtn').disabled = (page === totalPages);

        // Update page text
        document.getElementById('currentPageText').textContent = page;

        // Smooth scroll to top of attendees list
        document.getElementById('attendeesList').scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    }
</script>
@endif
        </main>
    </div>
@if($user && $user->role === 'admin')
        </div> {{-- end main content --}}
    </body>
    </html>
@else
    @include('foot')
@endif