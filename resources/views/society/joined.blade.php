@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 md:px-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8">
        <div class="flex items-center gap-3 mb-4 md:mb-0">
            <span class="material-symbols-outlined text-primary text-4xl">favorite</span>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#111318] dark:text-white">My Societies</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">{{ $societies->count() }} society/societies joined</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="mb-8 flex flex-col md:flex-row gap-4">
        <input 
            type="text" 
            id="searchBox"
            placeholder="Search your societies..." 
            class="flex h-10 flex-1 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-2 text-sm font-normal text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
    </div>

    <!-- Societies Grid -->
    @if($societies->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="societiesContainer">
            @foreach($societies as $society)
                <div class="society-card flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] hover:shadow-lg transition-shadow group" data-name="{{ strtolower($society->societyName) }}">
                    <!-- Card Header with Photo or Gradient -->
                    @if($society->societyPhotoPath)
                        <div class="h-40 w-full bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $society->societyPhotoPath) }}');">
                            <div class="h-full w-full bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-primary to-blue-700 p-6 text-white h-40 flex items-center">
                            <span class="material-symbols-outlined text-6xl opacity-80">groups</span>
                        </div>
                    @endif

                    <!-- Card Content -->
                    <div class="flex flex-col flex-1 p-5 gap-3">
                        <h3 class="text-lg font-bold text-[#111318] dark:text-white line-clamp-2">{{ $society->societyName }}</h3>
                        
                        <p class="text-sm text-[#616f89] dark:text-gray-400 line-clamp-3">{{ $society->societyDescription }}</p>

                        <!-- Members Info -->
                        <div class="flex items-center gap-2 mt-2">
                            <span class="material-symbols-outlined text-sm text-primary">people</span>
                            <span class="text-sm text-[#616f89] dark:text-gray-400">
                                {{ $society->members->where('status', 'active')->count() }} member{{ $society->members->where('status', 'active')->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        <!-- President Info -->
                        @php
                            $president = $society->members->where('position', 'president')->first();
                        @endphp
                        @if($president)
                            <div class="flex items-center gap-2 pt-2 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                                <span class="material-symbols-outlined text-sm text-primary">admin_panel_settings</span>
                                <span class="text-xs text-[#616f89] dark:text-gray-400">
                                    President: <strong>{{ $president->user->name ?? 'N/A' }}</strong>
                                </span>
                            </div>
                        @endif

                        <!-- Your Role -->
                        @php
                            $userRole = $society->members->where('userID', auth()->id())->first();
                        @endphp
                        @if($userRole)
                            <div class="flex items-center gap-2 pt-2 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                                <span class="material-symbols-outlined text-sm text-blue-500">badge</span>
                                <span class="text-xs text-[#616f89] dark:text-gray-400">
                                    Your role: <strong class="text-primary capitalize">{{ $userRole->position }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer - Actions -->
                    <div class="flex gap-2 p-4 bg-[#f6f8fa] dark:bg-[#0f1419] border-t border-[#e5e7eb] dark:border-[#2a3441]">
                        <a href="{{ route('society.show', $society->societyID) }}" class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-semibold transition-colors">
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            const searchBox = document.getElementById('searchBox');
            const societyCards = document.querySelectorAll('.society-card');

            searchBox.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();

                societyCards.forEach(card => {
                    const societyName = card.getAttribute('data-name');
                    if (societyName.includes(searchTerm)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        </script>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-700 mb-4">groups</span>
            <h2 class="text-2xl font-bold text-[#111318] dark:text-white mb-2">No Societies Joined Yet</h2>
            <p class="text-[#616f89] dark:text-gray-400 text-center mb-6 max-w-md">
                You haven't joined any societies yet. Start exploring and join societies to stay connected with your community!
            </p>
            <a href="{{ route('society.index') }}" class="flex items-center justify-center gap-2 h-10 px-6 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-semibold transition-colors">
                <span class="material-symbols-outlined text-sm">add</span>
                Browse Societies
            </a>
        </div>
    @endif
</div>
@endsection
