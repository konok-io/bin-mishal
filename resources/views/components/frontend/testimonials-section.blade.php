<?php
use App\Models\Testimonial;
?>

<!-- Testimonials Section Component -->
<section class="testimonials-section py-5" id="testimonialsSection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5">
            <span class="section-badge">@lang('testimonials.title')</span>
            <h2 class="section-title">@lang('testimonials.subtitle')</h2>
            <p class="section-subtitle">@lang('testimonials.description')</p>
        </div>
        
        <!-- Testimonials Carousel -->
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach(Testimonial::active()->featured()->take(6)->get() as $index => $testimonial)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="5000">
                        <div class="testimonial-card">
                            <div class="row align-items-center">
                                <div class="col-lg-4">
                                    <div class="testimonial-author">
                                        @if($testimonial->avatar)
                                            <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="author-avatar">
                                        @else
                                            <div class="author-avatar-placeholder">
                                                {{ substr($testimonial->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="author-info">
                                            <h4>{{ $testimonial->name }}</h4>
                                            <p>{{ $testimonial->designation }}</p>
                                            @if($testimonial->company)
                                                <span class="company">{{ $testimonial->company }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <i class="fas fa-quote-left"></i>
                                        </div>
                                        <p class="testimonial-text">
                                            "{{ $testimonial->quote }}"
                                        </p>
                                        <div class="testimonial-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= ($testimonial->rating ?? 5) ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Default Testimonials if no data -->
                @if(Testimonial::active()->featured()->take(6)->get()->isEmpty())
                    <div class="carousel-item active" data-bs-interval="5000">
                        <div class="testimonial-card">
                            <div class="row align-items-center">
                                <div class="col-lg-4">
                                    <div class="testimonial-author">
                                        <div class="author-avatar-placeholder">আ</div>
                                        <div class="author-info">
                                            <h4>আহমেদ হাসান</h4>
                                            <p>Business Executive</p>
                                            <span class="company">Dhaka, Bangladesh</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <i class="fas fa-quote-left"></i>
                                        </div>
                                        <p class="testimonial-text">
                                            "Bin Mishal Travels এর সেবা অসাধারণ! ওমরাহ প্যাকেজ থেকে শুরু করে ভিসা সব কিছুই অনেক সহজ এবং দ্রুত হয়েছে। তাদের স্টাফরা অনেক সাহায্যকারী।"
                                        </p>
                                        <div class="testimonial-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star filled"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-icon"><i class="fas fa-chevron-left"></i></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-icon"><i class="fas fa-chevron-right"></i></span>
            </button>
            
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                @foreach(Testimonial::active()->featured()->take(6)->get() as $index => $testimonial)
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
                @if(Testimonial::active()->featured()->take(6)->get()->isEmpty())
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active"></button>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
/* Testimonials Section */
.testimonials-section {
    background: #fff;
    position: relative;
}

.testimonial-card {
    background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
    border-radius: 25px;
    padding: 50px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    border: 1px solid #eee;
    margin: 20px 0;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding-right: 30px;
    border-right: 2px solid var(--primary-color, #006C35);
}

.author-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--primary-color, #006C35);
    margin-bottom: 15px;
}

.author-avatar-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color, #006C35), var(--secondary-color, #C8A951));
    color: #fff;
    font-size: 40px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.author-info h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 5px;
}

.author-info p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 5px;
}

.author-info .company {
    font-size: 0.85rem;
    color: var(--primary-color, #006C35);
    font-weight: 500;
}

.testimonial-content {
    position: relative;
    padding-left: 20px;
}

.quote-icon {
    position: absolute;
    top: -10px;
    left: 0;
    font-size: 50px;
    color: var(--secondary-color, #C8A951);
    opacity: 0.3;
}

.testimonial-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
    font-style: italic;
    margin-bottom: 20px;
}

.testimonial-rating {
    display: flex;
    gap: 5px;
}

.testimonial-rating .fa-star {
    font-size: 16px;
}

.testimonial-rating .fa-star.filled {
    color: #ffc107;
}

.testimonial-rating .fa-star.empty {
    color: #ddd;
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    height: 50px;
    background: var(--primary-color, #006C35);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 1;
    position: absolute;
}

.carousel-control-icon {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
}

.carousel-indicators {
    position: relative;
    bottom: 0;
    margin-top: 30px;
    gap: 10px;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    border: none;
    opacity: 1;
}

.carousel-indicators button.active {
    background: var(--primary-color, #006C35);
    width: 30px;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 991px) {
    .testimonial-card {
        padding: 30px 25px;
    }
    
    .testimonial-author {
        border-right: none;
        border-bottom: 2px solid var(--primary-color, #006C35);
        padding-right: 0;
        padding-bottom: 25px;
        margin-bottom: 25px;
    }
    
    .testimonial-content {
        padding-left: 0;
        text-align: center;
    }
    
    .quote-icon {
        position: relative;
        top: 0;
        margin-bottom: 15px;
    }
    
    .testimonial-rating {
        justify-content: center;
    }
}
</style>
