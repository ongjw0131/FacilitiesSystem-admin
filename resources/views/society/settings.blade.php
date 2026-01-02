@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-blue-700 text-white py-8 px-4 md:px-10">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('society.show', $society->societyID) }}" class="hover:bg-white/20 rounded-lg p-2 transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">Society Settings</h1>
                    <p class="text-blue-100 mt-1">{{ $society->societyName }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 md:px-10 py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
                <p class="text-red-800 dark:text-red-300 font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-700 dark:text-red-400 text-sm">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg">
                <p class="text-green-800 dark:text-green-300 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('society.updateSettings', $society->societyID) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Society Name (Read-only) -->
            <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-6 border border-[#e5e7eb] dark:border-[#2a3441]">
                <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Basic Information</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">Society Name</label>
                    <div class="h-10 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-gray-100 dark:bg-gray-800 px-4 py-2 text-sm text-[#616f89] dark:text-gray-500 flex items-center">
                        {{ $society->societyName }}
                    </div>
                    <p class="text-xs text-[#616f89] dark:text-gray-400 mt-1">Society name cannot be changed</p>
                </div>

                <!-- Society Photo -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">Society Photo</label>
                    
                    @if($society->societyPhotoPath)
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441]">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs text-[#616f89] dark:text-gray-400">Current Photo</p>
                                <button type="button" id="removeCurrentPhotoBtn" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Remove
                                </button>
                            </div>
                            <img src="{{ asset('storage/' . $society->societyPhotoPath) }}" alt="{{ $society->societyName }}" class="w-full h-40 object-cover rounded-lg">
                            <input type="hidden" id="deleteCurrentPhoto" name="deleteCurrentPhoto" value="0">
                        </div>
                    @endif

                    <!-- Preview Container -->
                    <div id="photoPreviewContainer" class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] hidden">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs text-[#616f89] dark:text-gray-400">New Photo Preview</p>
                            <button type="button" id="removePhotoBtn" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                Remove
                            </button>
                        </div>
                        <img id="photoPreview" src="" alt="Preview" class="w-full h-40 object-cover rounded-lg mb-2">
                        <div class="flex justify-between items-center text-xs text-[#616f89] dark:text-gray-400">
                            <span id="photoFileName"></span>
                            <span id="photoFileSize"></span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center justify-center gap-2 h-32 border-2 border-dashed border-[#d0d7de] dark:border-[#2a3441] rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors photo-upload-label">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-3xl text-primary block mb-2">image</span>
                                <span class="text-sm text-primary font-medium block">Upload Photo</span>
                                <span class="text-xs text-[#616f89] dark:text-gray-400 block">JPEG, PNG, GIF (Max 6MB)</span>
                            </div>
                            <input type="file" accept="image/*" name="societyPhotoPath" class="hidden photo-input" id="societyPhotoInput">
                        </label>
                    </div>
                    @error('societyPhotoPath')
                        <p class="text-red-600 dark:text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Society Description -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-[#111318] dark:text-white">Description</label>
                        <span class="text-xs text-[#616f89] dark:text-gray-400"><span id="descriptionCharCount">{{ strlen($society->societyDescription) }}</span>/500 characters</span>
                    </div>
                    <textarea 
                        name="societyDescription"
                        id="societyDescriptionInput"
                        rows="5"
                        maxlength="500"
                        class="w-full rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-3 text-sm text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                        required
                    >{{ $society->societyDescription }}</textarea>
                    <p class="text-xs text-[#616f89] dark:text-gray-400 mt-2">Maximum 500 characters allowed</p>
                </div>
            </div>

            <!-- Access Settings -->
            <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-6 border border-[#e5e7eb] dark:border-[#2a3441]">
                <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Access Settings</h2>

                <!-- Join Type -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-3">How can users join this society?</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <input 
                                type="radio" 
                                name="joinType" 
                                value="open"
                                {{ $society->joinType === 'open' ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary"
                            >
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">lock_open</span>
                                    Open
                                </p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">Anyone can join without approval</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <input 
                                type="radio" 
                                name="joinType" 
                                value="approval"
                                {{ $society->joinType === 'approval' ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary"
                            >
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    Approval Required
                                </p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">Users must request to join and be approved</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <input 
                                type="radio" 
                                name="joinType" 
                                value="closed"
                                {{ $society->joinType === 'closed' ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary"
                            >
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">lock</span>
                                    Closed
                                </p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">No one can join, only invited members</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Post Permissions -->
            <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-sm p-6 border border-[#e5e7eb] dark:border-[#2a3441]">
                <h2 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Post Permissions</h2>

                <div>
                    <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-3">Who can create posts?</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <input 
                                type="radio" 
                                name="whoCanPost" 
                                value="president_only"
                                {{ ($society->whoCanPost ?? 'president_only') === 'president_only' ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary"
                            >
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                                    President Only
                                </p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">Only the president can create posts</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <input 
                                type="radio" 
                                name="whoCanPost" 
                                value="committee"
                                {{ ($society->whoCanPost ?? 'president_only') === 'committee' ? 'checked' : '' }}
                                class="w-4 h-4 accent-primary"
                            >
                            <div>
                                <p class="font-semibold text-[#111318] dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">groups</span>
                                    President & Committee
                                </p>
                                <p class="text-xs text-[#616f89] dark:text-gray-400">President and committee members can create posts</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('society.show', $society->societyID) }}" class="px-6 py-2 text-[#616f89] dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="px-6 py-2 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-sm">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const MAX_FILE_SIZE = 6 * 1024 * 1024; // 6MB in bytes
    const photoInput = document.getElementById('societyPhotoInput');
    const photoPreview = document.getElementById('photoPreview');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');
    const photoFileName = document.getElementById('photoFileName');
    const photoFileSize = document.getElementById('photoFileSize');
    const removePhotoBtn = document.getElementById('removePhotoBtn');
    const removeCurrentPhotoBtn = document.getElementById('removeCurrentPhotoBtn');
    const deleteCurrentPhotoInput = document.getElementById('deleteCurrentPhoto');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                if (file.size > MAX_FILE_SIZE) {
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    alert(`File size (${sizeMB}MB) exceeds the maximum allowed size of 6MB.\n\nPlease choose a smaller file.`);
                    // Clear the input and hide preview
                    this.value = '';
                    photoPreviewContainer.classList.add('hidden');
                } else {
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    console.log(`File selected: ${file.name} (${sizeMB}MB)`);
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        photoPreview.src = event.target.result;
                        photoFileName.textContent = file.name;
                        photoFileSize.textContent = `${sizeMB}MB`;
                        photoPreviewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    // Remove photo preview
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear the file input
            photoInput.value = '';
            // Clear preview
            photoPreview.src = '';
            photoFileName.textContent = '';
            photoFileSize.textContent = '';
            // Hide preview container
            photoPreviewContainer.classList.add('hidden');
            console.log('Photo preview removed');
        });
    }

    // Remove current photo
    if (removeCurrentPhotoBtn && deleteCurrentPhotoInput) {
        removeCurrentPhotoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove the current photo?')) {
                // Set flag to delete current photo
                deleteCurrentPhotoInput.value = '1';
                console.log('deleteCurrentPhoto set to:', deleteCurrentPhotoInput.value);
                // Hide current photo section
                this.closest('.mb-4').style.display = 'none';
                console.log('Current photo marked for deletion. Click Save Changes to apply.');
            }
        });
    } else {
        console.log('removeCurrentPhotoBtn:', removeCurrentPhotoBtn);
        console.log('deleteCurrentPhotoInput:', deleteCurrentPhotoInput);
    }

    // Description character counter
    const descriptionInput = document.getElementById('societyDescriptionInput');
    const descriptionCharCount = document.getElementById('descriptionCharCount');

    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            descriptionCharCount.textContent = currentLength;
        });
    }
</script>
@endsection
