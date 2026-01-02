@include('../login_head')
@section('title', 'Sign Up - University Event Manager')

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
                        fadeIn: {
                            "0%": {
                                opacity: "0"
                            },
                            "100%": {
                                opacity: "1"
                            }
                        }
                    },
                    animation: {
                        "fade-in": "fadeIn 0.6s ease-out forwards"
                    }
                },
            },
        }
    </script>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-white min-h-screen flex flex-col animate-fade-in">
    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#dbdfe6] dark:border-[#2a3140] px-10 py-4 bg-white dark:bg-[#1a202c]">
        <div class="flex items-center gap-4">
            <div class="size-8 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">school</span>
            </div>
            <h2 class="text-[#111318] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">UniEvent Manager</h2>
        </div>
        <div class="flex gap-4 items-center">
            <span class="hidden sm:inline text-sm text-[#616f89] dark:text-[#9ca3af] font-medium">Already have an account?</span>
            <a href="login" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 hover:bg-primary/20 text-primary dark:text-blue-300 text-sm font-bold leading-normal transition-colors">
                <span class="truncate">Log In</span>
            </a>
        </div>
    </header>
    <main class="flex-1 flex flex-col lg:flex-row h-full overflow-hidden">
        <div class="flex-1 flex flex-col justify-center items-center p-6 lg:p-12 overflow-y-auto bg-white dark:bg-[#1a202c]">
            <div class="w-full max-w-[480px] flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <h1 class="text-[#111318] dark:text-white text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Create your Account</h1>
                    <p class="text-[#616f89] dark:text-[#9ca3af] text-base font-normal leading-normal">Manage societies and discover events happening on campus.</p>
                </div>
                <form class="flex flex-col gap-4" action="{{ route('user.store') }}" method="POST">
                    @csrf
                    
                    {{-- Display all validation errors --}}
                    @if ($errors->any())
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <ul class="text-red-700 dark:text-red-300 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <label class="flex flex-col gap-1.5">
                        <p class="text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal">Full Name</p>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#616f89] dark:text-[#9ca3af]">person</span>
                            <input 
                                id="nameInput"
                                name="name"
                                class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border @error('name') border-red-500 @else border-[#dbdfe6] dark:border-[#4a5568] @enderror focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-[#64748b] pl-11 pr-4 text-base font-normal leading-normal transition-all" 
                                placeholder="Enter your full name" 
                                maxlength="255"
                                required 
                                value="{{ old('name') }}"
                                oninput="validateForm()"
                            />
                        </div>
                        @error('name')
                            <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-1.5">
                        <p class="text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal">University Email</p>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#616f89] dark:text-[#9ca3af]">mail</span>
                            <input 
                                id="emailInput"
                                name="email"
                                class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border @error('email') border-red-500 @else border-[#dbdfe6] dark:border-[#4a5568] @enderror focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-[#64748b] pl-11 pr-4 text-base font-normal leading-normal transition-all" 
                                placeholder="user@student.tarc.edu.my" 
                                type="email" 
                                required 
                                value="{{ old('email') }}"
                                oninput="validateForm()"
                            />
                        </div>
                        @error('email')
                            <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-1.5">
                        <p class="text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal">Password</p>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#616f89] dark:text-[#9ca3af]">lock</span>
                            <input 
                                id="passwordInput"
                                name="password"
                                class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border @error('password') border-red-500 @else border-[#dbdfe6] dark:border-[#4a5568] @enderror focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-[#64748b] pl-11 pr-11 text-base font-normal leading-normal transition-all" 
                                minlength="8" 
                                maxlength="255"
                                placeholder="At least 8 characters" 
                                type="password" 
                                required 
                                oninput="validateForm()"
                            />
                            <button class="absolute right-3 top-3 text-[#616f89] dark:text-[#9ca3af] hover:text-primary transition-colors" type="button" onclick="togglePasswordVisibility(this)">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-1.5">
                        <p class="text-[#111318] dark:text-gray-200 text-sm font-bold leading-normal">Confirm Password</p>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#616f89] dark:text-[#9ca3af]">lock</span>
                            <input 
                                id="confirmPasswordInput"
                                name="password_confirmation"
                                class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#111318] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] dark:border-[#4a5568] focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-[#64748b] pl-11 pr-11 text-base font-normal leading-normal transition-all" 
                                minlength="8" 
                                maxlength="255"
                                placeholder="Confirm your password" 
                                type="password" 
                                required 
                                oninput="validateForm()"
                            />
                            <button class="absolute right-3 top-3 text-[#616f89] dark:text-[#9ca3af] hover:text-primary transition-colors" type="button" onclick="toggleConfirmPasswordVisibility(this)">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 mt-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input 
                                id="termsCheckbox"
                                class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-[#dbdfe6] dark:border-[#4a5568] shadow transition-all checked:border-primary checked:bg-primary hover:shadow-md" 
                                type="checkbox" 
                                required
                                onchange="validateForm()"
                            />
                            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 material-symbols-outlined text-base font-bold pointer-events-none">check</span>
                        </div>
                        <p class="text-[#616f89] dark:text-[#9ca3af] text-sm leading-tight pt-0.5">
                            I agree to the <a class="text-primary hover:underline font-medium" href="#">Terms of Service</a> and <a class="text-primary hover:underline font-medium" href="#">Privacy Policy</a>.
                        </p>
                    </label>
                    <input id="submitBtn" class="mt-4 flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary hover:bg-blue-600 text-white text-base font-bold leading-normal tracking-[0.015em] transition-colors shadow-lg shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary" type="submit" value="Create Account" disabled />
                </form>
            </div>
        </div>
        <div class="hidden lg:flex flex-1 relative bg-background-light dark:bg-background-dark items-center justify-center p-8 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img alt="University Campus" class="w-full h-full object-cover opacity-90" data-alt="University campus students walking near library building" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_xmZ790-yd2r1rIjtyN9E-VDHRXYn2RBEoApMJjiR4vR0FJUD4TbQMXpb7EMD7SvxouULboKJ8akoiCy4wsqS0RQZZ6tikcBlExJ4m_6Wo7Hv1gJNuVDteC69TiJeW3I_CvKZSInZQcmhwNNdz-1vv7SgNEjVhUo08z8_srJgIW8snN0zK4x1GbtxERTynr-iu-GQ1syeOdPtVPXhjEtVF8rpDmlTPwtLAQAR__5UmFVuglST4Gmmg9sijIWOUcqn6kh_oBlTpm-1" />
                <div class="absolute inset-0 bg-gradient-to-br from-primary/90 to-blue-900/90 mix-blend-multiply"></div>
            </div>
            <div class="relative z-10 max-w-md text-white p-8">
                <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 text-white shadow-xl">
                    <span class="material-symbols-outlined text-4xl">event_available</span>
                </div>
                <h2 class="text-4xl font-black mb-6 leading-tight tracking-tight">Join the vibrant campus life.</h2>
                <div class="space-y-6">
                    <div class="flex gap-4 items-start">
                        <div class="mt-1 h-8 w-8 flex items-center justify-center rounded-full bg-blue-400/30 text-white">
                            <span class="material-symbols-outlined text-lg">groups</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Connect with Societies</h3>
                            <p class="text-blue-100 leading-relaxed text-sm">Find and join over 200+ student-led societies ranging from robotics to debating.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="mt-1 h-8 w-8 flex items-center justify-center rounded-full bg-blue-400/30 text-white">
                            <span class="material-symbols-outlined text-lg">calendar_month</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Never Miss an Event</h3>
                            <p class="text-blue-100 leading-relaxed text-sm">Get personalized notifications for workshops, seminars, and social gatherings.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="mt-1 h-8 w-8 flex items-center justify-center rounded-full bg-blue-400/30 text-white">
                            <span class="material-symbols-outlined text-lg">verified_user</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Official University Platform</h3>
                            <p class="text-blue-100 leading-relaxed text-sm">Securely integrated with your university credentials for seamless access.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-white/20">
                    <p class="text-lg italic font-medium text-white/90">"UniEvent Manager made it so easy to find the coding club during my first week. It's essential!"</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-blue-300 overflow-hidden border-2 border-white/50">
                            <img alt="Student" class="h-full w-full object-cover" data-alt="Female student portrait smiling" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAo_lIJgfZes3BgtdovUiSzFl1WbeBk8DJJVBgR6mrOyT916qMo5ji-swNxzZ-_PiC1nbwBwjjZXiG27bgrqAdWwTlmYS2DnLO2gxRklQseDYV53QcKOrQ_AjBEdU3DjBRxO1g115zdoNjuRW1cJKG71J3GpuZCRBVgZu7rmPFY0loH5wtqAgOlmCjqyFNjKB_n3OZkpAGIF9y2SkwB_yqSpLmylRGiyUAwrL9yh6vFZZrR60IRnq3dF_HdAty2gU3ghBLD5qs7ayLj" />
                        </div>
                        <div>
                            <p class="font-bold text-sm">Sarah Jenkins</p>
                            <p class="text-xs text-blue-200 uppercase tracking-wider font-semibold">Computer Science, Class of '25</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>