<?php
use App\Models\HeroTab;
use Illuminate\Support\Facades\Cache;

// Get active tabs with fallback
$activeTabs = Cache::remember('hero_active_tabs', 600, function() {
    try {
        return \App\Models\HeroTab::where('is_active', 1)->orderBy('order')->get() ?? collect();
    } catch (\Exception $e) {
        return collect();
    }
});
use App\Models\CMS\Setting;
?>

<!-- Dynamic Hero Section Component -->
<section class="hero-section" id="heroSection">
    <!-- Background Pattern -->
    <div class="hero-bg-pattern"></div>
    
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
                        @foreach($activeTabs as $index => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                        id="{{ $tab->tab_key }}-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#{{ $tab->tab_key }}-content"
                                        type="button" 
                                        role="tab">
                                    <i class="{{ $tab->icon ?? 'fas fa-plane' }}"></i>
                                    <span>{{ $tab->translated_label }}</span>
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
/* Hero Section Styles */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color, #343C90) 0%, #252E72 100%);
    color: #fff;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
    min-height: 650px;
}

.hero-bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 30px;
    line-height: 1.6;
}

.trust-badges {
    display: flex;
    gap: 25px;
}

.badge-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    background: rgba(255,255,255,0.15);
    padding: 8px 15px;
    border-radius: 25px;
}

.badge-item i {
    color: #ffd700;
}

/* Hero Booking Card */
.hero-form-wrapper {
    position: relative;
    z-index: 2;
}

.hero-booking-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.booking-tabs {
    background: #f8f9fa;
    border-bottom: none;
    padding: 15px 15px 0;
}

.booking-tabs .nav-link {
    border: none;
    border-radius: 10px 10px 0 0;
    color: #666;
    font-weight: 500;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.booking-tabs .nav-link i {
    font-size: 18px;
}

.booking-tabs .nav-link.active {
    background: #fff;
    color: var(--primary-color, #343C90);
    box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
}

.booking-tabs .nav-link:hover:not(.active) {
    color: var(--primary-color, #343C90);
}

.booking-content {
    padding: 25px;
    background: #fff;
}

.form-label {
    font-weight: 500;
    color: #333;
    font-size: 14px;
    margin-bottom: 6px;
}

.form-control, .form-select {
    border-radius: 10px;
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color, #343C90);
    box-shadow: 0 0 0 3px rgba(0,108,53,0.1);
}

.btn-primary {
    background: var(--primary-color, #343C90);
    border: none;
    padding: 14px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #252E72;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,108,53,0.3);
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
