@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f8fa] dark:bg-[#0f1419]">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('society.show', $society->societyID) }}" class="flex items-center gap-2 text-primary hover:underline font-medium">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Back to {{ $society->societyName }}
            </a>
        </div>

        <!-- Post Card -->
        <div class="bg-white dark:bg-[#1a202c] rounded-lg shadow-md border border-[#e5e7eb] dark:border-[#2a3441]">
            <!-- Post Header -->
            <div class="p-8 border-b border-[#e5e7eb] dark:border-[#2a3441]">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-[#111318] dark:text-white text-lg">{{ $post->user->name }}</p>
                            <p class="text-sm text-[#616f89] dark:text-gray-400">{{ $post->created_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-primary text-sm font-semibold rounded-full">
                            @php
                                $memberPos = $society->members->where('userID', $post->userID)->first();
                            @endphp
                            {{ ucfirst($memberPos?->position ?? 'member') }}
                        </span>
                        <!-- Three Dot Menu -->
                        @php
                            $canDeletePost = $isMember && (auth()->user() && (
                                $society->members->where('userID', auth()->id())->first()?->position === 'president' || 
                                $post->userID === auth()->id()
                            ));
                        @endphp
                        @if($canDeletePost)
                            <div class="relative group">
                                <button class="p-2 text-[#616f89] dark:text-gray-400 hover:text-primary transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                <div class="hidden group-hover:block absolute right-0 mt-1 bg-white dark:bg-[#1a202c] border border-[#e5e7eb] dark:border-[#2a3441] rounded-lg shadow-lg z-20">
                                    <form action="{{ route('society.post.destroy', [$society->societyID, $post->postID]) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 transition-colors" onclick="return confirm('Are you sure you want to delete this post?');">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Delete Post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Title & Content -->
                <div>
                    <h1 class="text-4xl font-bold text-[#111318] dark:text-white mb-6">{{ $post->title }}</h1>
                    <p class="text-lg text-[#616f89] dark:text-gray-400 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                </div>
            </div>

            <!-- Post Images Gallery -->
            @if($post->images->count() > 0)
                <div class="border-b border-[#e5e7eb] dark:border-[#2a3441] p-8">
                    <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-6">Images ({{ $post->images->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($post->images as $image)
                            <div class="group">
                                <img src="{{ asset('storage/' . $image->filePath) }}" alt="Post image" class="w-full rounded-lg object-cover h-80 cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src)">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Post Files -->
            @if($post->files->count() > 0)
                <div class="border-b border-[#e5e7eb] dark:border-[#2a3441] p-8">
                    <h3 class="text-lg font-semibold text-[#111318] dark:text-white mb-4">Files ({{ $post->files->count() }})</h3>
                    <div class="space-y-3">
                        @foreach($post->files as $file)
                            @php
                                $fileExtension = strtolower(pathinfo($file->originalName ?? $file->filePath, PATHINFO_EXTENSION));
                                $previewable = in_array($fileExtension, ['pdf', 'txt', 'json', 'csv', 'xml', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'log', 'md']);
                            @endphp
                            <div class="flex items-center justify-between gap-4 bg-[#f6f8fa] dark:bg-[#0f1419] p-5 rounded-lg border border-[#e5e7eb] dark:border-[#2a3441] hover:shadow-md transition-shadow group">
                                <button type="button" onclick="openFilePreview('{{ route('file.view', $file->fileID) }}', '{{ $fileExtension }}', '{{ $file->originalName ?? basename($file->filePath) }}', '{{ route('file.preview', $file->fileID) }}')" class="flex items-center gap-4 flex-1 min-w-0 cursor-pointer hover:opacity-80 transition-opacity">
                                    <span class="material-symbols-outlined text-primary text-3xl flex-shrink-0">attach_file</span>
                                    <div class="flex-1 min-w-0 text-left">
                                        <p class="text-base font-semibold text-[#111318] dark:text-white truncate">{{ $file->originalName ?? basename($file->filePath) }}</p>
                                        <p class="text-sm text-[#616f89] dark:text-gray-400">
                                            @php
                                                $sizeInMB = $file->fileSize ? round($file->fileSize / (1024 * 1024), 2) : 'Unknown';
                                            @endphp
                                            {{ $sizeInMB }} MB
                                            @if($previewable)
                                                <span class="text-xs text-primary ml-2">• Click to preview</span>
                                            @endif
                                        </p>
                                    </div>
                                </button>
                                <a href="{{ route('file.download', $file->fileID) }}" download class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium flex-shrink-0">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="p-8 border-t border-[#e5e7eb] dark:border-[#2a3441]">
                <h3 class="text-2xl font-bold text-[#111318] dark:text-white mb-6">Comments (<span id="commentCount">0</span>)</h3>

                <!-- Comments List -->
                <div id="commentsList" class="space-y-5 mb-8">
                    <div class="text-center py-12">
                        <p class="text-lg text-[#616f89] dark:text-gray-400">Loading comments...</p>
                    </div>
                </div>

                <!-- Add Comment -->
                @if($isMember)
                    <div class="bg-[#f6f8fa] dark:bg-[#0f1419] rounded-lg p-5 border border-[#e5e7eb] dark:border-[#2a3441]">
                        <form id="commentForm" class="flex gap-4" onsubmit="submitComment(event)">
                            @csrf
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <textarea 
                                    id="commentInput"
                                    placeholder="Add a comment..."
                                    maxlength="1000"
                                    class="w-full h-20 rounded-lg border border-[#d0d7de] dark:border-[#2a3441] bg-white dark:bg-[#0f1419] px-4 py-3 text-base text-[#111318] dark:text-white placeholder:text-[#616f89] focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                ></textarea>
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-xs text-[#616f89]"><span id="charCount">0</span>/1000</p>
                                    <button 
                                        type="submit"
                                        class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium"
                                    >
                                        <span class="material-symbols-outlined text-sm">send</span>
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-base text-[#616f89] dark:text-gray-400">
                            <a href="{{ route('society.join', $society->societyID) }}" class="text-primary hover:underline font-medium">Join the society</a> to add comments
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Modal for Fullscreen View -->
<div id="imageModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-[90vh] w-full h-full flex items-center justify-center">
        <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain">
        <button 
            onclick="closeImageModal()"
            class="absolute top-4 right-4 p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors"
        >
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
</div>

<!-- File Preview Modal -->
<div id="filePreviewModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-[90vh] w-full h-full flex flex-col items-center justify-center">
        <!-- Header -->
        <div class="absolute top-4 left-4 right-4 flex items-center justify-between z-10">
            <h3 id="previewFileName" class="text-white font-semibold truncate max-w-md"></h3>
            <button 
                onclick="closeFilePreview()"
                class="p-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors"
            >
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Content Area -->
        <div id="previewContent" class="w-full h-full flex items-center justify-center overflow-auto"></div>
    </div>
</div>

<script>
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside the image
    document.getElementById('imageModal').addEventListener('click', (e) => {
        if (e.target.id === 'imageModal') {
            closeImageModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeImageModal();
            closeFilePreview();
        }
    });

    // File Preview Functions
    function openFilePreview(fileUrl, extension, fileName, previewApiUrl) {
        const modal = document.getElementById('filePreviewModal');
        const previewContent = document.getElementById('previewContent');
        const previewFileName = document.getElementById('previewFileName');
        
        previewFileName.textContent = fileName;
        
        // Clear previous content
        previewContent.innerHTML = '';
        
        // Handle different file types
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
            previewContent.innerHTML = `<img src="${fileUrl}" class="max-w-full max-h-full object-contain" alt="${fileName}">`;
        } else if (extension === 'pdf') {
            previewContent.innerHTML = `<iframe src="${fileUrl}" class="w-full h-full" frameborder="0" allow="fullscreen"></iframe>`;
        } else if (['txt', 'csv', 'json', 'xml', 'log', 'md'].includes(extension)) {
            // Fetch and display text file content
            fetch(previewApiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load file: ' + response.statusText);
                    }
                    return response.text();
                })
                .then(content => {
                    previewContent.innerHTML = `
                        <div class="w-full h-full bg-[#1a202c] p-6 rounded-lg overflow-auto">
                            <pre class="text-gray-300 font-mono text-sm whitespace-pre-wrap break-words">${escapeHtml(content)}</pre>
                        </div>
                    `;
                })
                .catch(err => {
                    console.error('Preview error:', err);
                    previewContent.innerHTML = `
                        <div class="text-center text-white">
                            <p class="mb-2">Preview not available</p>
                            <p class="text-sm text-gray-400">Error: ${err.message}</p>
                        </div>
                    `;
                });
        } else {
            previewContent.innerHTML = `
                <div class="text-center text-white">
                    <span class="material-symbols-outlined text-6xl mb-4">description</span>
                    <p class="mb-2">Preview not available for this file type</p>
                    <p class="text-sm text-gray-400">Please download the file to view it</p>
                </div>
            `;
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFilePreview() {
        const modal = document.getElementById('filePreviewModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Close modal when clicking outside
    document.getElementById('filePreviewModal').addEventListener('click', (e) => {
        if (e.target.id === 'filePreviewModal') {
            closeFilePreview();
        }
    });

    // ===========================
    // Comment Functions
    // ===========================

    const societyID = {{ $society->societyID }};
    const postID = {{ $post->postID }};

    // Load comments on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadComments();
        
        // Character counter
        const commentInput = document.getElementById('commentInput');
        if (commentInput) {
            commentInput.addEventListener('input', function() {
                document.getElementById('charCount').textContent = this.value.length;
            });
        }
    });

    function loadComments() {
        fetch(`/society/${societyID}/post/${postID}/comments`)
            .then(response => response.json())
            .then(data => {
                const commentsList = document.getElementById('commentsList');
                const commentCount = document.getElementById('commentCount');
                
                commentCount.textContent = data.total;

                if (data.comments.length === 0) {
                    commentsList.innerHTML = `
                        <div class="text-center py-12">
                            <p class="text-lg text-[#616f89] dark:text-gray-400">No comments yet. Be the first to comment!</p>
                        </div>
                    `;
                } else {
                    commentsList.innerHTML = data.comments.map(comment => `
                        <div class="bg-[#f6f8fa] dark:bg-[#0f1419] rounded-lg p-5 border border-[#e5e7eb] dark:border-[#2a3441]" id="comment-${comment.commentID}">
                            <div class="flex gap-4 justify-between">
                                <div class="flex gap-4 flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-blue-700 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                        ${comment.user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <p class="text-base font-semibold text-[#111318] dark:text-white">${comment.user.name}</p>
                                            <p class="text-sm text-[#616f89] dark:text-gray-400">${formatDate(comment.created_at)}</p>
                                        </div>
                                        <p class="text-base text-[#616f89] dark:text-gray-400 whitespace-pre-wrap">${escapeHtml(comment.content)}</p>
                                    </div>
                                </div>
                                ${canDeleteComment(comment) ? `
                                    <div class="flex items-start gap-2">
                                        <button 
                                            onclick="deleteComment(${comment.commentID})"
                                            class="p-2 text-[#616f89] dark:text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                            title="Delete comment"
                                        >
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => console.error('Error loading comments:', error));
    }

    function submitComment(event) {
        event.preventDefault();
        
        const commentInput = document.getElementById('commentInput');
        const content = commentInput.value.trim();

        if (!content) {
            alert('Please enter a comment');
            return;
        }

        fetch(`/society/${societyID}/post/${postID}/comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                commentInput.value = '';
                document.getElementById('charCount').textContent = '0';
                loadComments();
            }
        })
        .catch(error => {
            console.error('Error posting comment:', error);
            alert('Error posting comment. Please try again.');
        });
    }

    function deleteComment(commentID) {
        if (!confirm('Delete this comment?')) return;

        fetch(`/society/${societyID}/post/${postID}/comment/${commentID}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            loadComments();
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Error deleting comment. Please try again.');
        });
    }

    function canDeleteComment(comment) {
        // Check if user is authenticated and is either the comment author or a president
        const currentUserID = {{ Auth::check() ? Auth::id() : 'null' }};
        if (currentUserID === null) return false;
        
        return comment.userID === currentUserID || '{{ $society->members->where('userID', Auth::id())->first()?->position ?? '' }}' === 'president';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = months[date.getMonth()];
        const day = date.getDate();
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${month} ${day}, ${year} ${hours}:${minutes}`;
    }
</script>
@endsection
