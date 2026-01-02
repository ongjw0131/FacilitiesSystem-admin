<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Edit Event - UniEvents Manager</title>
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Edit Event</h2>
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
        
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-500/10 dark:bg-yellow-500/20 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-yellow-600 text-4xl">edit</span>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-[#111318] dark:text-white">Edit Event</h1>
                    <p class="text-[#616f89] dark:text-gray-400 mt-1">{{ $event->name }}</p>
                </div>
            </div>

            <a href="{{ route('events.admin.show', $event) }}" class="flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition-all shadow-lg">
                <span class="material-symbols-outlined">visibility</span>
                View Event
            </a>
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
    <form action="{{ route('events.admin.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Right Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Event Image -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">image</span>
                        Event Image
                    </h3>
                    
                    <div class="mb-4">
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

                        <div class="w-full h-48 mb-3 rounded-lg overflow-hidden" id="imagePreview">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" class="w-full h-48 object-cover" alt="{{ $event->name }}">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600" style="font-size: 64px;">event</span>
                                </div>
                            @endif
                        </div>
                        
                        <input 
                            type="file" 
                            id="image"
                            name="image" 
                            accept="image/*" 
                            class="w-full px-4 py-3 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-[#111318] dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700 transition-all"
                            onchange="previewImage(event)"
                        >
                        <p class="text-xs text-[#616f89] dark:text-gray-400 mt-2">Max 2MB • JPG, PNG, WEBP</p>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Quick Info
                    </h3>
                    
                    <div class="text-sm space-y-2">
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-[#616f89] dark:text-gray-400">Event ID</span>
                            <span class="font-semibold text-[#111318] dark:text-white">#{{ $event->id }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-[#616f89] dark:text-gray-400">Organizer</span>
                            <span class="font-semibold text-[#111318] dark:text-white">{{ optional($event->organizer)->name ?? "User #{$event->organizer_id}" }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-[#616f89] dark:text-gray-400">Created</span>
                            <span class="font-semibold text-[#111318] dark:text-white">{{ $event->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">warning</span>
                        Danger Zone
                    </h3>
                    
                    <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                        Deleting this event is permanent and cannot be undone.
                    </p>

                    <button 
                        type="button"
                        onclick="document.getElementById('deleteForm').submit();"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">delete_forever</span>
                        Delete Event
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-[#1a202c] rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Basic Information
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Event Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', $event->name) }}" 
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                                required
                            >
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Description
                            </label>
                            <textarea 
                                id="description"
                                name="description" 
                                rows="6"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                            >{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Location
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-10 -translate-y-1/2 text-[#616f89]">place</span>
                                <input 
                                    type="text" 
                                    id="location"
                                    name="location" 
                                    value="{{ old('location', $event->location) }}" 
                                    class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
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
                                value="{{ old('start_date', optional($event->start_date)->format('Y-m-d\TH:i') ?? '') }}" 
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
                                value="{{ old('end_date', optional($event->end_date)->format('Y-m-d\TH:i') ?? '') }}" 
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
                                Capacity
                            </label>
                            <input 
                                type="number" 
                                id="capacity"
                                name="capacity" 
                                min="0"
                                value="{{ old('capacity', $event->capacity) }}" 
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-[#111318] dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
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
                                <option value="incoming" {{ old('status', $event->status)=='incoming' ? 'selected' : '' }}>Incoming</option>
                                <option value="open" {{ old('status', $event->status)=='open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status', $event->status)=='closed' ? 'selected' : '' }}>Closed</option>
                                <option value="cancelled" {{ old('status', $event->status)=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ old('status', $event->status)=='completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('events.admin.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-[#111318] dark:text-white font-bold py-4 px-6 rounded-xl text-center transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" action="{{ route('events.destroy', $event) }}" method="POST" class="hidden" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const safeResult = e.target.result.replace(/[<>]/g, '');
            preview.innerHTML = '<img src="' + safeResult + '" class="w-full h-48 object-cover rounded-lg" alt="Preview">';
        }
        reader.readAsDataURL(file);
    }
}
</script>
        </div>
        </main>
    </div>

</body>
</html>