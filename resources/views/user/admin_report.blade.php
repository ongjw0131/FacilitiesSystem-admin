<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Report &amp; Analysis Page - UniEvent Admin</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;900&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-content": "#ffffff",
                        "primary-light": "#eef4ff",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2230",
                        "text-main": "#111318",
                        "text-sub": "#616f89",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
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

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark text-text-main dark:text-white h-screen flex overflow-hidden">
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
    <div class="flex-1 flex flex-col h-full min-w-0">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4 z-10">
            <div class="flex items-center gap-4">
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Reports & Analytics</h2>
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
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-8">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-2 text-sm">
                        <a class="text-text-sub hover:text-primary transition-colors flex items-center gap-1" href="#">
                            <span class="material-symbols-outlined text-[16px]">home</span>
                            Dashboard
                        </a>
                        <span class="text-text-sub">/</span>
                        <span class="text-text-main dark:text-white font-medium">Reports</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-3xl font-black tracking-tight text-text-main dark:text-white">Reports &amp; Analysis</h1>
                            <p class="text-text-sub dark:text-gray-400 max-w-2xl">Insights into event performance, society engagement, and financial overviews.</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 p-1">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-dark rounded-full border border-gray-200 dark:border-gray-700 shadow-sm text-sm cursor-pointer hover:border-primary transition-colors">
                            <span class="text-text-sub">Date Range:</span>
                            <span class="font-medium text-text-main dark:text-white">Fall Semester 2023</span>
                            <span class="material-symbols-outlined text-[18px] text-text-sub">expand_more</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-dark rounded-full border border-gray-200 dark:border-gray-700 shadow-sm text-sm cursor-pointer hover:border-primary transition-colors">
                            <span class="text-text-sub">Society:</span>
                            <span class="font-medium text-text-main dark:text-white">All Societies</span>
                            <span class="material-symbols-outlined text-[18px] text-text-sub">expand_more</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-surface-dark rounded-full border border-gray-200 dark:border-gray-700 shadow-sm text-sm cursor-pointer hover:border-primary transition-colors">
                            <span class="text-text-sub">Event Type:</span>
                            <span class="font-medium text-text-main dark:text-white">All Types</span>
                            <span class="material-symbols-outlined text-[18px] text-text-sub">expand_more</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between h-full hover:border-primary/50 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-text-sub mb-1">Total Events</p>
                                <h3 class="text-2xl font-bold text-text-main dark:text-white" data-active-events>0</h3>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
                                <span class="material-symbols-outlined text-primary">event_available</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1 text-sm font-medium text-green-600">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                            <span>12% vs last semester</span>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between h-full hover:border-primary/50 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-text-sub mb-1">Active Societies</p>
                                <h3 class="text-2xl font-bold text-text-main dark:text-white" data-active-societies>0</h3>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/30 p-2 rounded-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900/50 transition-colors">
                                <span class="material-symbols-outlined text-purple-600">groups</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1 text-sm font-medium text-green-600">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                            <span>3 new this month</span>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between h-full hover:border-primary/50 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-text-sub mb-1">Total Revenue</p>
                                <h3 class="text-2xl font-bold text-text-main dark:text-white" data-total-revenue></h3>
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-900/30 p-2 rounded-lg group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                                <span class="material-symbols-outlined text-emerald-600">attach_money</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1 text-sm font-medium text-green-600">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                            <span>From paid ticket orders</span>
                        </div>
                    </div>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-text-main dark:text-white">Top 5 Most Popular Societies</h3>
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
                        <!-- Top 5 societies will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>