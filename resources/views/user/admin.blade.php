<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UniEvents Admin Dashboard</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Theme Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc2",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2230",
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
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar Navigation -->
        <aside class="flex w-72 flex-col border-r border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark transition-colors duration-300">
            <!-- Logo Section -->
            <div class="flex items-center gap-3 px-6 py-6">
                <div class="bg-center bg-no-repeat bg-cover rounded-lg size-10 shadow-sm" data-alt="University crest logo abstract blue and white" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCMkVeislnnaakM2YXnluxWNB7NhdgYy47tXoczBKrfbvHXgBjhLPSi1A53AZr0JWkj9Af_tPh41QHFg1BgZgZR9MDsdnRFF9gGvuqUTj5zP9wMNlFCCVRz79dMLK8HYCEXDraZ5k4XwhT9oQ_DJCb9QlpYUGvI8SDXFWRv6nR_11pT0dcX6OXZ4v97qVKhb5ytp3Cw63oSk1vgONPU-StsR0abvPUs1cn7Um0fR5RSXsGAAIK0rJCMKROlDGngG1mAaTulSdtYC65Y");'></div>
                <div class="flex flex-col">
                    <h1 class="text-slate-900 dark:text-white text-base font-bold leading-none">UniEvents</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-normal mt-1">Admin Console v2.4</p>
                </div>
            </div>
            <!-- Navigation Links -->
            <div class="flex flex-col flex-1 gap-2 px-4 py-4 overflow-y-auto">
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary cursor-pointer transition-all" href="{{ route('user.admin') }}">
                    <span class="material-symbols-outlined filled">dashboard</span>
                    <p class="text-sm font-medium leading-normal">Dashboard</p>
                </a>
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_user') }}">
                    <span class="material-symbols-outlined">group</span>
                    <p class="text-sm font-medium leading-normal">User Management</p>
                </a>
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_society') }}">
                    <span class="material-symbols-outlined">diversity_3</span>
                    <p class="text-sm font-medium leading-normal">Society Management</p>
                </a>
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_event') }}">
                    <span class="material-symbols-outlined">event_note</span>
                    <p class="text-sm font-medium leading-normal">Event Oversight</p>
                </a>
            </div>
            <!-- Footer / Logout -->
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
        <!-- Main Content Area -->
        <main class="flex flex-1 flex-col h-full overflow-hidden relative">
            <!-- Top Nav Bar -->
            <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4 z-10">
                <div class="flex items-center gap-4">
                    <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Overview</h2>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border border-white dark:border-surface-dark"></span>
                    </button>
                    <!-- Profile -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200 dark:border-slate-700">
                        <div class="text-right hidden lg:block">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">System Administrator</p>
                        </div>
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-white dark:border-slate-700 shadow-sm" data-alt="Profile picture of a person looking professional" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1WT5ZuTEcKB1jtvKBFRKkz1uw214PYkNuPpjCQ9kjirfhpiJyGwi7UJH4qpcG-j-GPVCJk6eB9rUxYpfe0EupgcwX9lYvGUWnw7r5w7pbmMTNkyNXuQ6HdvGL4bXtGJV7Tzl9basBSqIdH1c8F-BzDzwXlsladA7kQteelhc6ztPlzPP3ZPeqZKso0a3oZ33BytAU20DZv8gQTlYf3ZBuRiEegXrCNbWiuKUrXkvYJeIuBJA-tJ8pZyZyqgL8jhNKmPSkosy1_j4u");'></div>
                    </div>
                </div>
            </header>
            <!-- Dashboard Content Scroll Area -->
            <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark p-8">
                <div class="max-w-7xl mx-auto flex flex-col gap-8">
                    <!-- Stats Section -->
                    <section>
                        <div class="flex flex-wrap gap-4">
                            <!-- Stat Card 1 -->
                            <div class="flex min-w-[240px] flex-1 flex-col gap-1 rounded-xl p-5 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-primary">
                                        <span class="material-symbols-outlined">diversity_3</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Active Societies</p>
                                    <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1" data-active-societies></p>
                                </div>
                            </div>
                            <!-- Stat Card 3 -->
                            <div class="flex min-w-[240px] flex-1 flex-col gap-1 rounded-xl p-5 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Registered Students</p>
                                    <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1">{{ $users->where('role', 'student')->count() }}</p>
                                </div>
                            </div>
                            <!-- Stat Card 4 -->
                            <div class="flex min-w-[240px] flex-1 flex-col gap-1 rounded-xl p-5 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="p-2 bg-teal-50 dark:bg-teal-900/20 rounded-lg text-teal-600 dark:text-teal-400">
                                        <span class="material-symbols-outlined">calendar_month</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Upcoming Events</p>
                                    <p class="text-slate-900 dark:text-white text-2xl font-bold mt-1" data-upcoming-events>0</p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between h-full hover:border-primary/50 transition-colors group">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Events</p>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white" data-active-events>0</h3>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary">event_available</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between h-full hover:border-primary/50 transition-colors group">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Revenue</p>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white" data-total-revenue>$0</h3>
                                </div>
                                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-2 rounded-lg group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                                    <span class="material-symbols-outlined text-emerald-600">attach_money</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-text-main dark:text-black">Top 5 Most Popular Societies</h3>
                                <p class="text-xs text-text-sub mt-1">Based on highest member count</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-text-sub">Sort by:</span>
                                <select class="bg-transparent text-xs font-medium text-text-main dark:text-white border-none focus:ring-0 p-0 cursor-pointer">
                                    <option>Members</option>
                                    <option>Rating</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6 flex-1 justify-center" id="topSocietiesContainer">
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>