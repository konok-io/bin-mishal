<?php
use App\Models\GalleryItem;
?>

<!-- Gallery Section Component -->
<section class="gallery-section py-5" id="gallerySection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5">
            <span class="section-badge">@lang('gallery.title')</span>
            <h2 class="section-title">@lang('gallery.subtitle')</h2>
            <p class="section-subtitle">@lang('gallery.description')</p>
        </div>
        
        <!-- Gallery Grid -->
        <div class="row g-4">
            @foreach(GalleryItem::active()->photos()->featured()->take(8)->get() as $item)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="gallery-item">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->translated_title }}" class="img-fluid">
                        @else
                            <div class="gallery-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>{{ $item->translated_title }}</h4>
                                @if($item->translated_description)
                                    <p>{{ $item->translated_description }}</p>
                                @endif
                                <a href="{{ $item->image_url ?? '#' }}" class="gallery-link" data-lightbox="gallery">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Default Gallery if no data -->
            @if(GalleryItem::active()->photos()->take(8)->get()->isEmpty())
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=400" alt="Masjid al-Haram" class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>@lang('gallery.default_1_title')</h4>
                                <a href="#" class="gallery-link"><i class="fas fa-expand"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1564769625905-50e93615e769?w=400" alt="Makkah" class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>@lang('gallery.default_2_title')</h4>
                                <a href="#" class="gallery-link"><i class="fas fa-expand"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=400" alt="Airplane" class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>@lang('gallery.default_3_title')</h4>
                                <a href="#" class="gallery-link"><i class="fas fa-expand"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=400" alt=" Jeddah" class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>@lang('gallery.default_4_title')</h4>
                                <a href="#" class="gallery-link"><i class="fas fa-expand"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- View All -->
        <div class="text-center mt-5">
            <a href="{{ route('gallery', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-images"></i> @lang('gallery.view_all')
            </a>
        </div>
    </div>
</section>

<style>
/* Gallery Section */
.gallery-section {
    background: #fff;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    aspect-ratio: 1;
    cursor: pointer;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

.gallery-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-placeholder i {
    font-size: 50px;
    color: #ccc;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    display: flex;
    align-items: flex-end;
    padding: 20px;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-content {
    color: #fff;
    transform: translateY(20px);
    transition: transform 0.4s ease;
}

.gallery-item:hover .gallery-content {
    transform: translateY(0);
}

.gallery-content h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.gallery-content p {
    font-size: 0.85rem;
    opacity: 0.8;
    margin-bottom: 10px;
}

.gallery-link {
    width: 40px;
    height: 40px;
    background: var(--primary-color, #343C90);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.gallery-link:hover {
    background: var(--secondary-color, #E05522);
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 767px) {
    .gallery-item {
        aspect-ratio: 4/3;
    }
}
</style>
