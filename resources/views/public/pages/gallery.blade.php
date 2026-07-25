@extends('layouts.public')

@section('title', __('Gallery') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="gallery-hero" style="background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); padding: 80px 0;">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Gallery')</h1>
        <p class="lead text-white">@lang('Explore our photo and video gallery')</p>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
        <!-- Filter Tabs -->
        <ul class="nav nav-pills mb-4 justify-content-center" id="galleryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                    <i class="bi bi-grid-3x3-gap me-2"></i>@lang('All')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="photos-tab" data-bs-toggle="pill" data-bs-target="#photos" type="button" role="tab">
                    <i class="bi bi-image me-2"></i>@lang('Photos')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="videos-tab" data-bs-toggle="pill" data-bs-target="#videos" type="button" role="tab">
                    <i class="bi bi-play-circle me-2"></i>@lang('Videos')
                </button>
            </li>
        </ul>

        <!-- Gallery Content -->
        <div class="tab-content" id="galleryTabContent">
            <!-- All Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @if($galleryItems->count() > 0)
                    <div class="row g-4">
                        @foreach($galleryItems as $item)
                            <div class="col-lg-4 col-md-6">
                                @if($item->type === 'photo')
                                    <div class="gallery-item position-relative overflow-hidden rounded-3">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->translated_title }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                                        <div class="gallery-overlay">
                                            <h5 class="text-white">{{ $item->translated_title }}</h5>
                                            @if($item->translated_description)
                                                <p class="text-white-50">{{ Str::limit($item->translated_description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="gallery-item position-relative overflow-hidden rounded-3">
                                        @if($item->thumbnail)
                                            <img src="{{ $item->thumbnail }}" alt="{{ $item->translated_title }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                                        @else
                                            <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 250px;">
                                                <i class="bi bi-play-circle text-white" style="font-size: 4rem;"></i>
                                            </div>
                                        @endif
                                        <div class="gallery-overlay">
                                            <h5 class="text-white">{{ $item->translated_title }}</h5>
                                            <a href="{{ $item->video_embed_url }}" class="btn btn-light btn-sm mt-2" target="_blank">
                                                <i class="bi bi-play"></i> @lang('Watch Video')
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">@lang('No gallery items available yet.')</p>
                    </div>
                @endif
            </div>

            <!-- Photos Tab -->
            <div class="tab-pane fade" id="photos" role="tabpanel">
                @if($photos->count() > 0)
                    <div class="row g-4">
                        @foreach($photos as $item)
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-item position-relative overflow-hidden rounded-3">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->translated_title }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                                    <div class="gallery-overlay">
                                        <h5 class="text-white">{{ $item->translated_title }}</h5>
                                        @if($item->translated_description)
                                            <p class="text-white-50">{{ Str::limit($item->translated_description, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">@lang('No photos available yet.')</p>
                    </div>
                @endif
            </div>

            <!-- Videos Tab -->
            <div class="tab-pane fade" id="videos" role="tabpanel">
                @if($videos->count() > 0)
                    <div class="row g-4">
                        @foreach($videos as $item)
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-item position-relative overflow-hidden rounded-3">
                                    @if($item->thumbnail)
                                        <img src="{{ $item->thumbnail }}" alt="{{ $item->translated_title }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 250px;">
                                            <i class="bi bi-play-circle text-white" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    <div class="gallery-overlay">
                                        <h5 class="text-white">{{ $item->translated_title }}</h5>
                                        <a href="{{ $item->video_embed_url }}" class="btn btn-light btn-sm mt-2" target="_blank">
                                            <i class="bi bi-play"></i> @lang('Watch Video')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-play-circle text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">@lang('No videos available yet.')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2>@lang('Want to see more?')</h2>
        <p class="lead mb-4">@lang('Follow us on social media for the latest updates and photos.')</p>
        <a href="{{ locale_route('contact') }}" class="btn btn-success btn-lg">
            <i class="bi bi-chat-dots"></i> @lang('Contact Us')
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
.gallery-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 80px 0;
}
.gallery-item {
    cursor: pointer;
    transition: transform 0.3s ease;
}
.gallery-item:hover {
    transform: scale(1.02);
}
.gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-item:hover .gallery-overlay {
    opacity: 1;
}
.nav-pills .nav-link {
    color: var(--primary);
    border-radius: 25px;
    padding: 10px 25px;
    margin: 0 5px;
}
.nav-pills .nav-link.active {
    background-color: var(--primary);
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#galleryTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>
@endpush
