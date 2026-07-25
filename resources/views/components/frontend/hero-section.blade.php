<?php
use App\Models\HeroTab;
use App\Models\CMS\Setting;
use Illuminate\Support\Facades\Cache;

// Default tabs array
$defaultTabsArray = [
    [
        'tab_key' => 'flight',
        'icon' => 'fas fa-plane',
        'label' => ['en' => 'Flight', 'bn' => 'ফ্লাইট', 'ar' => 'رحلة طيران'],
    ],
    [
        'tab_key' => 'umrah',
        'icon' => 'fas fa-kaaba',
        'label' => ['en' => 'Umrah', 'bn' => 'উমরাহ', 'ar' => 'عمرة'],
    ],
    [
        'tab_key' => 'visa',
        'icon' => 'fas fa-passport',
        'label' => ['en' => 'Visa', 'bn' => 'ভিসা', 'ar' => 'تأشيرة'],
    ],
    [
        'tab_key' => 'cargo',
        'icon' => 'fas fa-box',
        'label' => ['en' => 'Cargo', 'bn' => 'কার্গো', 'ar' => 'شحن'],
    ],
    [
        'tab_key' => 'appointment',
        'icon' => 'fas fa-calendar-check',
        'label' => ['en' => 'Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট', 'ar' => 'موعد'],
    ],
    [
        'tab_key' => 'investor',
        'icon' => 'fas fa-chart-line',
        'label' => ['en' => 'Investor', 'bn' => 'বিনিয়োগকারী', 'ar' => 'مستثمر'],
    ],
];

// Try to get tabs from database
try {
    $tabsCollection = \App\Models\HeroTab::where('is_active', 1)->orderBy('order')->get();
    // Convert to array immediately to avoid serialization issues
    $dbTabsArray = $tabsCollection->map(function($tab) {
        return [
            'tab_key' => $tab->tab_key,
            'icon' => $tab->icon ?? 'fas fa-plane',
            'label' => is_string($tab->label) ? json_decode($tab->label, true) : ($tab->label ?? ['en' => 'Service']),
        ];
    })->toArray();
    $displayTabs = !empty($dbTabsArray) ? $dbTabsArray : $defaultTabsArray;
} catch (\Exception $e) {
    $displayTabs = $defaultTabsArray;
}

// Helper function for translated label
function getHeroTabLabel($tab) {
    $locale = app()->getLocale();
    $label = $tab['label'] ?? 'Service';
    if (is_array($label)) {
        return $label[$locale] ?? $label['en'] ?? 'Service';
    }
    return $label;
}

// Helper function for icon
function getHeroTabIcon($tab) {
    return $tab['icon'] ?? 'fas fa-plane';
}
?>

<!-- Dynamic Hero Section Component -->
<section class="hero-section" id="heroSection">
    <!-- Animated Background Elements -->
    <div class="hero-bg-wrapper">
        <div class="hero-gradient"></div>
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
        <div class="hero-particles"></div>
    </div>
    
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Content -->
            <div class="col-lg-6 hero-content-wrapper">
                <div class="hero-content" id="heroContent">
                    <!-- Dynamic Hero Title -->
                    <h1 class="hero-title">
                        {{ Setting::getValue('hero_title', 'Your Trusted Travel Partner') }}
                    </h1>
                    <p class="hero-subtitle">
                        {{ Setting::getValue('hero_subtitle', 'Book flights, Umrah packages, visas, cargo & more - all in one place') }}
                    </p>
                    
                    <!-- Trust Badges -->
                    <div class="trust-badges d-none d-md-flex">
                        <div class="badge-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>@lang('hero.licensed_operator')</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-headset"></i>
                            <span>@lang('hero.24_7_support')</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-award"></i>
                            <span>@lang('hero.trusted_company')</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right: Booking Form -->
            <div class="col-lg-6 hero-form-wrapper">
                <div class="hero-booking-card">
                    <!-- Service Tabs -->
                    <ul class="nav nav-tabs booking-tabs" id="bookingTabs" role="tablist">
                        @foreach($displayTabs as $index => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                        id="{{ $tab['tab_key'] }}-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#{{ $tab['tab_key'] }}-content"
                                        type="button" 
                                        role="tab">
                                    <i class="{{ $tab['icon'] ?? 'fas fa-plane' }}"></i>
                                    <span>{{ getHeroTabLabel($tab) }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content booking-content" id="bookingTabsContent">
                        <!-- Flight Booking Form -->
                        <div class="tab-pane fade show active" id="flight-content" role="tabpanel">
                            <form action="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.from')</label>
                                        <select name="from" class="form-select" required>
                                            <option value="">@lang('booking.select_city')</option>
                                            <option value="Jeddah">Jeddah (JED)</option>
                                            <option value="Riyadh">Riyadh (RUH)</option>
                                            <option value="Dammam">Dammam (DMM)</option>
                                            <option value="Medina">Medina (MED)</option>
                                            <option value="Dhaka">Dhaka (DAC)</option>
                                            <option value="Chittagong">Chittagong (CGP)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.to')</label>
                                        <select name="to" class="form-select" required>
                                            <option value="">@lang('booking.select_city')</option>
                                            <option value="Jeddah">Jeddah (JED)</option>
                                            <option value="Riyadh">Riyadh (RUH)</option>
                                            <option value="Dammam">Dammam (DMM)</option>
                                            <option value="Medina">Medina (MED)</option>
                                            <option value="Dhaka">Dhaka (DAC)</option>
                                            <option value="Chittagong">Chittagong (CGP)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.departure_date')</label>
                                        <input type="date" name="departure_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.return_date')</label>
                                        <input type="date" name="return_date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">@lang('booking.adults')</label>
                                        <select name="adults" class="form-select">
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">@lang('booking.children')</label>
                                        <select name="children" class="form-select">
                                            @for($i = 0; $i <= 9; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">@lang('booking.infants')</label>
                                        <select name="infants" class="form-select">
                                            @for($i = 0; $i <= 4; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i> @lang('booking.search_flights')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Umrah Form -->
                        <div class="tab-pane fade" id="umrah-content" role="tabpanel">
                            <form action="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.travel_date')</label>
                                        <input type="date" name="travel_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.duration')</label>
                                        <select name="duration" class="form-select">
                                            <option value="7">7 Days</option>
                                            <option value="14">14 Days</option>
                                            <option value="21">21 Days</option>
                                            <option value="30">30 Days</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.passengers')</label>
                                        <select name="passengers" class="form-select">
                                            @for($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}">{{ $i }} @lang('booking.persons')</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.hotel_rating')</label>
                                        <select name="hotel_rating" class="form-select">
                                            <option value="3">⭐⭐⭐ 3 Star</option>
                                            <option value="4">⭐⭐⭐⭐ 4 Star</option>
                                            <option value="5">⭐⭐⭐⭐⭐ 5 Star</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i> @lang('booking.search_packages')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Visa Form -->
                        <div class="tab-pane fade" id="visa-content" role="tabpanel">
                            <form action="{{ route('services.visa', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.visa_type')</label>
                                        <select name="visa_type" class="form-select" required>
                                            <option value="">@lang('booking.select_visa')</option>
                                            <option value="tourist">Tourist Visa</option>
                                            <option value="business">Business Visa</option>
                                            <option value="work">Work Visa</option>
                                            <option value="family">Family Visit Visa</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.country')</label>
                                        <select name="country" class="form-select">
                                            <option value="saudi">Saudi Arabia</option>
                                            <option value="uae">UAE</option>
                                            <option value="qatar">Qatar</option>
                                            <option value="bahrain">Bahrain</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.entry_type')</label>
                                        <select name="entry_type" class="form-select">
                                            <option value="single">Single Entry</option>
                                            <option value="multiple">Multiple Entry</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.duration_days')</label>
                                        <select name="duration_days" class="form-select">
                                            <option value="30">30 Days</option>
                                            <option value="60">60 Days</option>
                                            <option value="90">90 Days</option>
                                            <option value="180">180 Days</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-passport"></i> @lang('booking.apply_visa')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Cargo Form -->
                        <div class="tab-pane fade" id="cargo-content" role="tabpanel">
                            <form action="{{ route('cargo.calculate', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.from_country')</label>
                                        <select name="from_country" class="form-select" required>
                                            <option value="">@lang('booking.select_country')</option>
                                            <option value="saudi">🇸🇦 Saudi Arabia</option>
                                            <option value="uae">🇦🇪 UAE</option>
                                            <option value="qatar">🇶🇦 Qatar</option>
                                            <option value="bahrain">🇧🇭 Bahrain</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.to_country')</label>
                                        <select name="to_country" class="form-select" required>
                                            <option value="">@lang('booking.select_country')</option>
                                            <option value="bangladesh">🇧🇩 Bangladesh</option>
                                            <option value="india">🇮🇳 India</option>
                                            <option value="pakistan">🇵🇰 Pakistan</option>
                                            <option value="nepal">🇳🇵 Nepal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.cargo_type')</label>
                                        <select name="cargo_type" class="form-select">
                                            <option value="air">✈️ Air Cargo</option>
                                            <option value="sea">🚢 Sea Cargo</option>
                                            <option value="door">🚚 Door to Door</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.weight_kg')</label>
                                        <input type="number" name="weight" class="form-control" placeholder="e.g. 25" min="1" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-calculator"></i> @lang('booking.calculate_price')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Appointment Form -->
                        <div class="tab-pane fade" id="appointment-content" role="tabpanel">
                            <form action="{{ route('appointment', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.service_type')</label>
                                        <select name="service_type" class="form-select" required>
                                            <option value="">@lang('booking.select_service')</option>
                                            <option value="consultation">Visa Consultation</option>
                                            <option value="document">Document Review</option>
                                            <option value="investment">Investment Inquiry</option>
                                            <option value="general">General Inquiry</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.preferred_date')</label>
                                        <input type="date" name="preferred_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.preferred_time')</label>
                                        <select name="preferred_time" class="form-select">
                                            <option value="morning">🌅 Morning (9AM-12PM)</option>
                                            <option value="afternoon">☀️ Afternoon (12PM-4PM)</option>
                                            <option value="evening">🌙 Evening (4PM-6PM)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.branch')</label>
                                        <select name="branch" class="form-select">
                                            <option value="">@lang('booking.select_branch')</option>
                                            <option value="riyadh">🏢 Riyadh</option>
                                            <option value="jeddah">🏢 Jeddah</option>
                                            <option value="dammam">🏢 Dammam</option>
                                            <option value="dhaka">🏢 Dhaka</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-calendar-check"></i> @lang('booking.book_appointment')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Investor Form -->
                        <div class="tab-pane fade" id="investor-content" role="tabpanel">
                            <form action="{{ route('investor', ['locale' => app()->getLocale()]) }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.service_interest')</label>
                                        <select name="service_interest" class="form-select" required>
                                            <option value="">@lang('booking.select_interest')</option>
                                            <option value="misa">📋 MISA License</option>
                                            <option value="investment">💼 Foreign Investment</option>
                                            <option value="company">🏢 Company Registration</option>
                                            <option value="branch">🏪 Branch Registration</option>
                                            <option value="consultation">📞 General Consultation</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('booking.investment_range')</label>
                                        <select name="investment_range" class="form-select">
                                            <option value="">@lang('booking.select_range')</option>
                                            <option value="small">💰 Small (Under 500K SAR)</option>
                                            <option value="medium">💰💰 Medium (500K-5M SAR)</option>
                                            <option value="large">💰💰💰 Large (Above 5M SAR)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-handshake"></i> @lang('booking.inquire_now')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section Styles - Modern Gradient Design */
.hero-section {
    background: linear-gradient(135deg, #343C90 0%, #E05522 50%, #C94718 100%);
    color: #fff;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
    min-height: 650px;
}

/* Animated Background Wrapper */
.hero-bg-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

/* Main Gradient Overlay */
.hero-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, 
        rgba(52, 60, 144, 0.95) 0%, 
        rgba(224, 85, 34, 0.9) 50%, 
        rgba(201, 71, 24, 0.85) 100%);
    z-index: 1;
}

/* Floating Shapes */
.hero-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    z-index: 0;
}

.hero-shape-1 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #fff 0%, transparent 70%);
    top: -100px;
    right: -100px;
    animation: float1 15s ease-in-out infinite;
}

.hero-shape-2 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #E05522 0%, transparent 70%);
    bottom: -50px;
    left: -50px;
    animation: float2 12s ease-in-out infinite;
}

.hero-shape-3 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #343C90 0%, transparent 70%);
    top: 50%;
    left: 30%;
    animation: float3 10s ease-in-out infinite;
}

/* Floating Animations */
@keyframes float1 {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(-30px, 30px) rotate(5deg); }
    66% { transform: translate(30px, -30px) rotate(-5deg); }
}

@keyframes float2 {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(40px, -40px) scale(1.1); }
}

@keyframes float3 {
    0%, 100% { transform: translate(0, 0); }
    25% { transform: translate(20px, -20px); }
    75% { transform: translate(-20px, 20px); }
}

/* Particle Effect */
.hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 1px, transparent 1px),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 1px, transparent 1px),
        radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 2px, transparent 2px),
        radial-gradient(circle at 60% 70%, rgba(255,255,255,0.06) 1px, transparent 1px);
    background-size: 100px 100px, 150px 150px, 80px 80px, 120px 120px;
    animation: particles 20s linear infinite;
    z-index: 0;
}

@keyframes particles {
    0% { background-position: 0 0, 0 0, 0 0, 0 0; }
    100% { background-position: 100px 100px, -150px 150px, -80px -80px, 120px -120px; }
}

/* Content Wrapper */
.hero-content-wrapper {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 30px;
    line-height: 1.6;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.trust-badges {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.badge-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    padding: 10px 18px;
    border-radius: 30px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s;
}

.badge-item:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.badge-item i {
    color: #ffd700;
    font-size: 14px;
}

/* Hero Booking Card - Glassmorphism Effect */
.hero-form-wrapper {
    position: relative;
    z-index: 2;
}

.hero-booking-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow: 
        0 25px 50px rgba(0,0,0,0.3),
        0 0 0 1px rgba(255,255,255,0.1) inset;
    overflow: hidden;
    color: #1F2937;
    border: 1px solid rgba(255,255,255,0.3);
}

.hero-booking-card .form-control,
.hero-booking-card .form-select {
    background: #f8fafc;
    color: #1F2937;
    border: 2px solid #e5e7eb;
}

.hero-booking-card .form-control:focus,
.hero-booking-card .form-select:focus {
    background: #fff;
    color: #1F2937;
    border-color: #343C90;
    box-shadow: 0 0 0 4px rgba(52, 60, 144, 0.15);
}

.hero-booking-card label {
    color: #374151;
    font-weight: 600;
}

.hero-booking-card .btn {
    color: #fff;
}

.booking-tabs {
    background: linear-gradient(135deg, #343C90 0%, #4A5299 100%);
    border-bottom: none;
    padding: 15px 10px 0;
    display: flex;
    justify-content: center;
    flex-wrap: nowrap;
}

.booking-tabs .nav-item {
    white-space: nowrap;
}

.booking-tabs .nav-link {
    border: none;
    border-radius: 12px 12px 0 0;
    color: rgba(255,255,255,0.7);
    font-weight: 500;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
    font-size: 13px;
}

.booking-tabs .nav-link i {
    font-size: 14px;
}

.booking-tabs .nav-link.active {
    background: #fff;
    color: #343C90;
    box-shadow: 0 -4px 15px rgba(0,0,0,0.15);
    font-weight: 600;
}

.booking-tabs .nav-link:hover:not(.active) {
    color: #fff;
    background: rgba(255,255,255,0.1);
}

.booking-content {
    padding: 25px;
    background: transparent;
}

.form-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    margin-bottom: 6px;
}

.form-control, .form-select {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #e5e7eb;
    transition: all 0.3s;
    background: #f8fafc;
    color: #1F2937;
}

.form-control:focus, .form-select:focus {
    border-color: #343C90;
    box-shadow: 0 0 0 4px rgba(52, 60, 144, 0.1);
    background: #fff;
}

.btn-primary {
    background: linear-gradient(135deg, #E05522 0%, #C94718 100%);
    border: none;
    padding: 14px 25px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
    color: #fff;
    box-shadow: 0 4px 15px rgba(224, 85, 34, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #C94718 0%, #A73C16 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(224, 85, 34, 0.4);
}

/* Responsive */
@media (max-width: 991px) {
    .hero-section {
        padding: 50px 0;
        min-height: auto;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .trust-badges {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .booking-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 15px;
    }
    
    .booking-tabs .nav-link {
        white-space: nowrap;
        font-size: 13px;
    }
    
    .booking-tabs .nav-link span {
        display: none;
    }
}

@media (max-width: 767px) {
    .hero-section {
        padding: 30px 0;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
}
</style>
