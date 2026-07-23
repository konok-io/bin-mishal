@extends('layouts.public')

@section('title', $slug . ' - ' . config('app.name'))

@section('content')
@php
$post = \App\Models\Content\Post::where('slug', $slug)->orWhere('id', $slug)->first();
@endphp

@if(!$post)
<section class="py-5">
    <div class="container text-center">
        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
        <h2 class="mt-3">@lang('Article Not Found')</h2>
        <a href="{{ route('news') }}" class="btn btn-success mt-3">@lang('Back to News')</a>
    </div>
</section>
@else
<section class="py-5">
    <div class="container">
        <article class="news-article">
            <header class="article-header">
                <span class="article-date">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                </span>
                <h1>{{ $post->title }}</h1>
                @if($post->author)
                <div class="article-author">
                    <i class="bi bi-person"></i> {{ $post->author }}
                </div>
                @endif
            </header>
            
            @if($post->featured_image)
            <figure class="article-image">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="img-fluid">
            </figure>
            @endif
            
            <div class="article-content">
                {!! $post->content !!}
            </div>
            
            <footer class="article-footer">
                <a href="{{ route('news') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> @lang('Back to News')
                </a>
            </footer>
        </article>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
.article-header {
    text-align: center;
    margin-bottom: 30px;
}
.article-date {
    color: var(--primary);
    font-size: 14px;
}
.article-header h1 {
    margin: 15px 0;
    color: #333;
}
.article-author {
    color: #666;
}
.article-image {
    margin: 30px 0;
}
.article-image img {
    width: 100%;
    border-radius: 12px;
}
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
.article-content p {
    margin-bottom: 20px;
}
.article-footer {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
</style>
@endpush
