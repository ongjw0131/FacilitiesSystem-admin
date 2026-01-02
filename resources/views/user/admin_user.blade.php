<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UniEvents Admin - User Management</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased overflow-hidden">
    <div class="flex h-screen w-full">
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
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary cursor-pointer transition-all" href="{{ route('user.admin_user') }}">
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
        <main class="flex flex-1 flex-col h-full overflow-hidden relative">
            <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4 z-10">
                <div class="flex items-center gap-4">
                    <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">User Management</h2>
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
            <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark p-8">
                <div class="max-w-7xl mx-auto flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                        <div class="p-4 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Total Users</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $users->count() }}</p>
                        </div>
                        <div class="p-4 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Admin</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $users->where('role', 'admin')->count() }}</p>
                        </div>
                        <div class="p-4 bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Students</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $users->where('role', 'student')->count() }}</p>
                        </div>

                    </div>
                    <div class="flex flex-col md:flex-row justify-between gap-4 items-start md:items-center bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="flex flex-col sm:flex-row flex-1 w-full gap-4">
                            
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <button class="flex items-center justify-center w-full md:w-auto gap-2 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg transition-colors text-sm font-medium shadow-sm shadow-primary/30" onclick="document.getElementById('addUserModal').classList.remove('hidden')">
                                <span class="material-symbols-outlined text-[20px]">person_add</span>
                                Add New User
                            </button>
                        </div>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                        <th class="py-4 px-6 w-12 text-center">
                                            <input class="rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary/50 bg-transparent" type="checkbox" />
                                        </th>
                                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider cursor-pointer hover:text-primary select-none group">
                                            User <span class="material-symbols-outlined align-middle text-[14px] opacity-0 group-hover:opacity-100 transition-opacity">unfold_more</span>
                                        </th>
                                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Role &amp; Affiliation</th>
                                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Joined Date</th>
                                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse($users as $user)
                                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-4 px-6 text-center">
                                            <input class="rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary/50 bg-transparent" type="checkbox" />
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                @if(is_null($user->profile_picture_file_path))
                                                    <div class="size-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-sm">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $user->profile_picture_file_path) }}" alt="{{ $user->name }}" class="size-9 rounded-full bg-cover border-2 border-indigo-200 dark:border-indigo-700" />
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ ucfirst(str_replace('_', ' ', $user->role ?? 'student')) }}</span>
                                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $user->major ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($user->status === 'active')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium">
                                                    <span class="size-1.5 rounded-full bg-green-600 dark:bg-green-400"></span> Active
                                                </span>
                                            @elseif($user->status === 'inactive')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400 text-xs font-medium">
                                                    <span class="size-1.5 rounded-full bg-gray-600 dark:bg-gray-400"></span> Inactive
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-medium">
                                                    <span class="size-1.5 rounded-full bg-yellow-600 dark:bg-yellow-400"></span> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm text-slate-500 dark:text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                                <form method="POST" action="{{ route('user.deleteUser', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center justify-center size-8 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete User">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-6 text-center text-slate-500 dark:text-slate-400">
                                            No users found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 dark:bg-slate-800/30">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Showing <span class="font-semibold text-slate-900 dark:text-white">1</span> to <span class="font-semibold text-slate-900 dark:text-white">{{ min(10, $users->count()) }}</span> of <span class="font-semibold text-slate-900 dark:text-white">{{ $users->count() }}</span> results</p>
                            <div class="flex gap-2">
                                <button class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark text-slate-500 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled="">Previous</button>
                                <button class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark text-slate-500 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add New User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Add New Admin User</h3>
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-2xl leading-none">&times;</button>
            </div>
            <form id="addUserForm" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="John Doe" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="john@example.com" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="••••••••" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="••••••••" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contact Number</label>
                    <input type="tel" name="contact_number" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="+1 234 567 8900" />
                </div>
                <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="flex-1 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-primary hover:bg-primary-hover text-white transition-colors text-sm font-medium">
                        Create Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>