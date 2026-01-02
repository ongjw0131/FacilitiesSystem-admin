@include('head')
@section('title', 'Profile Settings - University Event Manager')

<div class="flex-1 max-w-[1440px] mx-auto w-full px-4 md:px-6 py-8 flex flex-col lg:flex-row gap-8">
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
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="{{ route('profile.show') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p class="text-sm font-medium leading-normal">Overview</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="#">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <p class="text-sm font-medium leading-normal">My Events</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616f89] dark:text-slate-400 hover:bg-[#f0f2f4] dark:hover:bg-slate-800 transition-all" href="#">
                    <span class="material-symbols-outlined">groups</span>
                    <p class="text-sm font-medium leading-normal">Societies</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-all hover:bg-primary/20" href="#">
                    <span class="material-symbols-outlined icon-fill">settings</span>
                    <p class="text-sm font-bold leading-normal">Settings</p>
                </a>
            </div>
            
            <div class="w-full mt-6">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                <button class="flex w-full items-center justify-center gap-2 rounded-lg h-10 px-4 bg-white dark:bg-transparent border border-[#dbdfe6] dark:border-slate-600 text-[#111318] dark:text-slate-300 text-sm font-bold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    <span>Log Out</span>
                </button>
            </form>
            </div>
        </div>
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
    <main class="flex-1 flex flex-col gap-6 overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-[#111318] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Profile Settings</h2>
                <p class="text-[#616f89] dark:text-slate-400 text-base font-normal">Manage your profile picture, personal information, and contact details.</p>
            </div>
        </div>
        <div class="border-b border-[#dbdfe6] dark:border-slate-700">
            <div class="flex gap-8 overflow-x-auto no-scrollbar">
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="{{ route('profile.show') }}">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Overview</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="#">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">My Events</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-transparent text-[#616f89] dark:text-slate-400 hover:text-[#111318] dark:hover:text-slate-200 pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="#">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Societies</p>
                </a>
                <a class="flex items-center justify-center border-b-[3px] border-b-primary text-primary pb-3 pt-2 px-1 whitespace-nowrap transition-colors" href="#">
                    <p class="text-sm font-bold leading-normal tracking-[0.015em]">Settings</p>
                </a>
            </div>
        </div>
        <div class="flex flex-col gap-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-6">
                
                    <div class="flex flex-col md:flex-row gap-6 md:items-center">
                        @if (is_null($user->profile_picture_file_path))
                        <div class="relative shrink-0">
                            <div id="previewContainer" class="size-24 rounded-full bg-cover bg-center border-4 border-[#f0f2f4] dark:border-slate-700" data-alt="Current profile picture of Alex Rivera" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC_W6kqmFckyjW9lvBlGpXrV0fF5jKtdxdxKg6Ggex_3JnJxz_AgK_QudCJKuC8Y0dMaGAF4tll9K8m9k5r0O5oV-9ui3PtRHFBRYtLoLRQzDUFHYQzlV0jkkNDvwDGeNCP17SuZIZHSwu38Qg5iSmAVLl-Qgerqi8iuFO-m3qgGVGmf4_wBedr6fGodVxWlGWeLRklcDl4pyj0guIEl_JLRpWtEY2Rx00fEq7ptbum-ppk9W7MQKMrIXyAx0kXJJ8Ee_rjoM1-TIpG");'></div>
                            <div class="absolute -bottom-1 -right-1 bg-white dark:bg-surface-dark rounded-full p-1 border dark:border-slate-600 shadow-sm">
                                <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            </div>
                        </div>
                        @else
                        <div class="relative shrink-0">
                            <img id="previewContainer" src="{{ asset('storage/' . $user->profile_picture_file_path) }}" class="size-24 rounded-full bg-cover bg-center border-4 border-[#f0f2f4] dark:border-slate-700" alt="Profile picture" />
                            <div class="absolute -bottom-1 -right-1 bg-white dark:bg-surface-dark rounded-full p-1 border dark:border-slate-600 shadow-sm">
                                <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            </div>
                        </div>
                        @endif
                        <div class="flex flex-col gap-2">
                            <h3 class="text-[#111318] dark:text-white text-lg font-bold">Profile Picture</h3>
                            <p class="text-[#616f89] dark:text-slate-400 text-sm">Update your photo. Accepted formats: JPG, PNG. Max size: 5MB.</p>
                            <div class="flex gap-3 mt-2">
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" />
                                <button type="button" onclick="document.getElementById('avatar').click()" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-bold shadow-sm shadow-blue-200 dark:shadow-none hover:bg-blue-700 transition-colors">Upload New Photo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="bg-white dark:bg-surface-dark rounded-xl border border-[#dbdfe6] dark:border-slate-700 p-6">
                    <h3 class="text-[#111318] dark:text-white text-lg font-bold mb-6">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Full Name</label>
                            <input class="form-input rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#111318] dark:text-white focus:ring-primary focus:border-primary placeholder:text-gray-400" type="text" name="name" value="{{ $user->name }}" maxlength="255" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Phone Number</label>
                            <input class="form-input rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#111318] dark:text-white focus:ring-primary focus:border-primary placeholder:text-gray-400" type="tel" name="contact_number" value="{{ $user->contact_number ?? ''}}" minlength="10" maxlength="11" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Email Address</label>
                            <input class="form-input rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-[#f6f6f8] dark:bg-slate-900 text-[#616f89] dark:text-slate-400 cursor-not-allowed" disabled="" type="email" value="{{ $user->email }}" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Role</label>
                            <input class="form-input rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-[#f6f6f8] dark:bg-slate-900 text-[#616f89] dark:text-slate-400 cursor-not-allowed" disabled="" type="text" value="{{ $user->role }}" />
                        </div>
                    </div>
                    <div class="w-full h-px bg-[#f0f2f4] dark:bg-slate-700 my-8"></div>
                    <h3 class="text-[#111318] dark:text-white text-lg font-bold mb-6">Academic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Major</label>
                            <input class="form-input rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#111318] dark:text-white focus:ring-primary focus:border-primary" type="text" name="major" value="{{ $user->major ?? '' }}" maxlength="255" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-[#111318] dark:text-white">Year of Graduation</label>
                            <select class="form-select rounded-lg border-[#dbdfe6] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#111318] dark:text-white focus:ring-primary focus:border-primary" name="year_of_graduation">
                                <option value="">-- Select Year --</option>
                                <option value="2026" @if($user->year_of_graduation == 2026) selected @endif>2026</option>
                                <option value="2027" @if($user->year_of_graduation == 2027) selected @endif>2027</option>
                                <option value="2028" @if($user->year_of_graduation == 2028) selected @endif>2028</option>
                                <option value="2029" @if($user->year_of_graduation == 2029) selected @endif>2029</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-8 gap-3">
                        <button type="submit" class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-bold shadow-sm shadow-blue-200 dark:shadow-none hover:bg-blue-700 transition-colors">Save Changes</button>
                    </div>
                </div>
            </form>
            
        </div>
    </main>
</div>

<script>
    // Image preview before upload
    const avatarInput = document.getElementById('avatar');
    const previewContainer = document.getElementById('previewContainer');

    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        console.log('File selected:', file);
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Update preview image/div
                if (previewContainer.tagName === 'IMG') {
                    previewContainer.src = e.target.result;
                } else {
                    previewContainer.style.backgroundImage = `url('${e.target.result}')`;
                }
                console.log('Preview updated');
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Debug form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            console.log('Avatar input value:', avatarInput.files.length);
            console.log('Form data:', new FormData(form));
        });
    }
</script>

@include('foot')