@extends('layouts.app')

@section('title', 'Facilities - Admin')

@section('content')
    @php
        $isAdmin = auth()->user()?->role === 'admin';
        $tableColumns = $isAdmin ? 6 : 5;
    @endphp
    <div class="w-full max-w-6xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Facilities</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Manage facility records and activation state.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-primary px-4 py-2 text-primary text-sm font-semibold shadow-sm hover:bg-blue-50 transition-colors">
                    <span class="material-symbols-outlined text-base">event_available</span>
                    View Booking
                </a>
                @if ($isAdmin)
                    <a href="{{ route('admin.facilities.create') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-white text-sm font-semibold shadow hover:bg-blue-700 transition-colors">
                        <span class="material-symbols-outlined text-base">add</span>
                        Add Facility
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-left">
                <thead class="bg-[#f9fafb] dark:bg-[#111827] text-xs uppercase text-[#616f89] dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Location</th>
                        <th class="px-6 py-3">Capacity</th>
                        <th class="px-6 py-3">Status</th>
                        @if ($isAdmin)
                            <th class="px-6 py-3 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f2f4] dark:divide-[#2a3441]">
                    @forelse ($facilities as $facility)
                        <tr class="text-sm text-[#111318] dark:text-gray-100">
                            <td class="px-6 py-4 font-semibold">{{ $facility->name }}</td>
                            <td class="px-6 py-4">{{ $facility->type }}</td>
                            <td class="px-6 py-4">{{ $facility->location }}</td>
                            <td class="px-6 py-4">{{ $facility->capacity }}</td>
                            <td class="px-6 py-4">
                                @if ($facility->active_count > 0)
                                    <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 text-xs font-semibold px-3 py-1">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            @if ($isAdmin)
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if ($facility->active_count > 0)
                                        <a href="{{ route('admin.facilities.show', $facility->representative_id) }}" class="text-primary hover:underline text-sm">View</a>
                                    @else
                                        <span class="text-gray-400 text-sm cursor-not-allowed">View</span>
                                    @endif
                                    <a href="{{ route('admin.facilities.edit', $facility->representative_id) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    @if ($facility->active_count > 0)
                                        <form action="{{ route('admin.facilities.destroy', $facility->representative_id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Deactivate this facility?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm">Deactivate</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tableColumns }}" class="px-6 py-6 text-center text-[#616f89]">No facilities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $facilities->links() }}
            </div>
        </div>
    </div>
@endsection
