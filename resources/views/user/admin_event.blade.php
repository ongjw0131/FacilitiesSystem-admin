<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Event Oversight - UniEvents Manager</title>
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Event Management</h2>
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
            <div class="w-full max-w-7xl mx-auto px-4 md:px-10 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 dark:bg-primary/20 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-primary text-4xl">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-[#111318] dark:text-white">Event Management</h1>
                    <p class="text-[#616f89] dark:text-gray-400 mt-1">Manage all events in the system</p>
                </div>
            </div>

            {{-- SECURITY: Route helper generates safe URL --}}
            <a href="{{ route('events.admin.create') }}" class="flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg">
                <span class="material-symbols-outlined">add_circle</span>
                Create Event
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 rounded-lg p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
            <div class="flex-1">
                <p class="font-semibold text-green-800 dark:text-green-200">Success!</p>
                {{-- SECURITY: Session messages are safe - from Laravel flash --}}
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                {{-- SECURITY: Route helper with parameters - safe --}}
                <a href="{{ route('events.admin.index') }}"
                    class="px-4 py-2 rounded-lg font-semibold transition-all {{ !request('filter') ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">list</span>
                        {{-- SECURITY: Numeric count - safe --}}
                        All Events ({{ $events->count() }})
                    </span>
                </a>

                <a href="{{ route('events.admin.index', ['filter' => 'active']) }}"
                    class="px-4 py-2 rounded-lg font-semibold transition-all {{ request('filter') === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Active ({{ $events->where('is_deleted', 0)->count() }})
                    </span>
                </a>

                <a href="{{ route('events.admin.index', ['filter' => 'deleted']) }}"
                    class="px-4 py-2 rounded-lg font-semibold transition-all {{ request('filter') === 'deleted' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-[#616f89] dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">delete</span>
                        Deleted ({{ $events->where('is_deleted', 1)->count() }})
                    </span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
            $activeEvents = $events->where('is_deleted', 0);
            $totalEvents = $activeEvents->count();
            $openEvents = $activeEvents->where('status', 'open')->count();
            $closedEvents = $activeEvents->where('status', 'closed')->count();
            $completedEvents = $activeEvents->where('status', 'completed')->count();
            @endphp

            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#616f89] dark:text-gray-400 font-medium">Active Events</p>
                        {{-- SECURITY: Numeric values - safe --}}
                        <p class="text-3xl font-bold text-[#111318] dark:text-white mt-1">{{ $totalEvents }}</p>
                    </div>
                    <span class="material-symbols-outlined text-blue-500 text-4xl">event_note</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#616f89] dark:text-gray-400 font-medium">Open Events</p>
                        <p class="text-3xl font-bold text-[#111318] dark:text-white mt-1">{{ $openEvents }}</p>
                    </div>
                    <span class="material-symbols-outlined text-green-500 text-4xl">event_available</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#616f89] dark:text-gray-400 font-medium">Closed Events</p>
                        <p class="text-3xl font-bold text-[#111318] dark:text-white mt-1">{{ $closedEvents }}</p>
                    </div>
                    <span class="material-symbols-outlined text-yellow-500 text-4xl">event_busy</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#616f89] dark:text-gray-400 font-medium">Completed</p>
                        <p class="text-3xl font-bold text-[#111318] dark:text-white mt-1">{{ $completedEvents }}</p>
                    </div>
                    <span class="material-symbols-outlined text-purple-500 text-4xl">task_alt</span>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-[#111318] dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">list</span>
                    @if(request('filter') === 'active')
                    Active Events
                    @elseif(request('filter') === 'deleted')
                    Deleted Events
                    @else
                    All Events
                    @endif
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">State</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Organizer</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($events as $e)
                        @php
                        $isDeleted = $e->is_deleted == 1;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors {{ $isDeleted ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative w-16 h-16 rounded-lg overflow-hidden {{ $isDeleted ? 'grayscale' : '' }}">
                                    @if($e->image_url_path)
                                    {{-- SECURITY: asset() helper is safe, alt uses proper encoding --}}
                                    <img src="{{ asset($e->image_url_path) }}" alt="@attr($e->name)" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-600">event</span>
                                    </div>
                                    @endif
                                    @if($isDeleted)
                                    <div class="absolute inset-0 bg-black/50 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-2xl">delete</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    {{-- SECURITY: HTML context - auto-encoded with {{ }} --}}
                                    <p class="text-sm font-semibold text-[#111318] dark:text-white truncate {{ $isDeleted ? 'line-through' : '' }}">
                                        {{ $e->name }}
                                    </p>
                                    <p class="text-xs text-[#616f89] dark:text-gray-400 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-xs">place</span>
                                        {{-- SECURITY: HTML context - auto-encoded --}}
                                        {{ $e->location ?: 'No location' }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- SECURITY: Dates formatted by Carbon - safe --}}
                                <p class="text-sm text-[#111318] dark:text-white">{{ $e->start_date ? \Carbon\Carbon::parse($e->start_date)->format('M d, Y') : 'TBD' }}</p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">{{ $e->start_date ? \Carbon\Carbon::parse($e->start_date)->format('h:i A') : '' }}</p>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($e->status === 'open') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($e->status === 'incoming') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($e->status === 'closed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($e->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($e->status === 'completed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endif">
                                    {{-- SECURITY: HTML context - auto-encoded --}}
                                    {{ ucfirst($e->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isDeleted)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 flex items-center gap-1 w-fit">
                                    <span class="material-symbols-outlined text-xs">delete</span>
                                    Deleted
                                </span>
                                @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 flex items-center gap-1 w-fit">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    Active
                                </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- SECURITY: HTML context - auto-encoded, numeric ID safe --}}
                                <p class="text-sm text-[#111318] dark:text-white">{{ optional($e->organizer)->name ?? "User #{$e->organizer_id}" }}</p>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    {{-- SECURITY: Route helpers generate safe URLs --}}
                                    <a href="{{ route('events.admin.show', $e) }}" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>

                                    @if(!$isDeleted)
                                    <a href="{{ route('events.edit', $e) }}" class="p-2 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>

                                    {{-- SECURITY: NO inline onsubmit JavaScript! Use event listener instead --}}
                                    <form action="{{ route('events.destroy', $e) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('events.admin.restore', $e) }}" method="POST" class="inline restore-form">
                                        @csrf
                                        <button type="submit" class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors" title="Restore">
                                            <span class="material-symbols-outlined text-xl">restore</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('events.admin.permanentDelete', $e) }}" method="POST" class="inline permanent-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Permanent Delete">
                                            <span class="material-symbols-outlined text-xl">delete_forever</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 mb-4" style="font-size: 64px;">event_busy</span>
                                    <p class="text-lg font-semibold text-[#111318] dark:text-white mb-2">No Events Found</p>
                                    <p class="text-sm text-[#616f89] dark:text-gray-400">
                                        @if(request('filter') === 'deleted')
                                        No deleted events to display
                                        @elseif(request('filter') === 'active')
                                        No active events to display
                                        @else
                                        Create your first event to get started
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </main>
    </div>

</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this event? This will mark it as deleted.')) {
                e.preventDefault();
            }
        });
    });
    
    // Restore confirmation
    document.querySelectorAll('.restore-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to restore this event?')) {
                e.preventDefault();
            }
        });
    });
    
    // Permanent delete confirmation
    document.querySelectorAll('.permanent-delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('⚠️ PERMANENT DELETE! This action cannot be undone. Are you absolutely sure?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
</html>