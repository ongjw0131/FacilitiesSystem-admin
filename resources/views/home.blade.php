@include('head')
@section('title', 'Home - University Event Manager')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col items-center">
            <!-- Hero Section -->
            <section class="w-full max-w-[1280px] px-4 md:px-10 py-5">
                <div class="@container">
                    <div class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat rounded-xl items-center justify-center p-8 relative overflow-hidden" data-alt="Group of diverse university students studying together on a sunny campus lawn" style='background-image: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuDzMYl4anPFLzxq0xWfS02RiIXeWH4MYKv64UFCYs2cibZSwn3FuUXJGVSaG28P-XMrODQP2zwdGq9Y9ewF6ZrPGi3LDc3cBRwNTLWWxCd7iouka5TmgCgiH-Qfalo-yEUIQ-NAaC4LY3A93cjv6Zbhl6troRWRqXXyBOZ2YwDanVmGQ1aJgPfyKOQFysNHC-z_gCStWMoLNtpujLvjJYu9t9ZbcA59hfnixIGeXDkRORwl976W4oZG95cGfDKc6tZlX5KcSQ1x5ME4");'>
                        <div class="flex flex-col gap-4 text-center z-10 max-w-3xl">
                            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-[-0.033em]">
                                Discover Your Campus Community
                            </h1>
                            <h2 class="text-gray-200 text-base md:text-lg font-normal leading-relaxed max-w-2xl mx-auto">
                                Join societies, attend events, and make the most of your university life. Find your people and your passion.
                            </h2>
                        </div>
                        <label class="flex flex-col w-full max-w-[560px] h-14 md:h-16 z-10 mt-4">
                            <div class="flex w-full flex-1 items-stretch rounded-lg h-full shadow-lg">
                                <div class="text-[#616f89] flex bg-white items-center justify-center pl-4 rounded-l-lg border-r-0">
                                    <span class="material-symbols-outlined text-[20px]">search</span>
                                </div>
                                <input class="flex w-full min-w-0 flex-1 resize-none overflow-hidden text-[#111318] focus:outline-0 focus:ring-0 bg-white h-full placeholder:text-[#616f89] px-4 text-base font-normal leading-normal" placeholder="Search events or societies..." value="" />
                                <div class="flex items-center justify-center rounded-r-lg bg-white pr-2">
                                    <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 md:h-12 bg-primary hover:bg-blue-700 text-white text-sm md:text-base font-bold leading-normal tracking-[0.015em] transition-colors">
                                        <span class="truncate">Find Events</span>
                                    </button>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </section>
            <!-- Upcoming Events Section -->
            <section class="w-full max-w-[1280px] px-4 md:px-10 py-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[#111318] dark:text-white text-[28px] font-bold leading-tight tracking-[-0.015em]">Upcoming Events</h2>
                    <a class="text-primary font-medium hover:underline flex items-center gap-1" href="#">
                        View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Event Card 1 -->
                    <div class="flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] group hover:shadow-lg transition-shadow">
                        <div class="w-full aspect-video bg-cover bg-center" data-alt="Large auditorium stage with spotlights during a tech conference" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAPOy3-9pQS6aKwz88wZ8OTk-yXIT1YnlyWJ07-rIIpRLocAejfvMW8r79nrGp___HVUk0nkPpfDXUQpPVHrjBjafiOXgPgsvBu-7xnwbFhhYs4K9FJsMm32U21CHVZ83cJ1lx7QOioxWqZmr1nUxm1qIGuQwcqxrtjPF4gI0OUcHo1_jO1-XREyI5fD_2DPgnlCBIEH8tj3VKxosbrhMy9G1Zo3FjiYzxstagrD6fVISSPeFbv5xiuPbbzsOZP6jdAh7iUbHlBolnx");'>
                            <div class="w-full h-full bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                        </div>
                        <div class="flex flex-col p-5 gap-3">
                            <div class="flex justify-between items-start">
                                <p class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Tech Summit 2024</p>
                                <span class="bg-blue-100 text-primary text-xs font-bold px-2 py-1 rounded">OCT 15</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#616f89] dark:text-gray-400 text-sm">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                                <span>Main Auditorium</span>
                            </div>
                            <p class="text-[#616f89] dark:text-gray-400 text-sm leading-normal line-clamp-2">
                                Join us for the biggest tech gathering of the year featuring guest speakers from major tech companies.
                            </p>
                            <div class="mt-auto pt-2">
                                <button class="w-full cursor-pointer items-center justify-center rounded-lg h-10 bg-primary/10 hover:bg-primary/20 dark:bg-white/10 dark:hover:bg-white/20 text-primary dark:text-white text-sm font-bold leading-normal transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Event Card 2 -->
                    <div class="flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] group hover:shadow-lg transition-shadow">
                        <div class="w-full aspect-video bg-cover bg-center" data-alt="Musician playing acoustic guitar on a dimly lit stage" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDFs7VKBh-bV71WS5WhNnP7sR2W766VYt6w8yWFde17q6mtDWl2BCXWs65Hqa2-m0_YvuEg1pGvFugoyL8HJkt5dTMGXoHcas0WSag5d6Pe3hQYLZfnwAdCoqcV-7yHz0MaIwlVqwpkOYdZuADT55qUDR9thHyCEiAKrmvYIJ6J6VQ9cAGPWrS3nzumWbzjCMlyLd-KnOmk94Fdq12e2YOLUxXo94uTaSvXLCC-GpeIqC7GkrGIEw852mEm7QSQn0C_duTnEYH08WSI");'>
                            <div class="w-full h-full bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                        </div>
                        <div class="flex flex-col p-5 gap-3">
                            <div class="flex justify-between items-start">
                                <p class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Music Society Open Mic</p>
                                <span class="bg-blue-100 text-primary text-xs font-bold px-2 py-1 rounded">OCT 18</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#616f89] dark:text-gray-400 text-sm">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                                <span>Student Union Bar</span>
                            </div>
                            <p class="text-[#616f89] dark:text-gray-400 text-sm leading-normal line-clamp-2">
                                Showcase your talent or enjoy performances by your fellow students. All genres welcome.
                            </p>
                            <div class="mt-auto pt-2">
                                <button class="w-full cursor-pointer items-center justify-center rounded-lg h-10 bg-primary/10 hover:bg-primary/20 dark:bg-white/10 dark:hover:bg-white/20 text-primary dark:text-white text-sm font-bold leading-normal transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Event Card 3 -->
                    <div class="flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] group hover:shadow-lg transition-shadow">
                        <div class="w-full aspect-video bg-cover bg-center" data-alt="Abstract colorful painting supplies and canvas texture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAvRtAyQYWMr0NLJbbDq5LTuQawY5BPOK3soBSgUr_Mmq0oiUOZ9CJqqB_1YnA2jWCJb-X5Ezfoj3-4HFSa9dMSwB7sBR6-aNT7DOJ0wDT3o_Xo5pOwIR4LKXkj8Rhgc7vFDIBg9QO8QXuisBJrMakQ5a1Fhzg1AWzugmlRVOsByRRfmc7lS0_lQm32h5frolyQEuiE-y46r4A1ixQag4DxRa-FWF2jD0d2YNX0ETxMGl2CBFqheQlbcyP-fFhOPgBmponTbugaVCB9");'>
                            <div class="w-full h-full bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                        </div>
                        <div class="flex flex-col p-5 gap-3">
                            <div class="flex justify-between items-start">
                                <p class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Fine Arts Exhibition</p>
                                <span class="bg-blue-100 text-primary text-xs font-bold px-2 py-1 rounded">OCT 22</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#616f89] dark:text-gray-400 text-sm">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                                <span>Gallery Hall A</span>
                            </div>
                            <p class="text-[#616f89] dark:text-gray-400 text-sm leading-normal line-clamp-2">
                                A curated display of the finest student artwork from this semester. Wine and cheese reception included.
                            </p>
                            <div class="mt-auto pt-2">
                                <button class="w-full cursor-pointer items-center justify-center rounded-lg h-10 bg-primary/10 hover:bg-primary/20 dark:bg-white/10 dark:hover:bg-white/20 text-primary dark:text-white text-sm font-bold leading-normal transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Featured Societies Section -->
            <section class="w-full max-w-[1280px] px-4 md:px-10 py-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[#111318] dark:text-white text-[28px] font-bold leading-tight tracking-[-0.015em]">Featured Societies</h2>
                    <a class="text-primary font-medium hover:underline flex items-center gap-1" href="#">
                        Explore All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Society Card 1 -->
                    <a class="flex flex-col items-center justify-center p-6 bg-white dark:bg-[#1a202c] rounded-xl border border-transparent hover:border-primary/30 hover:shadow-md transition-all group text-center gap-3" href="#">
                        <div class="w-16 h-16 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-3xl mb-1 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">terminal</span>
                        </div>
                        <h3 class="text-[#111318] dark:text-white font-bold text-lg">Coding Club</h3>
                        <p class="text-[#616f89] dark:text-gray-400 text-xs">1.2k Members</p>
                    </a>
                    <!-- Society Card 2 -->
                    <a class="flex flex-col items-center justify-center p-6 bg-white dark:bg-[#1a202c] rounded-xl border border-transparent hover:border-primary/30 hover:shadow-md transition-all group text-center gap-3" href="#">
                        <div class="w-16 h-16 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-3xl mb-1 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">theater_comedy</span>
                        </div>
                        <h3 class="text-[#111318] dark:text-white font-bold text-lg">Drama Society</h3>
                        <p class="text-[#616f89] dark:text-gray-400 text-xs">850 Members</p>
                    </a>
                    <!-- Society Card 3 -->
                    <a class="flex flex-col items-center justify-center p-6 bg-white dark:bg-[#1a202c] rounded-xl border border-transparent hover:border-primary/30 hover:shadow-md transition-all group text-center gap-3" href="#">
                        <div class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-3xl mb-1 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">eco</span>
                        </div>
                        <h3 class="text-[#111318] dark:text-white font-bold text-lg">Eco Warriors</h3>
                        <p class="text-[#616f89] dark:text-gray-400 text-xs">2.5k Members</p>
                    </a>
                    <!-- Society Card 4 -->
                    <a class="flex flex-col items-center justify-center p-6 bg-white dark:bg-[#1a202c] rounded-xl border border-transparent hover:border-primary/30 hover:shadow-md transition-all group text-center gap-3" href="#">
                        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-3xl mb-1 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">sports_basketball</span>
                        </div>
                        <h3 class="text-[#111318] dark:text-white font-bold text-lg">Sports Union</h3>
                        <p class="text-[#616f89] dark:text-gray-400 text-xs">5k+ Members</p>
                    </a>
                </div>
            </section>
        </main>
        
@include('foot')