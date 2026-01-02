@include('../login_head')
@section('title', 'Login - University Event Manager')


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
                        "fade-in": {
                            "0%": {
                                opacity: "0"
                            },
                            "100%": {
                                opacity: "1"
                            }
                        },
                        "fade-in-up": {
                            "0%": {
                                opacity: "0",
                                transform: "translateY(10px)"
                            },
                            "100%": {
                                opacity: "1",
                                transform: "translateY(0)"
                            }
                        }
                    },
                    animation: {
                        "fade-in": "fade-in 1s ease-out forwards",
                        "fade-in-up": "fade-in-up 1s ease-out forwards"
                    }
                },
            },
        }
    </script>

    @vite(['resources/js/app.js'])
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-white overflow-x-hidden antialiased">
    <div class="flex min-h-screen w-full flex-row animate-fade-in">
        <div class="hidden lg:flex lg:w-1/2 relative bg-background-dark overflow-hidden">
            <img class="absolute inset-0 w-full h-full object-cover" data-alt="University students walking on campus during autumn" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuA4-XNLC56-gZ5oO6KfVa8LdVa4__2r9OgJB1vq8eE9wlUMv8Zre0V-3bx7CTTAjdqJqEIEjG2XFAuHS228T351xwWy8mWEDXwa7UZkh0Mikq7aqgo5f49POUWS-HGil0EJy3Y_l5VwkCfbkICs1vLFjnamYC96ot_mxITot-iTXc7pdps-Lr-L7IqMKPCZynsceMJUjuQ_FXDM_HmEG9sGXHXr80fE0_sBCWfdyQkWzDz_OzIEftNqJGfMhpuxa6_PYGOBFgaC2H" />
            <div class="absolute inset-0 bg-gradient-to-br from-primary/70 to-background-dark/80"></div>
            <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full text-white">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-4xl">school</span>
                    <span class="text-xl font-bold tracking-tight">UniEvents</span>
                </div>
                <div class="mb-12">
                    <h1 class="text-5xl font-black leading-tight tracking-tight mb-6">Manage your<br />society events.</h1>
                    <p class="text-lg font-light opacity-90 max-w-md leading-relaxed">
                        Join thousands of students and faculty members organizing the next big campus experience.
                    </p>
                </div>
                <div class="flex items-center gap-4 text-sm font-medium opacity-70">
                    <span>© 2024 University System</span>
                    <span class="w-1 h-1 bg-white rounded-full"></span>
                    <a class="hover:text-white transition-colors" href="#">Privacy</a>
                    <span class="w-1 h-1 bg-white rounded-full"></span>
                    <a class="hover:text-white transition-colors" href="#">Terms</a>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative bg-white dark:bg-background-dark transition-colors duration-300">
            <div class="lg:hidden absolute top-8 left-8 flex items-center gap-2 text-primary dark:text-white">
                <span class="material-symbols-outlined text-3xl">school</span>
                <span class="text-lg font-bold">UniEvents</span>
            </div>
            <div class="w-full max-w-[440px] flex flex-col gap-8">
                <div class="flex flex-col gap-2 animate-fade-in-up">
                    <h2 class="text-[#111318] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Welcome back</h2>
                    <p class="text-[#616f89] dark:text-slate-400 text-base font-normal leading-normal">
                        Log in to access your dashboard.
                    </p>
                </div>
                <form class="flex flex-col gap-5" method="POST" action="{{ route('login') }}">
                    @csrf
                    <label class="flex flex-col w-full group">
                        <p class="text-[#111318] dark:text-white text-base font-medium leading-normal pb-2 group-focus-within:text-primary transition-colors">University Email</p>
                        <div class="relative">
                            <input id="emailInput" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-[#1a2332] focus:border-primary dark:focus:border-primary h-14 placeholder:text-[#616f89] dark:placeholder:text-gray-500 px-[15px] pl-11 text-base font-normal leading-normal transition-all shadow-sm @error('email') border-red-500 @enderror" placeholder="user@student.tarc.edu.my" type="email" name="email" value="{{ old('email') }}" maxlength="255" required oninput="validateLoginForm()" />
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#616f89] dark:text-gray-500 pointer-events-none text-[20px]">mail</span>
                        </div>
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col w-full group">
                        <div class="flex justify-between items-baseline pb-2">
                            <p class="text-[#111318] dark:text-white text-base font-medium leading-normal group-focus-within:text-primary transition-colors">Password</p>
                        </div>
                        <div class="relative">
                            <input id="passwordInput" class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-0 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-[#1a2332] focus:border-primary dark:focus:border-primary h-14 placeholder:text-[#616f89] dark:placeholder:text-gray-500 px-[15px] pl-11 text-base font-normal leading-normal transition-all shadow-sm @error('password') border-red-500 @enderror" placeholder="********" type="password" name="password" maxlength="255" required oninput="validateLoginForm()" />
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#616f89] dark:text-gray-500 pointer-events-none text-[20px]">lock</span>
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#616f89] hover:text-primary dark:text-gray-500 dark:hover:text-white transition-colors cursor-pointer outline-none" type="button" onclick="togglePasswordVisibility(this)">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                    <div class="flex justify-end -mt-2">
                        <a class="text-[#616f89] dark:text-slate-400 text-sm font-normal hover:text-primary dark:hover:text-primary underline decoration-transparent hover:decoration-current transition-all" href="{{ route('user.forgotPassword') }}">Forgot Password?</a>
                    </div>
                    <button id="loginBtn" type="submit" class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary hover:bg-blue-700 text-white text-base font-bold leading-normal tracking-[0.015em] shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-all duration-200 mt-2">
                        <span class="truncate">Log In</span>
                    </button>
                </form>
                <div class="flex flex-col items-center gap-4 pt-2">
                    <p class="text-[#616f89] dark:text-slate-400 text-sm font-normal text-center">
                        Don't have an account yet?
                        <a class="text-primary font-bold hover:underline ml-1" href="signup">Register your account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>