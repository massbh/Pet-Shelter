<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/admin.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/blog/admin.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
</head>

<body>
    @include('components.header')

    <div class="blog-form-container">
        <div class="blog-form-header">
            <h1><i data-lucide="edit"></i> Edit Blog Post</h1>
            <p style="color: #666; margin-top: 0.5rem;">Update your blog post content and settings</p>
        </div>

        <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.blog._form')

            <div class="blog-form-actions">
                <a href="{{ route('admin.blog.index') }}" class="btn-cancel">
                    <i data-lucide="x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i data-lucide="save"></i> Update Post
                </button>
            </div>
        </form>
    </div>

    @include('components.footer')

    <script>
        lucide.createIcons();
    </script>
</body>
</html>