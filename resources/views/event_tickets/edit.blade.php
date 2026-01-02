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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Edit Ticket</h2>
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
        @section('title', 'Edit Event Tickets')
        @endif

        <main class="flex-1 flex flex-col items-center">
            <section class="w-full max-w-[720px] px-4 md:px-10 py-10">

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#111318] mb-2">
                        Edit Ticket – {{ $ticket->ticket_name }}
                    </h1>
                    <p class="text-[#616f89]">
                        Update ticket details, price, quantity or sales period.
                    </p>
                </div>
                @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3">
                    <ul class="list-disc list-inside text-sm text-red-700 font-semibold">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="bg-white rounded-xl shadow p-8">
                    <form method="POST" action="{{ route('event-tickets.update', $ticket->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Ticket Name -->
                        <div>
                            <label class="block text-sm font-bold mb-1">Ticket Name</label>
                            <input
                                type="text"
                                name="ticket_name"
                                value="{{ old('ticket_name', $ticket->ticket_name) }}"
                                class="w-full rounded-lg border px-4 py-2"
                                required>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-bold mb-1">Price (RM)</label>
                            <input
                                type="number"
                                step="0.01"
                                name="price"
                                value="{{ old('price', $ticket->price) }}"
                                class="w-full rounded-lg border px-4 py-2"
                                required>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-bold mb-1">Total Quantity</label>
                            <input
                                type="number"
                                name="total_quantity"
                                value="{{ old('total_quantity', $ticket->total_quantity) }}"
                                class="w-full rounded-lg border px-4 py-2"
                                required>
                        </div>

                        <!-- Sales Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-1">Sales Start</label>
                                <input
                                    type="datetime-local"
                                    name="sales_start_at"
                                    value="{{ $ticket->sales_start_at->format('Y-m-d\TH:i') }}"
                                    class="w-full rounded-lg border px-4 py-2"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold mb-1">Sales End</label>
                                <input
                                    type="datetime-local"
                                    name="sales_end_at"
                                    value="{{ $ticket->sales_end_at->format('Y-m-d\TH:i') }}"
                                    class="w-full rounded-lg border px-4 py-2"
                                    required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border px-4 py-2">
                                @foreach (['draft', 'active', 'paused', 'sold_out', 'expired'] as $status)
                                <option value="{{ $status }}" @selected($ticket->status === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            class="w-full h-11 rounded-lg bg-primary text-white font-bold hover:bg-blue-700 transition">
                            Update Ticket
                        </button>
                    </form>
                </div>

            </section>
        </main>


        @if($user && $user->role === 'admin')
    </div> {{-- end main content --}}
</body>

</html>
@else
@include('foot')
@endif