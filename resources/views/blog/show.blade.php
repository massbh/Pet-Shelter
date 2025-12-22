<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Pet Shelter Blog</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/public.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    @include('components.header')

    <section class="blog-container">
        <article class="single-post">
            <header class="post-header">
                <h1>{{ $post->title }}</h1>
                <p class="post-description">{{ $post->description }}</p>
                
                <div class="post-meta">
                    <div class="post-date">
                        <i data-lucide="calendar"></i>
                        {{ $post->published_at->format('F j, Y') }}
                    </div>
                    <div class="post-author">
                        <i data-lucide="user"></i>
                        {{ $post->user->name }}
                    </div>
                    @if(Auth::check() && Auth::user()->isAdmin())
                        <div class="post-admin">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="blog-read-more" style="font-size: 0.9rem;">
                                <i data-lucide="edit"></i> Edit
                            </a>
                        </div>
                    @endif
                </div>
            </header>

            @if($post->featured_image)
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="post-image">
            @endif

            <div class="post-content">
                {!! $post->content !!}
            </div>

            <footer class="post-footer">
                <a href="{{ route('blog.index') }}" class="back-to-blog">
                    <i data-lucide="arrow-left"></i> Back to Blog
                </a>
            </footer>
        </article>
    </section>

    @include('components.footer')

    <script>
        lucide.createIcons();
        
        // Add responsive behavior to images in content
        document.querySelectorAll('.post-content img').forEach(img => {
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.style.borderRadius = '8px';
            img.style.margin = '20px 0';
        });
    </script>
</body>
</html>