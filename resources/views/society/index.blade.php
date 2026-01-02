@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 md:px-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8">
        <div class="flex items-center gap-3 mb-4 md:mb-0">
            <span class="material-symbols-outlined text-primary text-4xl">groups</span>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#111318] dark:text-white">Societies</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">{{ $societies->count() }} societies available</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="mb-8 flex flex-col md:flex-row gap-4">
        <input 
            type="text" 
            id="searchBox"
            placeholder="Search societies..." 
            class="flex h-10 flex-1 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-2 text-sm font-normal text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
        >
        <select id="filterType" class="flex h-10 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-2 text-sm font-normal text-[#111318] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            <option value="">All Types</option>
            <option value="open">Open</option>
            <option value="approval">Approval Required</option>
            <option value="closed">Closed</option>
        </select>
    </div>

    <!-- Societies Grid -->
    @if($societies->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="societiesContainer">
            @foreach($societies as $society)
                <div class="society-card flex flex-col rounded-xl overflow-hidden bg-white dark:bg-[#1a202c] shadow-[0_2px_8px_rgba(0,0,0,0.08)] dark:shadow-none dark:border dark:border-[#2a3441] hover:shadow-lg transition-shadow group" data-name="{{ strtolower($society->societyName) }}" data-type="{{ $society->joinType }}">
                    <!-- Card Header with Photo or Gradient -->
                    @if($society->societyPhotoPath)
                        <div class="h-40 w-full bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $society->societyPhotoPath) }}');">
                            <div class="h-full w-full bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-primary to-blue-700 p-6 text-white h-40 flex items-center">
                            <span class="material-symbols-outlined text-6xl opacity-80">group_add</span>
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
                                {{ $society->members->count() }} member{{ $society->members->count() !== 1 ? 's' : '' }}
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
                    </div>

                    <!-- Card Footer - Actions -->
                    <div class="flex gap-2 p-4 bg-[#f6f8fa] dark:bg-[#0f1419] border-t border-[#e5e7eb] dark:border-[#2a3441]">
                        @if($society->joinType === 'closed')
                            <button disabled class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-gray-300 dark:bg-gray-700 text-white text-sm font-semibold cursor-not-allowed opacity-50">
                                <span class="material-symbols-outlined text-sm">lock</span>
                                Closed
                            </button>
                        @elseif($society->joinType === 'open')
                            @php
                                $userMembership = $society->members->where('userID', auth()->id())->first();
                            @endphp
                            @if(auth()->check() && $userMembership)
                                <button disabled class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-gray-300 dark:bg-gray-700 text-white text-sm font-semibold cursor-not-allowed opacity-50">
                                    <span class="material-symbols-outlined text-sm">done</span>
                                    Member
                                </button>
                            @else
                                <form action="{{ route('society.directJoin', $society->societyID) }}" method="POST" class="flex-1 direct-join-form">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 h-9 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-semibold transition-colors direct-join-btn" data-society-id="{{ $society->societyID }}">
                                        <span class="material-symbols-outlined text-sm">person_add</span>
                                        Join
                                    </button>
                                </form>
                            @endif
                        @else
                            @if(auth()->check() && in_array($society->societyID, $declinedRequestSocietyIds))
                                <button disabled class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm font-semibold cursor-not-allowed opacity-75">
                                    <span class="material-symbols-outlined text-sm">cancel</span>
                                    Declined
                                </button>
                            @elseif(auth()->check() && in_array($society->societyID, $pendingRequestSocietyIds))
                                <button disabled class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm font-semibold cursor-not-allowed opacity-75">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    Pending
                                </button>
                            @else
                                <form action="{{ route('society.requestJoin', $society->societyID) }}" method="POST" class="flex-1 request-join-form">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 h-9 rounded-lg bg-primary hover:bg-blue-700 text-white text-sm font-semibold transition-colors request-btn" data-society-id="{{ $society->societyID }}">
                                        <span class="material-symbols-outlined text-sm">person_add</span>
                                        Request Join
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-[#1a202c] rounded-xl border border-[#e5e7eb] dark:border-[#2a3441]">
            <span class="material-symbols-outlined text-6xl text-[#d0d7de] dark:text-[#2a3441] mb-4">groups</span>
            <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-2">No Societies Yet</h3>
            <p class="text-sm text-[#616f89] dark:text-gray-400">Start creating societies to see them here</p>
        </div>
    @endif
</div>

<script>
    const searchBox = document.getElementById('searchBox');
    const filterType = document.getElementById('filterType');
    const societyCards = document.querySelectorAll('.society-card');

    function filterSocieties() {
        const searchTerm = searchBox.value.toLowerCase();
        const selectedType = filterType.value;

        societyCards.forEach(card => {
            const name = card.dataset.name;
            const type = card.dataset.type;

            const matchesSearch = name.includes(searchTerm);
            const matchesType = selectedType === '' || type === selectedType;

            card.style.display = (matchesSearch && matchesType) ? '' : 'none';
        });
    }

    searchBox.addEventListener('input', filterSocieties);
    filterType.addEventListener('change', filterSocieties);

    // Handle request join button state change
    document.querySelectorAll('.request-join-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.request-btn');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">schedule</span><span>Pending</span>';
            btn.classList.remove('bg-primary', 'hover:bg-blue-700', 'text-white');
            btn.classList.add('bg-amber-100', 'dark:bg-amber-900/30', 'text-amber-700', 'dark:text-amber-400', 'opacity-75', 'cursor-not-allowed');
        });
    });
</script>
@endsection
