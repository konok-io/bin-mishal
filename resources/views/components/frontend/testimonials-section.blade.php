<?php
use App\Models\Testimonial;

// Get testimonials from database
try {
    $dbTestimonials = Testimonial::active()->featured()->take(6)->get();
    $hasTestimonials = $dbTestimonials->isNotEmpty();
} catch (\Exception $e) {
    $hasTestimonials = false;
    $dbTestimonials = collect();
}

// Default testimonials fallback
$defaultTestimonials = [
    [
        'initial' => 'আ',
        'name' => ['en' => 'Ahmed Hasan', 'bn' => 'আহমেদ হাসান', 'ar' => 'أحمد الحسن'],
        'designation' => ['en' => 'Business Executive', 'bn' => 'ব্যবসায়ী', 'ar' => 'مدير أعمال'],
        'location' => 'Dhaka, Bangladesh',
        'quote' => ['en' => 'Excellent service from Bin Mishal Travels! From Umrah packages to visa processing, everything was smooth and quick. The staff was incredibly helpful throughout our journey.', 'bn' => 'বিন মিশাল ট্রাভেলসের সেবা অসাধারণ! উমরাহ প্যাকেজ থেকে ভিসা প্রসেসিং সব কিছুই অনেক সহজ এবং দ্রুত হয়েছে।', 'ar' => 'خدمة ممتازة من بن ميشال للسفر! من باقات العمرة إلى معالجة التأشيرة، كان كل شيء سلسًا وسريعًا.'],
        'rating' => 5
    ],
    [
        'initial' => 'ম',
        'name' => ['en' => 'Mohammad Ali', 'bn' => 'মোহাম্মদ আলী', 'ar' => 'محمد علي'],
        'designation' => ['en' => 'Travel Agent', 'bn' => 'ট্রাভেল এজেন্ট', 'ar' => 'وكيل سفر'],
        'location' => 'Riyadh, Saudi Arabia',
        'quote' => ['en' => 'Been working with Bin Mishal for 5 years. Their flight booking system is top-notch and the rates are always competitive. Highly recommended for travel businesses.', 'bn' => '৫ বছর ধরে বিন মিশালের সাথে কাজ করছি। তাদের ফ্লাইট বুকিং সিস্টেম অসাধারণ এবং রেট সবসময় প্রতিযোগিতামূলক।', 'ar' => 'أعمل مع بن ميشال منذ 5 سنوات. نظام حجز الرحلات لديهم ممتاز والأسعار دائمًا تنافسية.'],
        'rating' => 5
    ],
    [
        'initial' => 'র',
        'name' => ['en' => 'Rahim Sheikh', 'bn' => 'রহিম শেখ', 'ar' => 'راهيم شيخ'],
        'designation' => ['en' => 'Umrah Pilgrim', 'bn' => 'উমরাহ তীর্থযাত্রী', 'ar' => 'معتمر'],
        'location' => 'Jeddah, Saudi Arabia',
        'quote' => ['en' => 'Our family Umrah trip was perfectly organized. Hotels were excellent, transport was comfortable, and the guided tours made our spiritual journey unforgettable.', 'bn' => 'আমাদের পরিবারের উমরাহ ট্রিপ দুর্দান্তভাবে সংগঠিত হয়েছিল। হোটেলগুলো দুর্দান্ত ছিল এবং গাইডেড ট্যুর আমাদের আধ্যাত্মিক যাত্রাকে অবিস্মরণীয় করেছে।', 'ar' => 'كانت رحلة العمرة العائلية منظمة بشكل مثالي. كانت الفنادق ممتازة والجولات المصحوبة جعلت رحلتنا الروحية لا تُنسى.'],
        'rating' => 5
    ]
];

// Helper function
function getLocalizedTestimonial($array, $locale = 'en') {
    if (is_array($array)) {
        return $array[$locale] ?? $array['en'] ?? reset($array);
    }
    return $array;
}
?>

<!-- Testimonials Section Component -->
<section class="testimonials-section py-5" id="testimonialsSection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-badge">@lang('testimonials.title')</span>
            <h2 class="section-title">@lang('testimonials.subtitle')</h2>
            <p class="section-subtitle">@lang('testimonials.description')</p>
        </div>
        
        <!-- Testimonials Carousel -->
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up">
            <div class="carousel-inner">
                @if($hasTestimonials)
                    @foreach($dbTestimonials as $index => $testimonial)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="6000">
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
                                                    <span class="company"><i class="fas fa-map-marker-alt me-1"></i>{{ $testimonial->company }}</span>
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
                @else
                    @foreach($defaultTestimonials as $index => $testimonial)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="6000">
                            <div class="testimonial-card">
                                <div class="row align-items-center">
                                    <div class="col-lg-4">
                                        <div class="testimonial-author">
                                            <div class="author-avatar-placeholder">{{ $testimonial['initial'] }}</div>
                                            <div class="author-info">
                                                <h4>{{ getLocalizedTestimonial($testimonial['name'], app()->getLocale()) }}</h4>
                                                <p>{{ getLocalizedTestimonial($testimonial['designation'], app()->getLocale()) }}</p>
                                                <span class="company"><i class="fas fa-map-marker-alt me-1"></i>{{ $testimonial['location'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="testimonial-content">
                                            <div class="quote-icon">
                                                <i class="fas fa-quote-left"></i>
                                            </div>
                                            <p class="testimonial-text">
                                                "{{ getLocalizedTestimonial($testimonial['quote'], app()->getLocale()) }}"
                                            </p>
                                            <div class="testimonial-rating">
                                                @for($i = 1; $i <= $testimonial['rating']; $i++)
                                                    <i class="fas fa-star filled"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                @if($hasTestimonials)
                    @foreach($dbTestimonials as $index => $testimonial)
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                @else
                    @foreach($defaultTestimonials as $index => $testimonial)
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<style>
/* Testimonials Section */
.testimonials-section {
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    position: relative;
    overflow: hidden;
}

.testimonials-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23343C90' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.testimonial-card {
    background: #fff;
    border-radius: 30px;
    padding: 50px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.04);
    margin: 20px 0;
    position: relative;
    z-index: 1;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding-right: 30px;
    border-right: 3px solid var(--primary-color, #E05522);
}

.author-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid var(--primary-color, #E05522);
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(52, 60, 144, 0.2);
}

.author-avatar-placeholder {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color, #E05522), var(--secondary-color, #343C90));
    color: #fff;
    font-size: 45px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(52, 60, 144, 0.3);
}

.author-info h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
}

.author-info p {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 8px;
}

.author-info .company {
    font-size: 0.9rem;
    color: var(--primary-color, #E05522);
    font-weight: 500;
}

.testimonial-content {
    position: relative;
    padding-left: 30px;
}

.quote-icon {
    position: absolute;
    top: -15px;
    left: 10px;
    font-size: 60px;
    color: var(--secondary-color, #343C90);
    opacity: 0.2;
}

.testimonial-text {
    font-size: 1.15rem;
    line-height: 1.9;
    color: #444;
    font-style: italic;
    margin-bottom: 25px;
}

.testimonial-rating {
    display: flex;
    gap: 5px;
}

.testimonial-rating .fa-star {
    font-size: 18px;
}

.testimonial-rating .fa-star.filled {
    color: #ffc107;
    filter: drop-shadow(0 2px 4px rgba(255, 193, 7, 0.3));
}

.testimonial-rating .fa-star.empty {
    color: #ddd;
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, var(--primary-color, #E05522), var(--secondary-color, #343C90));
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 1;
    position: absolute;
    box-shadow: 0 8px 25px rgba(52, 60, 144, 0.3);
    transition: all 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 12px 35px rgba(52, 60, 144, 0.4);
}

.carousel-control-icon {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
}

.carousel-indicators {
    position: relative;
    bottom: 0;
    margin-top: 35px;
    gap: 12px;
}

.carousel-indicators button {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ddd;
    border: none;
    opacity: 1;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    background: linear-gradient(135deg, var(--primary-color, #E05522), var(--secondary-color, #343C90));
    width: 40px;
    border-radius: 7px;
}

/* Responsive */
@media (max-width: 991px) {
    .testimonial-card {
        padding: 35px 25px;
    }
    
    .testimonial-author {
        border-right: none;
        border-bottom: 3px solid var(--primary-color, #E05522);
        padding-right: 0;
        padding-bottom: 30px;
        margin-bottom: 30px;
    }
    
    .testimonial-content {
        padding-left: 0;
        text-align: center;
    }
    
    .quote-icon {
        position: relative;
        top: 0;
        margin-bottom: 20px;
    }
    
    .testimonial-rating {
        justify-content: center;
    }
    
    .author-avatar,
    .author-avatar-placeholder {
        width: 90px;
        height: 90px;
        font-size: 36px;
    }
}

@media (max-width: 767px) {
    .testimonial-text {
        font-size: 1rem;
    }
}
</style>
