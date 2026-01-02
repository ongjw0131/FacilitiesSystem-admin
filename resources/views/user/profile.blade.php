@include('head')
@section('title', 'User Profile - University Event Manager')

<!-- Main Page Layout -->
<div class="flex-1 max-w-[1440px] mx-auto w-full px-4 md:px-6 py-8 flex flex-col lg:flex-row gap-8">
    <!-- Left Sidebar (Profile Card) -->
    <aside class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
        <div class="bg-white dark:bg-surface-dark rounded-xl p-6 border border-[#dbdfe6] dark:border-slate-700 shadow-sm flex flex-col items-center text-center">
            <div class="relative mb-4">
                @if (is_null($user->profile_picture_file_path))
                <div class="relative shrink-0">
                    <div class="size-24 rounded-full bg-cover bg-center border-4 border-[#f0f2f4] dark:border-slate-700" data-alt="Current profile picture of Alex Rivera" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC_W6kqmFckyjW9lvBlGpXrV0fF5jKtdxdxKg6Ggex_3JnJxz_AgK_QudCJKuC8Y0dMaGAF4tll9K8m9k5r0O5oV-9ui3PtRHFBRYtLoLRQzDUFHYQzlV0jkkNDvwDGeNCP17SuZIZHSwu38Qg5iSmAVLl-Qgerqi8iuFO-m3qgGVGmf4_wBedr6fGodVxWlGWeLRklcDl4pyj0guIEl_JLRpWtEY2Rx00fEq7ptbum-ppk9W7MQKMrIXyAx0kXJJ8Ee_rjoM1-TIpG");'></div>
                    <div class="absolute -bottom-1 -right-1 bg-white dark:bg-surface-dark rounded-full p-1 border dark:border-slate-600 shadow-sm">
                        <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                    </div>
                </div>
                @else
                <div class="relative shrink-0">
                    <img src="{{ asset('storage/' . $user->profile_picture_file_path) }}" class="size-24 rounded-full bg-cover bg-center border-4 border-[#f0f2f4] dark:border-slate-700" alt="Profile picture" />
                    <div class="absolute -bottom-1 -right-1 bg-white dark:bg-surface-dark rounded-full p-1 border dark:border-slate-600 shadow-sm">
                        <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                    </div>
                </div>
                @endif
            </div>
            <h1 class="text-[#111318] dark:text-white text-xl font-bold leading-tight">{{ $user->name }}</h1>
            <p class="text-[#616f89] dark:text-slate-400 text-sm font-medium mt-1">{{ $user->major }} Major</p>
            <p class="text-[#616f89] dark:text-slate-400 text-xs mt-1">Class of {{ $user->year_of_graduation}}</p>
            <div class="flex gap-2 mt-4">
                <span class="px-2 py-1 rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold border border-green-200 dark:border-green-800">Student</span>
                <span class="px-2 py-1 rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold border border-blue-200 dark:border-blue-800">Society Manager</span>
            </div>
            <div class="w-full h-px bg-[#f0f2f4] dark:bg-slate-700 my-6"></div>
            <div class="w-full flex flex-col gap-2">
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-all hover:bg-primary/20" href="#">
                    <span class="material-symbols-outlined icon-fill">dashboard</span>
                    <p class="text-sm font-bold leading-normal">Overview</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="{{ route('president.events.index') }}">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <p class="text-sm font-medium leading-normal">Event Management</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="#">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <p class="text-sm font-medium leading-normal">My Events</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="{{ route('user.viewMyTickets') }}">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <p class="text-sm font-medium leading-normal">My Ticket</p>
                </a>

                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="#">
                    <span class="material-symbols-outlined">groups</span>
                    <p class="text-sm font-medium leading-normal">Societies</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="{{ route('profile.settings') }}">
                    <span class="material-symbols-outlined">settings</span>
                    <p class="text-sm font-medium leading-normal">Settings</p>
                </a>
            </div>
            <div class="w-full mt-6">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg h-10 px-4 bg-white dark:bg-transparent border border-[#dbdfe6] dark:border-slate-600 text-[#111318] dark:text-slate-300 text-sm font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
        <!-- Mini Contact Card -->
        <div class="bg-white dark:bg-surface-dark rounded-xl p-6 border border-[#dbdfe6] dark:border-slate-700 shadow-sm flex flex-col gap-4">
            <h3 class="text-sm font-bold text-[#111318] dark:text-white uppercase tracking-wider">Contact Info</h3>
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-[#616f89] dark:text-slate-500">
                    <span class="material-symbols-outlined text-[20px]">mail</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-[#616f89] dark:text-slate-400">Email</span>
                    <span class="text-sm font-medium text-[#111318] dark:text-slate-200 break-all">{{ $user->email }}</span>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-[#616f89] dark:text-slate-500">
                    <span class="material-symbols-outlined text-[20px]">call</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs text-[#616f89] dark:text-slate-400">Phone</span>
                    <span class="text-sm font-medium text-[#111318] dark:text-slate-200">{{ $user->contact_number }}</span>
                </div>
            </div>
        </div>
    </aside>
    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col gap-6 overflow-hidden">
        <!-- Page Heading & Actions -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-[#111318] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">My Profile</h2>
                <p class="text-[#616f89] dark:text-slate-400 text-base font-normal">Manage your personal information, event history, and society memberships.</p>
            </div>
            <div class="flex gap-3">
            </div>
        </div>
        <!-- Tabs -->
        <div class="border-b border-[#dbdfe6] dark:border-slate-700">
            <div class="flex gap-8 overflow-x-auto no-scrollbar">
                <a class="flex items-center justify-center border-b-[3px] border-b-primary text-primary pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="#">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Overview</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="{{ route('president.events.index') }}">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Event Management</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">My Events</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="{{ route('user.viewMyTickets') }}">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">My Ticket</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="#">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Societies</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="{{ route('profile.settings') }}">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Settings</p>
                </a>
            </div>
        </div>
        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-surface-dark rounded-xl p-5 border border-[#dbdfe6] dark:border-slate-700 shadow-sm flex flex-col gap-1">
                <div class="flex justify-between items-start">
                    <p class="text-[#616f89] dark:text-slate-400 text-sm font-medium leading-normal">Events Attended</p>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-1 rounded">event_available</span>
                </div>
                <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight mt-2">12</p>
                <p class="text-green-600 dark:text-green-400 text-xs font-medium mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> +2 this month
                </p>
            </div>
            <div class="bg-white dark:bg-surface-dark rounded-xl p-5 border border-[#dbdfe6] dark:border-slate-700 shadow-sm flex flex-col gap-1">
                <div class="flex justify-between items-start">
                    <p class="text-[#616f89] dark:text-slate-400 text-sm font-medium leading-normal">Societies Joined</p>
                    <span class="material-symbols-outlined text-purple-500 bg-purple-500/10 p-1 rounded">diversity_3</span>
                </div>
                <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight mt-2">3</p>
                <p class="text-[#616f89] dark:text-slate-500 text-xs font-medium mt-1">Active Member</p>
            </div>
            <div class="bg-white dark:bg-surface-dark rounded-xl p-5 border border-[#dbdfe6] dark:border-slate-700 shadow-sm flex flex-col gap-1">
                <div class="flex justify-between items-start">
                    <p class="text-[#616f89] dark:text-slate-400 text-sm font-medium leading-normal">Managed Events</p>
                    <span class="material-symbols-outlined text-orange-500 bg-orange-500/10 p-1 rounded">admin_panel_settings</span>
                </div>
                <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight mt-2">4</p>
                <p class="text-[#616f89] dark:text-slate-500 text-xs font-medium mt-1">Next: Hackathon 2024</p>
            </div>
        </div>

        <!-- Content Grid: Upcoming & Societies -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Column 1: Upcoming Events (Span 2) -->
            <div class="xl:col-span-2 flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-[#111318] dark:text-white text-lg font-bold">Upcoming Events</h3>
                    <a class="text-primary text-sm font-bold hover:underline" href="#">View All</a>
                </div>
                <!-- Event Card 1 -->
                <div class="group bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-4 flex flex-col sm:flex-row gap-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="w-full sm:w-32 h-32 sm:h-auto rounded-lg bg-cover bg-center shrink-0 relative overflow-hidden" data-alt="Abstract colorful geometric shapes representing technology" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBTHMOZdjibD61XkMnMTefxcEa-yf9ITJ0KF9GLh_ADO30c-C6SPBNZRq7zLQ_0IsU3nqFz4fQ-Uy29EZxbtsrf6B8PwvfNGkaZXHH6TnPmuRyTo1Wao3PjKdBLGAONt1N9FTYOzKgG1xCDtBc81GRKADqS6hJhsKLze4YHnhgmnFmNsf7F28ESXPD_HR9aLZFufGksnYr2n3NiAudtR0wsnDKzOFkExOjmhKVVXVCfWTUIgx-zpHRuh2W1yrJUpjfzpLp3BGGyghO0");'>
                        <div class="absolute top-2 left-2 bg-white/90 dark:bg-black/80 backdrop-blur-sm rounded-md px-2 py-1 flex flex-col items-center shadow-sm">
                            <span class="text-xs font-bold uppercase text-red-500">Nov</span>
                            <span class="text-lg font-black text-[#111318] dark:text-white leading-none">15</span>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 justify-between py-1">
                        <div>
                            <div class="flex justify-between items-start">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 uppercase tracking-wide mb-2">Technology</span>
                                <span class="px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Going
                                </span>
                            </div>
                            <h4 class="text-[#111318] dark:text-white text-lg font-bold leading-tight group-hover:text-primary transition-colors">Campus Hackathon 2024</h4>
                            <p class="text-[#616f89] dark:text-slate-400 text-sm mt-1 line-clamp-2">Join us for 48 hours of coding, innovation, and networking. Prizes include internships and tech gear.</p>
                        </div>
                        <div class="flex items-center gap-4 mt-3 text-sm text-[#616f89] dark:text-slate-500">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                <span>09:00 AM</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                <span>Engineering Hall A</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Event Card 2 -->
                <div class="group bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-4 flex flex-col sm:flex-row gap-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="w-full sm:w-32 h-32 sm:h-auto rounded-lg bg-cover bg-center shrink-0 relative overflow-hidden" data-alt="Group of students networking at a career fair" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnDD9UeowPKYN3ioVWEe1vfh7FpevqGX-TsIrpw5ptGtpqnq-GJgQyRPzAMGTa5Y_gI96xY3Z9ngC_uzXp7AQig_rV9uGIUJVr8EORLNL-33GpI62zg5vwaD0Hf_xcrP3kJ5A2zCmgxpLk_sOvuDVW6o6IL_eauQKLE5w1bX2dWg5z4KCIIt0wZtR1B4DPdpA81FKLMs6Qljihr5LMiPMBv8V8KCkRu6OVFfpOhUGfhGtffoDfy8UE-odQ9XS2DRXi2gTPFCXRo9lE");'>
                        <div class="absolute top-2 left-2 bg-white/90 dark:bg-black/80 backdrop-blur-sm rounded-md px-2 py-1 flex flex-col items-center shadow-sm">
                            <span class="text-xs font-bold uppercase text-red-500">Nov</span>
                            <span class="text-lg font-black text-[#111318] dark:text-white leading-none">22</span>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 justify-between py-1">
                        <div>
                            <div class="flex justify-between items-start">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 uppercase tracking-wide mb-2">Career</span>
                            </div>
                            <h4 class="text-[#111318] dark:text-white text-lg font-bold leading-tight group-hover:text-primary transition-colors">Autumn Career Fair</h4>
                            <p class="text-[#616f89] dark:text-slate-400 text-sm mt-1 line-clamp-2">Meet recruiters from top companies. Bring your resume and dress professionally.</p>
                        </div>
                        <div class="flex items-center gap-4 mt-3 text-sm text-[#616f89] dark:text-slate-500">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                <span>10:00 AM</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                <span>Student Center Main Hall</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column 2: My Societies (Span 1) -->
            <div class="flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-[#111318] dark:text-white text-lg font-bold">My Societies</h3>
                    <a class="text-primary text-sm font-bold hover:underline" href="#">Browse</a>
                </div>
                <div class="bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-4 flex flex-col gap-4">
                    <!-- Society Item 1 -->
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0f2f4] dark:border-slate-800 last:border-0 last:pb-0">
                        <div class="size-12 rounded-lg bg-cover bg-center shrink-0" data-alt="Logo for the robotics club showing a robot arm" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDiuq_NGP0rmCjYeN6SS4gjLar806afaXC6cvMzGr4r8CecX_i5Nmst65adZh8lfMsKSMfZJSaoKbe1wosiJJlM4lAx5XYXLyUqEf_KC4o6qr4GVKUZMEjtLbMWa7FxHpvdbmtBx3_5Rg6ocXz3kdIpS8lYvAm_szY3HqNL8cYSU13mj2GGSpuBRArZMkgoir7q_zwnSAhNR7xnx7rtRn7fiqVKRpPXVY97YfsedKgbsmc2zFc-yukRziE3gk9fP-tPqdjR-41fhowm");'></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-0.5">
                                <h5 class="text-[#111318] dark:text-white font-bold truncate">Robotics Club</h5>
                                <span class="px-1.5 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-500 text-[10px] font-bold rounded border border-yellow-200 dark:border-yellow-800">ADMIN</span>
                            </div>
                            <p class="text-[#616f89] dark:text-slate-400 text-xs">President • 45 Members</p>
                        </div>
                        <button class="text-[#616f89] dark:text-slate-500 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                    <!-- Society Item 2 -->
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0f2f4] dark:border-slate-800 last:border-0 last:pb-0">
                        <div class="size-12 rounded-lg bg-cover bg-center shrink-0" data-alt="Debate club concept image with microphone" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAIaqmOkHLHKHacAVZYnTzL5Z-OPEGxLZ86fEh06xaZBaWw5Y16DyVO86hK-kC7MaMoshm9-KIb0DpSn6dW5ce1NbhPumgzRA8W2ovwo6WGUMHAQ5rxZv1mf-CbL_a4UOyf1nLk__lB-z7-9InJtUJu2bQATLyZbVOoAkRjtwjiYYewu2JkiKqsEGp5PSn-YwFe6FmIj0AVwiBe8Nu0zCTW6XUMFqzcnp51JSohmkqGwKh-q8EiDPbOI4aHbZdusV4iKYDlm-at0WH8");'></div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-[#111318] dark:text-white font-bold truncate">Debate Society</h5>
                            <p class="text-[#616f89] dark:text-slate-400 text-xs">Member • 120 Members</p>
                        </div>
                        <button class="text-[#616f89] dark:text-slate-500 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                    <!-- Society Item 3 -->
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0f2f4] dark:border-slate-800 last:border-0 last:pb-0">
                        <div class="size-12 rounded-lg bg-cover bg-center shrink-0" data-alt="Photography club lens close up" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCnnhHrNsovpALF5shxEbAbtL2THWdDq3BMa0YGABUadJroF2oARxo9otbevDkY9opP7Ka1MbsKGbTC0F9Qzean82qhAowfXQMVhSvL12ZJCgM-v61XDSSh2CXkph8mFbhjcy7aKp4mMgHTzmSrJVaxloFt5y8hHiaiV-PhFWwUts13CfQ-Evq2SyKIUv0fkzPbrJU1P0uKhiPOk4RztpK23S1yWdECsnTfmqr-a9Yn3KQDr7sWhsIP-yGVkIQV9zI9CtmqszxlQZCZ");'></div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-[#111318] dark:text-white font-bold truncate">Photo Society</h5>
                            <p class="text-[#616f89] dark:text-slate-400 text-xs">Member • 88 Members</p>
                        </div>
                        <button class="text-[#616f89] dark:text-slate-500 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>


@include('foot')