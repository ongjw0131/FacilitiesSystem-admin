@extends('layouts.app')

@section('title', 'Create Facility')

@section('content')
    <div class="w-full max-w-4xl px-4 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Create Facility</h1>
                <p class="text-sm text-[#616f89] dark:text-gray-400">Add a new facility and set its availability.</p>
            </div>
            <a href="{{ route('admin.facilities.index') }}" class="text-primary hover:underline text-sm">Back to list</a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-[#1a202c] border border-[#f0f2f4] dark:border-[#2a3441] rounded-xl shadow-sm p-6 space-y-4">
            <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Venue Prefix (e.g. BB)</label>
                        <input type="text" name="venue_prefix" value="{{ old('venue_prefix') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                            placeholder="Letters/numbers only" />
                        <p class="text-xs text-[#616f89] mt-1">IDs will be generated as &lt;Prefix&gt;101, 102, ...</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Type</label>
                        <input type="text" name="type" value="{{ old('type') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Number of Venues</label>
                        <input type="number" name="number_of_venues" value="{{ old('number_of_venues', 1) }}" min="1" max="100" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Capacity</label>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" required
                            class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-gray-200 mb-1">Facility Image</label>
                    <input type="file" name="facility_image" accept="image/*"
                        class="w-full rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] bg-white dark:bg-[#111827] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" />
                    <p class="text-xs text-[#616f89] mt-1">Allowed types: JPEG, PNG, WEBP. Max size 2MB.</p>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-[#111318] dark:text-gray-200 font-medium">Active and bookable</label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] text-sm font-semibold text-[#111318] dark:text-gray-200">Cancel</a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow hover:bg-blue-700 transition-colors">
                        Save Facility
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
