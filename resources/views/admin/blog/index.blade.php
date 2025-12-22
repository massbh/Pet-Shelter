<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/admin.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/blog/admin.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('components.header')

    <div class="blog-admin-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="blog-admin-header">
            <h1><i data-lucide="file-text"></i> Manage Blog Posts</h1>
            <div class="blog-admin-actions">
                <a href="{{ route('admin.blog.create') }}" class="btn-submit">
                    <i data-lucide="plus-circle"></i> New Post
                </a>
                <a href="{{ route('blog.index') }}" class="btn-cancel" target="_blank">
                    <i data-lucide="eye"></i> View Public Blog
                </a>
            </div>
        </div>

        @if($posts->count() > 0)
            <table class="blog-admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Author</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="blog-image-preview">
                                @else
                                    <div style="width: 80px; height: 60px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                        <i data-lucide="image" style="color: #999;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $post->title }}</strong>
                                <br>
                                <small style="color: #666;">Slug: {{ $post->slug }}</small>
                            </td>
                            <td style="max-width: 300px;">
                                <p style="margin: 0; line-height: 1.4;">{{ Str::limit($post->description, 100) }}</p>
                            </td>
                            <td>
                                <span class="blog-status-badge {{ $post->is_published ? 'blog-status-published' : 'blog-status-draft' }}">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                @if($post->published_at)
                                    {{ $post->published_at->format('M d, Y') }}
                                @else
                                    <span style="color: #999;">Not published</span>
                                @endif
                            </td>
                            <td>{{ $post->user->name }}</td>
                            <td>
                                <div class="blog-actions">
                                    <a href="{{ route('blog.show', $post) }}" target="_blank" class="blog-action-btn blog-action-view">
                                        <i data-lucide="eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="blog-action-btn blog-action-edit">
                                        <i data-lucide="edit"></i> Edit
                                    </a>
                                    <button type="button" class="blog-action-btn blog-action-delete" 
                                            data-post-id="{{ $post->id }}" 
                                            data-post-title="{{ $post->title }}">
                                        <i data-lucide="trash-2"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($posts->hasPages())
                <div class="blog-pagination">
                    @if($posts->onFirstPage())
                        <span class="pagination-link disabled">Previous</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="pagination-link">Previous</a>
                    @endif

                    @for($i = 1; $i <= $posts->lastPage(); $i++)
                        @if($i == $posts->currentPage())
                            <span class="pagination-link active">{{ $i }}</span>
                        @else
                            <a href="{{ $posts->url($i) }}" class="pagination-link">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="pagination-link">Next</a>
                    @else
                        <span class="pagination-link disabled">Next</span>
                    @endif
                </div>
            @endif
        @else
            <div class="blog-empty-state">
                <h3>No Blog Posts Yet</h3>
                <p>Start creating content for your blog! Share stories, tips, and updates about your pet shelter.</p>
                <a href="{{ route('admin.blog.create') }}" class="btn-submit">
                    <i data-lucide="plus-circle"></i> Create Your First Post
                </a>
            </div>
        @endif
    </div>

    @include('components.footer')

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i data-lucide="alert-triangle"></i> Delete Blog Post</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete "<strong id="deletePostTitle"></strong>"?</p>
                <p style="color: #dc3545; margin-top: 10px; font-size: 0.9rem;">
                    <i data-lucide="alert-circle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i data-lucide="trash-2"></i> Delete Post
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>