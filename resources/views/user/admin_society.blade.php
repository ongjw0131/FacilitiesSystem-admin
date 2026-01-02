<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Society Management - UniEvent Admin</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    @vite(['resources/js/app.js'])
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Society Management</h2>
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
            <div class="max-w-7xl mx-auto flex flex-col gap-6">
                <div class="flex items-center gap-2 text-sm">
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="#">Dashboard</a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="font-medium text-slate-900 dark:text-white">Societies</span>
                </div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white tracking-tight">Society Management</h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Manage, add, and monitor all university societies.</p>
                    </div>
                    <a href="{{ route('society.create') }}" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg font-medium transition-colors shadow-sm shadow-primary/30">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        <span>Add New Society</span>
                    </a>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="w-full lg:w-96">
                        <label class="relative block w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="material-symbols-outlined text-slate-400">search</span>
                            </span>
                            <input class="w-full bg-background-light dark:bg-background-dark border-none rounded-lg py-2.5 pl-10 pr-4 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-primary/50" name="search" id="searchInput" placeholder="Search by name or president..." type="text" />
                        </label>
                    </div>
                    <div class="flex flex-wrap md:flex-nowrap gap-3 w-full lg:w-auto">
                        <div class="w-full md:w-48">
                            <div class="relative">
                                <select class="appearance-none w-full bg-background-light dark:bg-background-dark border-none rounded-lg py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-primary/50 cursor-pointer" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="banned">Banned</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border-light dark:border-border-dark bg-gray-50 dark:bg-slate-800/50">
                                    <th class="p-4 w-12 text-center">
                                        <input class="rounded border-slate-300 text-primary focus:ring-primary/50 bg-white dark:bg-slate-700 dark:border-slate-600" type="checkbox" />
                                    </th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Society Name</th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Category</th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">President</th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Members</th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                    <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark text-sm" id="societyTableBody">
                                <!-- Data will be populated by JavaScript from API -->
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col md:flex-row items-center justify-between p-4 border-t border-border-light dark:border-border-dark bg-gray-50/50 dark:bg-slate-800/30">
                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-4 md:mb-0">
                            Total <span class="font-semibold text-slate-900 dark:text-white" id="societyCount">0</span> <span id="societyLabel">societies</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 rounded border border-border-light dark:border-border-dark text-slate-500 dark:text-slate-400 bg-white dark:bg-surface-dark text-sm hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50" disabled="">
                                Previous
                            </button>
                            <div class="flex items-center gap-1">
                                <button class="size-8 rounded border border-primary bg-primary text-white text-sm font-medium">1</button>
                                <button class="size-8 rounded border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700">2</button>
                                <button class="size-8 rounded border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700">3</button>
                                <span class="text-slate-400 px-1">...</span>
                                <button class="size-8 rounded border border-border-light dark:border-border-dark bg-white dark:bg-surface-dark text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700">10</button>
                            </div>
                            <button class="px-3 py-1.5 rounded border border-border-light dark:border-border-dark text-slate-500 dark:text-slate-400 bg-white dark:bg-surface-dark text-sm hover:bg-slate-50 dark:hover:bg-slate-700">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let allSocieties = [];

        // Fetch societies from API
        async function fetchSocieties() {
            try {
                const response = await fetch('/api/societies/all');
                const data = await response.json();
                
                if (data.status === 'S') {
                    allSocieties = data.societies || [];
                    renderTable(allSocieties);
                    updateCount();
                }
            } catch (error) {
                console.error('Error fetching societies:', error);
            }
        }

        // Render table with societies
        function renderTable(societies) {
            const tbody = document.getElementById('societyTableBody');
            tbody.innerHTML = '';

            if (societies.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">groups</span>
                                <p class="font-medium text-slate-500 dark:text-slate-400">No societies found</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Try adjusting your search filters.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            societies.forEach(society => {
                const row = document.createElement('tr');
                row.className = 'group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors';
                
                const photoUrl = society.photoPath 
                    ? `/storage/${society.photoPath}` 
                    : 'https://via.placeholder.com/40';
                    
                const presidentName = society.president?.name || 'N/A';
                const joinTypeFormatted = society.joinType ? society.joinType.charAt(0).toUpperCase() + society.joinType.slice(1) : 'General';
                
                // Format creation date
                let formattedDate = 'N/A';
                try {
                    if (society.createdAt) {
                        const createdDate = new Date(society.createdAt);
                        if (!isNaN(createdDate.getTime())) {
                            formattedDate = createdDate.toLocaleDateString('en-US', { 
                                year: 'numeric', 
                                month: 'short', 
                                day: 'numeric' 
                            });
                        }
                    }
                } catch (e) {
                    console.error('Error parsing date:', e);
                }
                
                row.innerHTML = `
                    <td class="p-4 text-center">
                        <input class="rounded border-slate-300 text-primary focus:ring-primary/50 bg-white dark:bg-slate-700 dark:border-slate-600" type="checkbox" />
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden shrink-0 border border-slate-100 dark:border-slate-700">
                                ${society.photoPath ? `<img alt="${society.name}" class="w-full h-full object-cover" src="${photoUrl}" />` : `<span class="text-sm font-bold text-blue-700">${society.name.substring(0, 2)}</span>`}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-900 dark:text-white">${society.name}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Created: ${formattedDate}</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">
                            ${joinTypeFormatted}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-full bg-slate-200 overflow-hidden" style="background-image: url('https://ui-avatars.com/api/?name=${encodeURIComponent(presidentName)}'); background-size: cover;"></div>
                            <span class="text-slate-700 dark:text-slate-300">${presidentName}</span>
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-400">${society.memberCount || 0}</td>
                    <td class="p-4">
                        ${society.isDelete === 1 ? `
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                <span class="size-1.5 rounded-full bg-red-500"></span>
                                Banned
                            </span>
                        ` : `
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        `}
                    </td>
                    <td class="p-4 text-right">
                        <div class="relative group">
                            <button class="text-slate-400 hover:text-primary transition-colors p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800">
                                <span class="material-symbols-outlined text-[20px]">more_vert</span>
                            </button>
                            <div class="absolute right-0 mt-1 w-40 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                                <a href="/society/${society.id}/edit" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 first:rounded-t-lg">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    <span>Edit</span>
                                </a>
                                <button onclick="banSociety(${society.id})" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 last:rounded-b-lg">
                                    <span class="material-symbols-outlined text-[18px]">block</span>
                                    <span>Ban</span>
                                </button>
                            </div>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        // Update society count
        function updateCount() {
            const count = allSocieties.length;
            document.getElementById('societyCount').textContent = count;
            document.getElementById('societyLabel').textContent = count === 1 ? 'society' : 'societies';
        }

        // Filter societies based on search and status
        function filterSocieties() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('statusFilter').value;

            // If banned filter is selected, fetch banned societies from API
            if (statusFilter === 'banned') {
                fetch('/api/societies/banned')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'S') {
                            const societies = data.societies || [];
                            renderTable(societies);
                            
                            // Update count
                            const count = societies.length;
                            document.getElementById('societyCount').textContent = count;
                            document.getElementById('societyLabel').textContent = count === 1 ? 'banned society' : 'banned societies';
                        }
                    })
                    .catch(error => console.error('Error fetching banned societies:', error));
                return;
            }

            if (searchTerm.length === 0 && statusFilter === '') {
                // If search is empty and no filter, show all active societies
                renderTable(allSocieties);
                updateCount();
                return;
            }

            // Fetch from API with search query
            fetch(`/api/societies/search?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'S') {
                        const societies = data.societies || [];
                        renderTable(societies);
                        
                        // Update count
                        const count = societies.length;
                        document.getElementById('societyCount').textContent = count;
                        document.getElementById('societyLabel').textContent = count === 1 ? 'society' : 'societies';
                    }
                })
                .catch(error => console.error('Error searching societies:', error));
        }

        // Ban society
        function banSociety(societyId) {
            if (!confirm('Are you sure you want to ban this society? This will:\n- Mark the society as deleted\n- Remove all members (president, committee, and regular members)\n\nThis action cannot be undone.')) {
                return;
            }

            fetch(`/api/societies/${societyId}/ban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'S' || data.success) {
                    alert('Society banned successfully');
                    fetchSocieties(); // Refresh the table
                } else {
                    alert('Error: ' + (data.message || 'Failed to ban society'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error banning society');
            });
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', filterSocieties);
        document.getElementById('statusFilter').addEventListener('change', filterSocieties);

        // Load societies on page load
        document.addEventListener('DOMContentLoaded', fetchSocieties);
    </script>

</body>

</html>