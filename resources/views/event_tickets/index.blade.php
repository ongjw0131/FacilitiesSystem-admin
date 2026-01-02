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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Event Ticket Management</h2>
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
                        {{-- GLOBAL TOAST --}}
            @if (session('success') || session('error'))
            <div
                id="global-toast"
                class="fixed top-24 left-1/2 -translate-x-1/2 z-[9999]">
                <div
                    class="flex items-center gap-3 px-6 py-4 rounded-xl
        {{ session('success')
            ? 'bg-green-100 border border-green-300 text-green-800'
            : 'bg-red-100 border border-red-300 text-red-800' }}
        shadow-lg
        opacity-0 translate-y-2
        transition-all duration-300">

                    <span class="material-symbols-outlined text-2xl
            {{ session('success') ? 'text-green-600' : 'text-red-600' }}">
                        {{ session('success') ? 'check_circle' : 'error' }}
                    </span>

                    <p class="font-semibold text-sm">
                        {{ session('success') ?? session('error') }}
                    </p>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const toast = document.querySelector('#global-toast > div');
                    if (!toast) return;

                    // show
                    setTimeout(() => {
                        toast.classList.remove('opacity-0', 'translate-y-2');
                        toast.classList.add('opacity-100', 'translate-y-0');
                    }, 50);

                    // hide
                    setTimeout(() => {
                        toast.classList.remove('opacity-100', 'translate-y-0');
                        toast.classList.add('opacity-0', 'translate-y-2');
                    }, 2000);
                });
            </script>
            @endif
        </header>

@else
    {{-- PRESIDENT / NORMAL USER HEADER --}}
    @include('head')
    @section('title', 'Manage Tickets')
@endif

<main class="flex-1 flex flex-col items-center">
    <section class="w-full max-w-[1280px] px-4 md:px-10 py-10">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#111318]">
                    Ticket Management
                </h1>
                <p class="text-[#616f89] mt-1">
                    Event:
                    <span
                        id="event-name"
                        class="font-semibold"
                        data-event-id="{{ $event->id }}">
                        Loading...
                    </span>
                </p>


            </div>

            <a href="{{ route('event-tickets.create', $event->id) }}"
                class="flex items-center justify-center gap-2 rounded-lg h-10 px-5 bg-primary text-white font-bold hover:bg-blue-700 transition">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Create Ticket
            </a>
        </div>

        <!-- Ticket List -->
        <div class="bg-white rounded-xl shadow border border-[#dbdfe6] overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-[#f8fafc] border-b border-[#dbdfe6]">
                    <tr class="text-left text-[#616f89] font-bold">
                        <th class="px-6 py-4">Ticket Name</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Quantity</th>
                        <th class="px-6 py-4">Sales Period</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($event->tickets as $ticket)
                    <tr class="border-b last:border-0 hover:bg-[#f9fafb]">

                        <!-- Ticket Name -->
                        <td class="px-6 py-4 font-semibold text-[#111318]">
                            {{ $ticket->ticket_name }}
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4">
                            @if($ticket->price > 0)
                            RM {{ number_format($ticket->price, 2) }}
                            @else
                            <span class="text-green-600 font-bold">FREE</span>
                            @endif
                        </td>

                        <!-- Quantity -->
                        <td class="px-6 py-4">
                            {{ $ticket->sold_quantity }} / {{ $ticket->total_quantity }}
                        </td>

                        <!-- Sales Period -->
                        <td class="px-6 py-4 text-[#616f89]">
                            <div>{{ $ticket->sales_start_at->format('d M Y H:i') }}</div>
                            <div>→ {{ $ticket->sales_end_at->format('d M Y H:i') }}</div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @php
                            $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'active' => 'bg-green-100 text-green-700',
                            'paused' => 'bg-yellow-100 text-yellow-700',
                            'sold_out' => 'bg-red-100 text-red-700',
                            'expired' => 'bg-gray-200 text-gray-600',
                            ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusColors[$ticket->status] ?? 'bg-gray-100' }}">
                                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">

                                <!-- Activate / Pause (expired is allowed) -->
                                <form method="POST"
                                    action="{{ route('event-tickets.update-status', $ticket->id) }}"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status"
                                        value="{{ $ticket->status === 'active' ? 'paused' : 'active' }}">

                                    <button
                                        type="submit"
                                        title="{{ $ticket->status === 'active' ? 'Pause Ticket' : 'Activate Ticket' }}"
                                        class="p-2 rounded-lg
                                                   {{ $ticket->status === 'active'
                                                       ? 'text-yellow-600 hover:bg-yellow-100'
                                                       : 'text-green-600 hover:bg-green-100' }}
                                                   transition">
                                        <span class="material-symbols-outlined text-[20px]">
                                            {{ $ticket->status === 'active' ? 'pause' : 'play_arrow' }}
                                        </span>
                                    </button>
                                </form>

                                <!-- Edit (always allowed, including expired) -->
                                <a href="{{ route('event-tickets.edit', $ticket->id) }}"
                                    title="Edit Ticket"
                                    class="p-2 rounded-lg text-primary hover:bg-blue-100 transition">
                                    <span class="material-symbols-outlined text-[20px]">
                                        edit
                                    </span>
                                </a>

                                <!-- Delete (❌ sold_out not allowed) -->
                                @if ($ticket->status !== 'sold_out')
                                <form method="POST"
                                    action="{{ route('event-tickets.destroy', $ticket->id) }}"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        title="Delete Ticket"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-100 transition">
                                        <span class="material-symbols-outlined text-[20px]">
                                            delete
                                        </span>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-[#616f89]">
                            No tickets created for this event yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const el = document.getElementById('event-name');
        if (!el) return;

        const eventId = el.dataset.eventId;

        try {
            const res = await fetch(`/api/events/${eventId}`);
            const data = await res.json();

            if (data.event && data.event.name) {
                el.textContent = data.event.name;
            } else {
                el.textContent = 'Unknown Event';
            }
        } catch (e) {
            console.error(e);
            el.textContent = 'Failed to load event';
        }
    });
</script>



@if($user && $user->role === 'admin')
        </div> {{-- end main content --}}
    </body>
    </html>
@else
    @include('foot')
@endif
