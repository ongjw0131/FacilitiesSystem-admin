@include('../login_head')
@section('title', 'Reset Password - University Event Manager')

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-dark": "#104abf",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "text-main": "#111318",
                        "text-muted": "#616f89",
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
                    keyframes: {
                        fadeIn: {
                            "0%": {
                                opacity: "0"
                            },
                            "100%": {
                                opacity: "1"
                            }
                        },
                        fadeInUp: {
                            "0%": {
                                opacity: "0",
                                transform: "translateY(20px)"
                            },
                            "100%": {
                                opacity: "1",
                                transform: "translateY(0)"
                            }
                        }
                    },
                    animation: {
                        "fade-in": "fadeIn 0.7s ease-out forwards",
                        "fade-in-up": "fadeInUp 0.8s ease-out 0.2s both"
                    }
                },
            },
        }
    </script>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark font-display antialiased text-text-main dark:text-white overflow-hidden">
    <div class="flex min-h-screen w-full animate-fade-in">
        <div class="flex flex-col w-full lg:w-1/2 xl:w-5/12 p-8 sm:p-12 lg:p-16 justify-between relative bg-white dark:bg-gray-900 z-10 shadow-xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 text-text-main dark:text-white">
                    <div class="size-8 text-primary">
                        <svg class="w-full h-full" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">UniEvents</h2>
                </div>
                <a class="flex items-center gap-1 text-sm font-medium text-text-muted hover:text-primary transition-colors cursor-pointer" href="{{ route('user.login') }}">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    Back
                </a>
            </div>
            <div class="w-full max-w-md mx-auto space-y-8 animate-fade-in-up">
                <div class="flex flex-col gap-3">
                    <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-2">
                        <span class="material-symbols-outlined text-3xl">vpn_key</span>
                    </div>
                    <h1 class="text-text-main dark:text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">Reset your password</h1>
                    <p class="text-text-muted dark:text-gray-400 text-base font-normal leading-normal">
                        Enter your new password below. Make sure it's strong and secure.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.reset') }}" class="flex flex-col gap-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <input type="hidden" name="email" value="{{ $email }}" />

                    <div class="flex flex-col gap-2">
                        <label class="text-text-main dark:text-gray-200 text-sm font-bold leading-normal">New Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-8 -translate-y-1/2 text-text-muted">
                                <span class="material-symbols-outlined">lock</span>
                            </span>
                            <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-text-main dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-text-muted pl-12 pr-4 text-base font-normal leading-normal transition-all" 
                                   type="password" name="password" id="password" placeholder="Enter new password" required />
                            <button class="absolute right-3 top-5 text-[#616f89] dark:text-[#9ca3af] hover:text-primary transition-colors" type="button" onclick="togglePasswordVisibility(this)">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        <p class="text-xs text-text-muted">At least 8 characters with uppercase, lowercase, number, and special character</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-text-main dark:text-gray-200 text-sm font-bold leading-normal">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-8 -translate-y-1/2 text-text-muted">
                                <span class="material-symbols-outlined">lock</span>
                            </span>
                            <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-text-main dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-text-muted pl-12 pr-4 text-base font-normal leading-normal transition-all" 
                                   type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" required />
                            <button class="absolute right-3 top-5 text-[#616f89] dark:text-[#9ca3af] hover:text-primary transition-colors" type="button" onclick="togglePasswordVisibility(this)">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 bg-primary hover:bg-primary-dark text-white text-base font-bold leading-normal tracking-[0.015em] transition-all shadow-lg shadow-primary/20 active:scale-[0.98]">
                        <span class="truncate">Reset Password</span>
                    </button>
                </form>
            </div>
            <div class="flex justify-between items-center text-xs text-text-muted dark:text-gray-500">
                <p>© 2024 UniEvents Systems</p>
                <div class="flex gap-4">
                    <a class="hover:text-primary" href="#">Help</a>
                    <a class="hover:text-primary" href="#">Privacy</a>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex lg:w-1/2 xl:w-7/12 relative bg-background-light dark:bg-gray-800 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 hover:scale-105" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDBpeo-Gs6z0vz_4rVSS8PLZ_5oNnv2gtlK3yjSzMQEIst4O70rt9AWoPFkfXkfXwyOAycKPrVoBvMJ44u3OBxNmE5z-pX5g146POI1R9kVbPi71n-0cYyj48KtrPrs29qyyM3CA_xOzCUxa551f3tWh0l5uI9D6kRcJCcQ2h5QT1q166csBIZIL1G5jozdnAQNJe4aZGSRBKm-3hrBWgGrC6UFSMD3-JTTKDaHChzJGvWcPT7nt41xXMVgzW7cf2JFpWuNaN2QubwK');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-16 w-full text-white z-20">
                <div class="max-w-lg space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/10 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        System Online
                    </div>
                    <h2 class="text-4xl font-bold leading-tight">Secure your account with a strong password.</h2>
                    <p class="text-lg text-white/80 leading-relaxed">Create a password only you know to keep your account safe.</p>
                    <div class="flex gap-2 pt-4">
                        <div class="h-1 w-12 bg-white rounded-full"></div>
                        <div class="h-1 w-2 bg-white/30 rounded-full"></div>
                        <div class="h-1 w-2 bg-white/30 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
