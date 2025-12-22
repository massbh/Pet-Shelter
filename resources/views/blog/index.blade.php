<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Pet Shelter</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blog/public.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    @include('components.header')

    <div class="blog-hero">
        <h1>Our Pet Shelter Blog</h1>
        <p>Discover stories, tips, and insights about pet care, adoption stories, and animal welfare</p>
    </div>

    <section class="blog-container">
        @if($posts->count() > 0)
            <div class="blog-grid">
                @foreach($posts as $post)
                    <article class="blog-card">
                        @if($post->featured_image)
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="blog-card-image">
                        @else
                            <img src="{{ asset('assets/blog-placeholder.jpg') }}" alt="Blog post" class="blog-card-image">
                        @endif
                        
                        <div class="blog-card-content">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->description }}</p>
                            
                            <div class="blog-meta">
                                <div class="blog-date">
                                    <i data-lucide="calendar"></i>
                                    {{ $post->published_at->format('M d, Y') }}
                                </div>
                                <a href="{{ route('blog.show', $post) }}" class="blog-read-more">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

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
            <div class="no-posts">
                <h3>No Blog Posts Yet</h3>
                <p>Check back soon for our latest articles about pet care and adoption stories!</p>
            </div>
        @endif
    </section>

    @include('components.footer')

    <script>
        lucide.createIcons();
    </script>
</body>
</html>