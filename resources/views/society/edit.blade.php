<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Edit Society - UniEvent Admin</title>
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
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_report') }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <p class="text-sm font-medium leading-normal">Reports &amp; Analytics</p>
            </a>
            <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white cursor-pointer transition-all" href="{{ route('user.admin_settings') }}">
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium leading-normal">System Settings</p>
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
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Edit Society</h2>
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
            <div class="max-w-4xl mx-auto flex flex-col gap-6">
                <div class="flex items-center gap-2 text-sm">
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors" href="{{ route('user.admin_society') }}">Societies</a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="font-medium text-slate-900 dark:text-white" id="societyName">Edit</span>
                </div>

                <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Society Information</h3>
                    
                    <form id="editForm" class="space-y-6">
                        <!-- Society Name -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Society Name</label>
                            <input type="text" id="societyNameInput" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-primary/50 focus:border-transparent" placeholder="Enter society name" />
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">This is the name members will see</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea id="descriptionInput" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-primary/50 focus:border-transparent" placeholder="Enter society description"></textarea>
                        </div>

                        <!-- Join Type -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Join Type</label>
                            <select id="joinTypeInput" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-transparent">
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="invitation">Invitation Only</option>
                            </select>
                        </div>

                        <!-- Who Can Post -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Who Can Post</label>
                            <select id="whoCanPostInput" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-transparent">
                                <option value="everyone">Everyone</option>
                                <option value="members">Members</option>
                                <option value="committee">Committee Only</option>
                                <option value="president_only">President Only</option>
                            </select>
                        </div>

                        <!-- President Selection -->
                        <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4">President</h4>
                            
                            <!-- Current President Display -->
                            <div id="currentPresidentCard" class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 hidden">
                                <p class="text-xs text-red-600 dark:text-red-400 font-semibold mb-2">CURRENT PRESIDENT</p>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-red-900 dark:text-red-200" id="currentPresidentName"></p>
                                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">ID: <span id="currentPresidentID"></span></p>
                                        <p class="text-sm text-red-700 dark:text-red-300">Email: <span id="currentPresidentEmail"></span></p>
                                    </div>
                                    <span class="material-symbols-outlined text-red-500">person</span>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="relative mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Search and select new president</label>
                                <div class="relative">
                                    <input type="text" id="presidentSearch" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-primary/50 focus:border-transparent" placeholder="Search by name or email..." />
                                    <div id="presidentDropdown" class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto z-10">
                                    </div>
                                </div>
                            </div>

                            <!-- New President Display -->
                            <div id="newPresidentCard" class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hidden">
                                <p class="text-xs text-green-600 dark:text-green-400 font-semibold mb-2">NEW PRESIDENT</p>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-green-900 dark:text-green-200" id="newPresidentName"></p>
                                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">ID: <span id="newPresidentID"></span></p>
                                        <p class="text-sm text-green-700 dark:text-green-300">Email: <span id="newPresidentEmail"></span></p>
                                    </div>
                                    <button type="button" onclick="clearNewPresident()" class="text-green-600 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Error Alert -->
                        <div id="errorAlert" class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 hidden"></div>

                        <!-- Success Alert -->
                        <div id="successAlert" class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 hidden"></div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                <span>Save Changes</span>
                            </button>
                            <a href="{{ route('user.admin_society') }}" class="flex items-center gap-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        const societyID = {{ $societyID }};
        let selectedPresidentID = null;
        let currentPresidentID = null;
        let societyData = null;

        // Fetch society details
        async function loadSociety() {
            try {
                const response = await fetch(`/api/societies/${societyID}`);
                const data = await response.json();
                
                if (data.status === 'S') {
                    societyData = data.society;
                    document.getElementById('societyName').textContent = societyData.name;
                    document.getElementById('societyNameInput').value = societyData.name;
                    document.getElementById('descriptionInput').value = societyData.description || '';
                    document.getElementById('joinTypeInput').value = societyData.joinType || 'open';
                    document.getElementById('whoCanPostInput').value = societyData.whoCanPost || 'everyone';
                    
                    if (societyData.president) {
                        currentPresidentID = societyData.president.id;
                        selectedPresidentID = societyData.president.id;
                        
                        // Display current president in red
                        document.getElementById('currentPresidentCard').classList.remove('hidden');
                        document.getElementById('currentPresidentName').textContent = societyData.president.name;
                        document.getElementById('currentPresidentID').textContent = societyData.president.id;
                        document.getElementById('currentPresidentEmail').textContent = societyData.president.email;
                    }
                }
            } catch (error) {
                console.error('Error loading society:', error);
                showError('Failed to load society details');
            }
        }

        // Search presidents
        let presidentTimeout;
        document.getElementById('presidentSearch').addEventListener('input', function(e) {
            clearTimeout(presidentTimeout);
            const search = e.target.value.trim();
            
            if (search.length < 1) {
                document.getElementById('presidentDropdown').classList.add('hidden');
                return;
            }

            presidentTimeout = setTimeout(() => {
                fetch(`/api/search-users?q=${encodeURIComponent(search)}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log('Search response:', data);
                        const dropdown = document.getElementById('presidentDropdown');
                        dropdown.innerHTML = '';
                        
                        // Handle different response formats
                        let users = [];
                        if (data.users && Array.isArray(data.users)) {
                            users = data.users;
                        } else if (Array.isArray(data)) {
                            users = data;
                        }
                        
                        console.log('Users to display:', users);
                        
                        if (users.length > 0) {
                            users.forEach(user => {
                                const item = document.createElement('div');
                                item.className = 'px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-600 cursor-pointer border-b border-slate-100 dark:border-slate-600 last:border-b-0 transition-colors';
                                item.innerHTML = `
                                    <p class="font-medium text-slate-900 dark:text-white text-sm">${user.name}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">ID: ${user.id}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">${user.email}</p>
                                `;
                                item.addEventListener('click', () => selectPresident(user.id, user.name, user.email));
                                dropdown.appendChild(item);
                            });
                            dropdown.classList.remove('hidden');
                        } else {
                            const noResult = document.createElement('div');
                            noResult.className = 'px-4 py-3 text-slate-500 dark:text-slate-400 text-sm';
                            noResult.textContent = 'No users found';
                            dropdown.appendChild(noResult);
                            dropdown.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching users:', error);
                        const dropdown = document.getElementById('presidentDropdown');
                        dropdown.innerHTML = '<div class="px-4 py-3 text-red-500 text-sm">Error searching users</div>';
                        dropdown.classList.remove('hidden');
                    });
            }, 300);
        });

        function selectPresident(userID, userName, userEmail) {
            selectedPresidentID = userID;
            document.getElementById('presidentSearch').value = '';
            document.getElementById('presidentDropdown').classList.add('hidden');
            
            // Display new president in green
            document.getElementById('newPresidentCard').classList.remove('hidden');
            document.getElementById('newPresidentName').textContent = userName;
            document.getElementById('newPresidentID').textContent = userID;
            document.getElementById('newPresidentEmail').textContent = userEmail;
        }

        function clearNewPresident() {
            selectedPresidentID = currentPresidentID;
            document.getElementById('newPresidentCard').classList.add('hidden');
            document.getElementById('presidentSearch').value = '';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const searchInput = document.getElementById('presidentSearch');
            const dropdown = document.getElementById('presidentDropdown');
            if (!e.target.closest('#presidentSearch') && !e.target.closest('#presidentDropdown')) {
                dropdown.classList.add('hidden');
            }
        });

        // Submit form
        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Update society info
                const updateResponse = await fetch(`/api/societies/${societyID}`, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        societyName: document.getElementById('societyNameInput').value,
                        societyDescription: document.getElementById('descriptionInput').value,
                        joinType: document.getElementById('joinTypeInput').value,
                        whoCanPost: document.getElementById('whoCanPostInput').value,
                    })
                });

                if (!updateResponse.ok) throw new Error('Failed to update society');

                // Change president if selected
                if (selectedPresidentID) {
                    const presidentResponse = await fetch(`/api/societies/${societyID}/president`, {
                        method: 'PUT',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ userID: selectedPresidentID })
                    });

                    if (!presidentResponse.ok) throw new Error('Failed to update president');
                }

                showSuccess('Society updated successfully!');
                setTimeout(() => window.location.href = "{{ route('user.admin_society') }}", 1500);
            } catch (error) {
                console.error('Error:', error);
                showError(error.message || 'Failed to save changes');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="material-symbols-outlined text-[20px]">save</span><span>Save Changes</span>';
            }
        });

        function showError(message) {
            const alert = document.getElementById('errorAlert');
            alert.textContent = message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 5000);
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            alert.textContent = message;
            alert.classList.remove('hidden');
        }

        // Load on page ready
        document.addEventListener('DOMContentLoaded', loadSociety);
    </script>
</body>

</html>
