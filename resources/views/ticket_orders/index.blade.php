@php
    $user = auth()->user();
@endphp

@if($isAdmin)
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Ticket Orders</h2>
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
    @section('title', 'Ticket Orders ')
@endif


<main class="flex-1 flex flex-col items-center bg-[#f8fafc]">
    <section class="w-full max-w-[1280px] px-4 md:px-10 py-10">
        <style>
            nav[role="navigation"] p {
                display: none;
            }
        </style>
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#111318]">
                Ticket Orders
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

        <!-- Filter -->
        <form method="GET" class="mb-6">
            <div class="flex flex-col md:flex-row gap-4">

                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search user or ticket name"
                    class="w-full md:w-1/3 rounded-lg border px-4 py-2">

                <!-- Status -->
                <select
                    name="status"
                    class="w-full md:w-48 rounded-lg border px-4 py-2">
                    <option value="">All Status</option>
                    @foreach (['pending','paid','cancelled','expired'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>
                        {{ ucfirst($s) }}
                    </option>
                    @endforeach
                </select>

                <!-- Submit -->
                <button
                    type="submit"
                    class="h-10 px-6 rounded-lg bg-primary text-white font-bold hover:bg-blue-700 transition">
                    Filter
                </button>

                <!-- Reset -->
                <a
                    href="{{ route('ticket-orders.index', $event->id) }}"
                    class="h-10 px-6 rounded-lg border border-[#dbdfe6] flex items-center justify-center font-bold">
                    Reset
                </a>

            </div>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow border border-[#dbdfe6] overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-[#f8fafc] border-b">
                    <tr class="text-left text-[#616f89] font-bold">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Ticket</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                    <tr class="border-b hover:bg-[#f9fafb]">

                        <td class="px-6 py-4">
                            {{ $order->user->name ?? 'Unknown' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $order->ticket->ticket_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $order->quantity }}
                        </td>

                        <td class="px-6 py-4 font-bold">
                            RM {{ number_format($order->total_amount, 2) }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                            $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'paid' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            'expired' => 'bg-gray-200 text-gray-600',
                            ];
                            @endphp

                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusColors[$order->status] ?? 'bg-gray-100' }}">
                                {{ strtoupper($order->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('ticket-orders.edit', $order->id) }}"
                                class="p-2 rounded-lg text-primary hover:bg-blue-100 transition">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-[#616f89]">
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination + Showing -->
            @if ($orders->hasPages())
            <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- 左：空位（对齐用） -->
                <div class="md:w-1/3"></div>

                <!-- 中：Pagination -->
                <div class="md:w-1/3 flex justify-center">
                    {{ $orders->links() }}
                </div>

                <!-- 右：Showing -->
                <div class="md:w-1/3 text-sm text-[#616f89] md:text-right text-center">
                    Showing {{ $orders->firstItem() }}
                    to {{ $orders->lastItem() }}
                    of {{ $orders->total() }} results
                </div>

            </div>
            @endif


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

            if (data?.event?.name) {
                el.textContent = data.event.name;
            } else {
                el.textContent = 'Unknown Event';
            }
        } catch (err) {
            console.error('Failed to load event name:', err);
            el.textContent = 'Failed to load event';
        }
    });
</script>

@if($isAdmin)
        </div> {{-- end main content --}}
    </body>
    </html>
@else
    @include('foot')
@endif