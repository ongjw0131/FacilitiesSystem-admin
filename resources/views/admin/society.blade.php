<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Society Management - UniEvents Admin</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2230",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased">
    <div class="flex h-screen w-full">
        <aside class="w-72 border-r border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark p-6">
            <div class="flex items-center gap-3 mb-8">
                <h1 class="text-slate-900 dark:text-white text-base font-bold">UniEvents</h1>
            </div>
            <nav class="space-y-2">
                <a href="{{ route('user.admin') }}" class="block px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">Dashboard</a>
                <a href="{{ route('user.admin_user') }}" class="block px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">User Management</a>
                <a href="{{ route('user.admin_society') }}" class="block px-3 py-2.5 rounded-lg bg-primary/10 text-primary">Society Management</a>
                <a href="{{ route('user.admin_event') }}" class="block px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">Event Oversight</a>
                <a href="{{ route('user.admin_reports') }}" class="block px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">Reports</a>
                <a href="{{ route('user.admin_settings') }}" class="block px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">Settings</a>
            </nav>
            <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 flex flex-col">
            <header class="border-b border-slate-200 dark:border-slate-800 bg-surface-light dark:bg-surface-dark px-8 py-4">
                <h2 class="text-slate-900 dark:text-white text-xl font-bold">Society Management</h2>
            </header>
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl">
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-slate-200 dark:border-slate-800 p-8">
                        <p class="text-slate-500 dark:text-slate-400">Society management page coming soon...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
