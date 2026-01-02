<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'University Event Manager')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;family=Noto+Sans:wght@100..900&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#135bec",
                            "background-light": "#f6f6f8",
                            "surface-light": "#ffffff",
                            "background-dark": "#101622",
                        },
                        fontFamily: {
                            "display": ["Lexend", "sans-serif"],
                            "body": ["Noto Sans", "sans-serif"]
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
        }
    </script>

</head>

<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white font-display">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <!-- Header -->
        <header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-[#f0f2f4] dark:border-[#2a3441] bg-white dark:bg-[#1a202c] px-4 py-3 md:px-10">
            <a class="flex items-center gap-4 text-[#111318] dark:text-white" href="/">
                <div class="size-8 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h2 class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">UniEvent</h2>
            </a>
            <div class="flex flex-1 justify-end gap-8">
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="/">Home</a>
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="{{ route('event.index') }}">Events</a>
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="{{ route('society.index') }}">Societies</a>
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="{{ route('admin.facilities.index') }}">Facilities</a>
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="{{ route('society.joined') }}">My Societies</a>
                    <a class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary transition-colors" href="#">About</a>
                </nav>
                <div class="flex gap-2 items-center">
                    @auth
                    <!-- Notification Bell -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors" title="Notifications">
                        <span class="material-symbols-outlined text-2xl text-[#111318] dark:text-white">notifications</span>
                    </a>
                    <div class="relative group">
                        <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity">
                            @if (is_null(Auth::user()->profile_picture_file_path))
                            <div class="relative shrink-0">
                                <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 border-2 border-white dark:border-slate-600 shadow-sm cursor-pointer" data-alt="Current profile picture of Alex Rivera" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC_W6kqmFckyjW9lvBlGpXrV0fF5jKtdxdxKg6Ggex_3JnJxz_AgK_QudCJKuC8Y0dMaGAF4tll9K8m9k5r0O5oV-9ui3PtRHFBRYtLoLRQzDUFHYQzlV0jkkNDvwDGeNCP17SuZIZHSwu38Qg5iSmAVLl-Qgerqi8iuFO-m3qgGVGmf4_wBedr6fGodVxWlGWeLRklcDl4pyj0guIEl_JLRpWtEY2Rx00fEq7ptbum-ppk9W7MQKMrIXyAx0kXJJ8Ee_rjoM1-TIpG");'></div>
                            </div>
                            @else
                            <div class="relative shrink-0">
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture_file_path) }}" class="bg-center bg-no-repeat bg-cover rounded-full size-10 border-2 border-white dark:border-slate-600 shadow-sm cursor-pointer" alt="Profile picture" />
                            </div>
                            @endif
                            <span class="text-sm font-medium text-[#111318] dark:text-white hidden md:inline">{{ Auth::user()->name }}</span>
                        </div>
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-3 border-b border-[#f0f2f4] dark:border-[#2a3441] hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <p class="text-sm font-semibold text-[#111318] dark:text-white hover:text-primary">Profile</p>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('user.login') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-6 bg-primary hover:bg-blue-700 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
                        <span class="truncate">Login</span>
                    </a>
                    <!-- Secondary Button Design: White bg, primary border, primary text -->
                    <a href="{{ route('user.signup') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-6 bg-white dark:bg-transparent border border-primary text-primary hover:bg-blue-50 dark:hover:bg-blue-900/30 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
                        <span class="truncate">Sign Up</span>
                    </a>
                    @endauth
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