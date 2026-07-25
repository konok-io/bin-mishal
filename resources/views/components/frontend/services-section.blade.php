<?php
use App\Models\HeroTab;
use Illuminate\Support\Facades\Cache;

// Get active tabs with fallback
$activeTabs = Cache::remember('services_active_tabs', 600, function() {
    try {
        return \App\Models\HeroTab::where('is_active', 1)->orderBy('order')->get() ?? collect();
    } catch (\Exception $e) {
        return collect();
    }
});
?>

<!-- Services Section Component -->
<section class="services-section py-5" id="servicesSection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5">
            <span class="section-badge">@lang('services.our_services')</span>
            <h2 class="section-title">@lang('services.title')</h2>
            <p class="section-subtitle">@lang('services.subtitle')</p>
        </div>
        
        <!-- Services Grid -->
        <div class="row g-4">
            @foreach($activeTabs as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="{{ $service->icon ?? 'fas fa-plane' }}"></i>
                        </div>
                        <div class="service-content">
                            <h3 class="service-title">{{ $service->translated_label }}</h3>
                            <p class="service-description">
                                {{ $service->translated_description ?? $service->translated_subtitle }}
                            </p>
                            
                            <!-- Features List -->
                            @if($service->translated_features && count($service->translated_features) > 0)
                                <ul class="service-features">
                                    @foreach($service->translated_features as $feature)
                                        <li><i class="fas fa-check-circle"></i> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            <a href="{{ $service->button_url_resolved ?? '#' }}" class="btn btn-outline-primary service-btn">
                                @lang('common.learn_more') <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="service-overlay"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-5">
            <a href="{{ route('services.all', ['locale' => app()->getLocale()]) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-th-large"></i> @lang('services.view_all_services')
            </a>
        </div>
    </div>
</section>

<style>
/* Services Section */
.services-section {
    background: #f8fafc;
    position: relative;
}

.section-header {
    margin-bottom: 50px;
}

.section-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-color, #006C35), var(--secondary-color, #C8A951));
    color: #fff;
    padding: 6px 20px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 15px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

/* Service Cards */
.service-card {
    background: #fff;
    border-radius: 20px;
    padding: 35px 30px;
    position: relative;
    overflow: hidden;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.service-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color, #006C35), #005c2e);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    transition: all 0.4s ease;
}

.service-icon i {
    font-size: 32px;
    color: #fff;
}

.service-card:hover .service-icon {
    transform: scale(1.1) rotate(5deg);
    background: linear-gradient(135deg, var(--secondary-color, #C8A951), #b89941);
}

.service-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 12px;
}

.service-description {
    color: #666;
    line-height: 1.7;
    margin-bottom: 20px;
    flex-grow: 1;
}

.service-features {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.service-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #555;
    font-size: 14px;
    border-bottom: 1px solid #f0f0f0;
}

.service-features li:last-child {
    border-bottom: none;
}

.service-features li i {
    color: var(--primary-color, #006C35);
    font-size: 12px;
}

.service-btn {
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.service-btn:hover {
    background: var(--primary-color, #006C35);
    border-color: var(--primary-color, #006C35);
    color: #fff;
}

.service-btn i {
    transition: transform 0.3s;
}

.service-btn:hover i {
    transform: translateX(5px);
}

.service-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color, #006C35), var(--secondary-color, #C8A951));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
}

.service-card:hover .service-overlay {
    transform: scaleX(1);
}

/* Responsive */
@media (max-width: 991px) {
    .section-title {
        font-size: 2rem;
    }
    
    .service-card {
        padding: 25px 20px;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
    }
    
    .service-icon i {
        font-size: 24px;
    }
}

@media (max-width: 767px) {
    .section-title {
        font-size: 1.8rem;
    }
    
    .service-card {
        text-align: center;
    }
    
    .service-features {
        text-align: left;
    }
}
</style>
