@extends('layouts.public')

@section('title', __('Blog & News') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="blog-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">@lang('Blog & News')</h1>
            <p class="lead text-muted">@lang('Latest updates, travel tips, and company news')</p>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($posts->isEmpty())
                    <div class="alert alert-info">
                        @lang('No posts available at the moment.')
                    </div>
                @else
                    @foreach($posts as $post)
                    <article class="blog-card mb-4">
                        @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" class="blog-image" alt="{{ $post->title }}">
                        @endif
                        <div class="blog-content">
                            <div class="blog-meta">
                                @if($post->category)
                                <span class="badge bg-success">{{ $post->category->name }}</span>
                                @endif
                                <span><i class="bi bi-calendar"></i> {{ $post->published_at?->format('M d, Y') }}</span>
                                <span><i class="bi bi-eye"></i> {{ $post->view_count }} @lang('views')</span>
                            </div>
                            <h2 class="blog-title">{{ $post->title }}</h2>
                            <p class="blog-excerpt">{{ $post->excerpt }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-success">
                                @lang('Read More') <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                    @endforeach

                    {{ $posts->links() }}
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-folder"></i> @lang('Categories')</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="{{ route('blog.index') }}" class="text-decoration-none">
                                    @lang('All Posts')
                                </a>
                            </li>
                            @foreach($categories as $category)
                            <li class="list-group-item">
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                                    <i class="bi bi-folder"></i> {{ $category->name }}
                                    <span class="badge bg-secondary float-end">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Featured Posts -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-star"></i> @lang('Featured Posts')</h5>
                    </div>
                    <div class="card-body">
                        @foreach($featuredPosts as $featured)
                        <div class="d-flex mb-3">
                            @if($featured->featured_image)
                            <img src="{{ $featured->featured_image }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                            @endif
                            <div>
                                <a href="{{ route('blog.show', $featured->slug) }}" class="text-decoration-none fw-bold">
                                    {{ Str::limit($featured->title, 50) }}
                                </a>
                                <small class="text-muted d-block">{{ $featured->published_at?->format('M d, Y') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.blog-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    color: white;
    padding: 80px 0;
}
.blog-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.blog-card:hover {
    transform: translateY(-5px);
}
.blog-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
.blog-content {
    padding: 24px;
}
.blog-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    color: #6c757d;
    font-size: 14px;
}
.blog-title {
    font-size: 1.75rem;
    margin-bottom: 15px;
    color: #333;
}
.blog-excerpt {
    color: #666;
    margin-bottom: 20px;
}
</style>
@endpush
