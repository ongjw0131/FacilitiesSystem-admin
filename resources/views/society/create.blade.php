<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Create Society - UniEvent Admin</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        body {
            font-family: 'Lexend', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white flex h-screen overflow-hidden">
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
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary cursor-pointer transition-all" href="{{ route('user.admin_society') }}">
                <span class="material-symbols-outlined">diversity_3</span>
                <p class="text-sm font-medium leading-normal">Society Management</p>
            </a>
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_event') }}">
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
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4 z-10">
            <div class="flex items-center gap-4">
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Create Society</h2>
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
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-2xl mx-auto flex flex-col gap-6">
                <div class="flex items-center gap-2 text-sm">
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="{{ route('user.admin_society') }}">Society Management</a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="font-medium text-slate-900 dark:text-white">Create New</span>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white tracking-tight">Create New Society</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">Add a new society to the system.</p>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ url('/society') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- President Search (Autocomplete) -->
                        <div class="flex flex-col gap-2">
                            <label for="presidentSearch" class="text-sm font-semibold text-slate-900 dark:text-white">
                                <span class="material-symbols-outlined text-sm align-text-bottom">person</span> Select President
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="presidentSearch" 
                                    placeholder="Search by name or email..."
                                    class="flex w-full h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-background-dark px-4 py-2 text-sm font-normal leading-normal text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    autocomplete="off"
                                >
                                <ul id="presidentSuggestions" class="absolute top-full left-0 right-0 mt-1 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-lg shadow-lg hidden z-10 max-h-48 overflow-y-auto">
                                </ul>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Selected: <span id="presidentName" class="font-semibold text-primary">None</span></p>
                            <input type="hidden" id="presidentID" name="presidentID" value="">
                            @error('presidentID')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Society Name -->
                        <div class="flex flex-col gap-2">
                            <label for="societyName" class="text-sm font-semibold text-slate-900 dark:text-white">Society Name</label>
                            <input 
                                type="text" 
                                id="societyName" 
                                name="societyName" 
                                value="{{ old('societyName') }}"
                                class="flex h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-background-dark px-4 py-2 text-sm font-normal leading-normal text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('societyName') border-red-500 @enderror"
                                placeholder="Enter society name"
                                required
                            >
                            @error('societyName')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Join Type -->
                        <div class="flex flex-col gap-2">
                            <label for="joinType" class="text-sm font-semibold text-slate-900 dark:text-white">Join Type</label>
                            <select 
                                id="joinType" 
                                name="joinType"
                                class="flex h-10 rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-background-dark px-4 py-2 text-sm font-normal leading-normal text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('joinType') border-red-500 @enderror"
                                required
                            >
                                <option value="" class="text-slate-500">Select join type...</option>
                                <option value="open" class="text-slate-900" {{ old('joinType') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="approval" class="text-slate-900" {{ old('joinType') === 'approval' ? 'selected' : '' }}>Approval Required</option>
                                <option value="closed" class="text-slate-900" {{ old('joinType') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('joinType')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="flex flex-col gap-2">
                            <label for="societyDescription" class="text-sm font-semibold text-slate-900 dark:text-white">Description</label>
                            <textarea 
                                id="societyDescription" 
                                name="societyDescription" 
                                rows="4"
                                class="flex rounded-lg border border-border-light dark:border-border-dark bg-white dark:bg-background-dark px-4 py-2 text-sm font-normal leading-normal text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('societyDescription') border-red-500 @enderror resize-none"
                                placeholder="Enter society description..."
                                required
                            >{{ old('societyDescription') }}</textarea>
                            @error('societyDescription')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-3 justify-end pt-4 border-t border-border-light dark:border-border-dark">
                            <a href="{{ route('user.admin_society') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 text-slate-700 dark:text-slate-300 font-semibold bg-white dark:bg-surface-dark border border-border-light dark:border-border-dark hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">close</span>
                                Cancel
                            </a>
                            <button 
                                type="submit"
                                class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn"
                                disabled
                            >
                                <span class="material-symbols-outlined text-sm">add</span>
                                <span>Create Society</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        const searchInput = document.getElementById('presidentSearch');
        const suggestionsBox = document.getElementById('presidentSuggestions');
        const presidentIDInput = document.getElementById('presidentID');
        const presidentName = document.getElementById('presidentName');
        const submitBtn = document.getElementById('submitBtn');

        searchInput.addEventListener('input', async (e) => {
            const query = e.target.value.trim();
            
            if (query.length < 1) {
                suggestionsBox.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/api/search-users?q=${encodeURIComponent(query)}`);
                const users = await response.json();
                
                suggestionsBox.innerHTML = '';
                if (users.length === 0) {
                    suggestionsBox.innerHTML = '<li class="px-4 py-2 text-sm text-slate-500 dark:text-slate-400">No users found</li>';
                } else {
                    users.forEach(user => {
                        const li = document.createElement('li');
                        li.className = 'px-4 py-2 text-sm cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white transition-colors';
                        li.innerHTML = `<strong>${user.name}</strong><br><span class="text-xs text-slate-500 dark:text-slate-400">${user.email}</span>`;
                        li.dataset.userId = user.id;
                        li.dataset.userName = user.name;
                        
                        li.addEventListener('click', () => {
                            presidentIDInput.value = user.id;
                            presidentName.textContent = user.name;
                            searchInput.value = '';
                            suggestionsBox.classList.add('hidden');
                            submitBtn.disabled = false;
                        });
                        
                        suggestionsBox.appendChild(li);
                    });
                }
                suggestionsBox.classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching users:', error);
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target !== searchInput && e.target !== suggestionsBox) {
                suggestionsBox.classList.add('hidden');
            }
        });

        // Re-show suggestions on focus
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim().length > 0) {
                suggestionsBox.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>
