@extends('layouts.public')

@section('title', $post->title . ' - ' . config('app.name'))

@section('meta')
    <meta name="description" content="{{ $post->excerpt }}">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt }}">
    @if($post->og_image)
    <meta property="og:image" content="{{ $post->og_image }}">
    @elseif($post->featured_image)
    <meta property="og:image" content="{{ $post->featured_image }}">
    @endif
@endsection

@section('content')
<!-- Article Hero -->
<section class="article-hero" @if($post->featured_image) style="background-image: url('{{ $post->featured_image }}')" @endif>
    <div class="article-hero-overlay">
        <div class="container">
            <div class="text-center text-white">
                @if($post->category)
                <span class="badge bg-success mb-3">{{ $post->category->name }}</span>
                @endif
                <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>
                <div class="article-meta">
                    <span><i class="bi bi-calendar"></i> {{ $post->published_at?->format('M d, Y') }}</span>
                    <span><i class="bi bi-eye"></i> {{ $post->view_count }} @lang('views')</span>
                    @if($post->reading_time)
                    <span><i class="bi bi-clock"></i> {{ $post->reading_time }} @lang('min read')</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="article-content">
                    {!! $post->content !!}
                </article>

                <!-- Tags -->
                @if($post->tags->isNotEmpty())
                <div class="article-tags mt-5">
                    <h5>@lang('Tags')</h5>
                    @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="badge bg-secondary text-decoration-none">
                        {{ $tag->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                <!-- Share -->
                <div class="article-share mt-4">
                    <h5>@lang('Share This Article')</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-primary">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-info">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" class="btn btn-success">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->isNotEmpty())
                <div class="related-posts mt-5">
                    <h4 class="mb-4">@lang('Related Posts')</h4>
                    <div class="row">
                        @foreach($relatedPosts as $related)
                        <div class="col-md-6">
                            <div class="card h-100">
                                @if($related->featured_image)
                                <img src="{{ $related->featured_image }}" class="card-img-top" alt="{{ $related->title }}" style="height: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none text-dark">
                                            {{ Str::limit($related->title, 60) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $related->published_at?->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.article-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    background-size: cover;
    background-position: center;
    color: white;
    padding: 120px 0;
    position: relative;
}
.article-hero-overlay {
    background: rgba(0, 0, 0, 0.6);
    padding: 80px 0;
}
.article-meta {
    display: flex;
    gap: 20px;
    justify-content: center;
    font-size: 14px;
}
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
.article-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 20px 0;
}
.article-content h2, .article-content h3, .article-content h4 {
    margin-top: 30px;
    margin-bottom: 15px;
}
.article-tags a {
    margin-right: 5px;
}
</style>
@endpush
