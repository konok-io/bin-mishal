@extends('layouts.public')

@section('title', __('News & Updates') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="news-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('News & Updates')</h1>
        <p class="lead text-white">@lang('Stay updated with the latest travel news and company announcements')</p>
    </div>
</section>

<!-- News Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @php
            $posts = \App\Models\Content\Post::where('status', 'published')
                ->where('type', 'news')
                ->orderBy('published_at', 'desc')
                ->limit(12)
                ->get();
            @endphp
            
            @forelse($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="news-card">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="news-image">
                    @endif
                    <div class="news-content">
                        <span class="news-date">
                            <i class="bi bi-calendar"></i>
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </span>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        <a href="{{ route('news.detail', $post->slug ?? $post->id) }}" class="btn btn-success btn-sm">
                            @lang('Read More')
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-newspaper" style="font-size: 4rem; color: #ccc;"></i>
                <h3 class="mt-3">@lang('No news articles yet')</h3>
                <p>@lang('Check back soon for updates!')</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.news-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.news-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    height: 100%;
    transition: transform 0.3s;
}
.news-card:hover {
    transform: translateY(-5px);
}
.news-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.news-content {
    padding: 20px;
}
.news-date {
    color: var(--primary);
    font-size: 14px;
}
.news-date i {
    margin-right: 5px;
}
.news-content h3 {
    margin: 10px 0;
    font-size: 1.2rem;
    color: #333;
}
.news-content p {
    color: #666;
    font-size: 14px;
}
</style>
@endpush
