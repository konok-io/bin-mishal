@extends('public.layouts.master')

@section('title', __('app.blog') . ' - ' . __('app.app_name'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>{{ __('app.blog') }}</h1>
        <p class="lead">Latest news, travel tips, and updates</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @php
                $posts = \App\Models\Post::where('status', 'published')
                    ->where('is_active', true)
                    ->with(['category', 'author'])
                    ->orderBy('published_at', 'desc')
                    ->paginate(9);
                @endphp
                
                @forelse($posts as $post)
                    <article class="blog-card mb-4">
                        <div class="row g-0">
                            @if($post->featured_image)
                                <div class="col-md-4">
                                    <img src="{{ $post->featured_image }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $post->title }}">
                                </div>
                            @endif
                            <div class="col-md-{{ $post->featured_image ? '8' : '12' }}">
                                <div class="card-body">
                                    <div class="mb-2">
                                        @if($post->category)
                                            <span class="badge bg-primary">{{ $post->category->name }}</span>
                                        @endif
                                        <small class="text-muted">{{ $post->published_at->format('M d, Y') }}</small>
                                    </div>
                                    <h3 class="h5">
                                        <a href="{{ route('blog.detail', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" class="text-decoration-none text-dark">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                    <a href="{{ route('blog.detail', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" class="btn btn-sm btn-outline-primary">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No blog posts yet</h3>
                        <p>Check back soon for updates!</p>
                    </div>
                @endforelse
                
                @if($posts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        @php
                        $categories = \App\Models\PostCategory::where('is_active', true)->get();
                        @endphp
                        <ul class="list-unstyled mb-0">
                            @forelse($categories as $category)
                                <li class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <i class="bi bi-folder"></i> {{ $category->name }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted">No categories</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                
                <!-- Recent Posts -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Recent Posts</h5>
                    </div>
                    <div class="card-body">
                        @php
                        $recentPosts = \App\Models\Post::where('status', 'published')
                            ->where('is_active', true)
                            ->orderBy('published_at', 'desc')
                            ->limit(5)
                            ->get();
                        @endphp
                        <ul class="list-unstyled mb-0">
                            @forelse($recentPosts as $recent)
                                <li class="mb-3">
                                    <a href="{{ route('blog.detail', ['locale' => app()->getLocale(), 'slug' => $recent->slug]) }}" class="text-decoration-none">
                                        <small class="text-muted">{{ $recent->published_at->format('M d') }}</small>
                                        <br>{{ Str::limit($recent->title, 50) }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted">No recent posts</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.blog-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
}
.blog-card img {
    min-height: 200px;
}
</style>
@endpush
