<?php
use App\Models\HeroTab;
use App\Models\CMS\Setting;
use Illuminate\Support\Facades\Cache;

// Default tabs array with full content
$defaultTabsArray = [
    [
        'tab_key' => 'flight',
        'icon' => 'fas fa-plane',
        'label' => ['en' => 'Flight', 'bn' => 'ফ্লাইট', 'ar' => 'رحلة طيران'],
        'title' => ['en' => 'Book Your Flight', 'bn' => 'আপনার ফ্লাইট বুক করুন', 'ar' => 'احجز رحلتك'],
        'subtitle' => ['en' => 'Best deals on domestic & international flights', 'bn' => 'দেশি ও আন্তর্জাতিক ফ্লাইটে সেরা অফার', 'ar' => 'أفضل العروض على الرحلات الداخلية والدولية'],
        'features' => [
            ['en' => '800+ Airlines', 'bn' => '৮০০+ এয়ারলাইন্স', 'ar' => '+800 شركة طيران'],
            ['en' => 'Best Prices', 'bn' => 'সেরা মূল্য', 'ar' => 'أفضل الأسعار'],
            ['en' => '24/7 Support', 'bn' => '২৪/৭ সাপোর্ট', 'ar' => 'دعم على مدار الساعة'],
        ],
    ],
    [
        'tab_key' => 'umrah',
        'icon' => 'fas fa-kaaba',
        'label' => ['en' => 'Umrah', 'bn' => 'উমরাহ', 'ar' => 'عمرة'],
        'title' => ['en' => 'Umrah Packages', 'bn' => 'উমরাহ প্যাকেজ', 'ar' => 'باقات العمرة'],
        'subtitle' => ['en' => 'Complete Umrah packages with visa & hotels', 'bn' => 'ভিসা ও হোটেল সহ সম্পূর্ণ উমরাহ প্যাকেজ', 'ar' => 'باقات عمرة كاملة مع التأشيرة والفنادق'],
        'features' => [
            ['en' => '5-Star Hotels', 'bn' => '৫ তারা হোটেল', 'ar' => 'فنادق 5 نجوم'],
            ['en' => 'Visa Included', 'bn' => 'ভিসা অন্তর্ভুক্ত', 'ar' => 'تأشيرة مشمولة'],
            ['en' => 'Group Discounts', 'bn' => 'গ্রুপ ডিসকাউন্ট', 'ar' => 'خصومات جماعية'],
        ],
    ],
    [
        'tab_key' => 'visa',
        'icon' => 'fas fa-passport',
        'label' => ['en' => 'Visa', 'bn' => 'ভিসা', 'ar' => 'تأشيرة'],
        'title' => ['en' => 'Visa Services', 'bn' => 'ভিসা সেবা', 'ar' => 'خدمات التأشيرة'],
        'subtitle' => ['en' => 'Fast & reliable visa processing for all countries', 'bn' => 'সব দেশের জন্য দ্রুত ও নির্ভরযোগ্য ভিসা প্রসেসিং', 'ar' => 'معالجة تأشيرات سريعة وموثوقة لجميع الدول'],
        'features' => [
            ['en' => 'Fast Processing', 'bn' => 'দ্রুত প্রসেসিং', 'ar' => 'معالجة سريعة'],
            ['en' => 'Expert Guidance', 'bn' => 'বিশেষজ্ঞ গাইডেন্স', 'ar' => 'إرشاد متخصص'],
            ['en' => 'High Success Rate', 'bn' => 'উচ্চ সাফল্য হার', 'ar' => 'معدل نجاح عالي'],
        ],
    ],
    [
        'tab_key' => 'cargo',
        'icon' => 'fas fa-box',
        'label' => ['en' => 'Cargo', 'bn' => 'কার্গো', 'ar' => 'شحن'],
        'title' => ['en' => 'Cargo Services', 'bn' => 'কার্গো সেবা', 'ar' => 'خدمات الشحن'],
        'subtitle' => ['en' => 'Secure & affordable shipping worldwide', 'bn' => 'বিশ্বব্যাপী নিরাপদ ও সাশ্রয়ী শিপিং', 'ar' => 'شحن آمن وبأسعار معقولة حول العالم'],
        'features' => [
            ['en' => 'Door to Door', 'bn' => 'ডোর টু ডোর', 'ar' => 'من الباب إلى الباب'],
            ['en' => 'Real-time Tracking', 'bn' => 'রিয়েল-টাইম ট্র্যাকিং', 'ar' => 'تتبع في الوقت الفعلي'],
            ['en' => 'Competitive Rates', 'bn' => 'প্রতিযোগিতামূলক রেট', 'ar' => 'أسعار تنافسية'],
        ],
    ],
    [
        'tab_key' => 'appointment',
        'icon' => 'fas fa-calendar-check',
        'label' => ['en' => 'Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট', 'ar' => 'موعد'],
        'title' => ['en' => 'Book Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট বুক করুন', 'ar' => 'احجز موعد'],
        'subtitle' => ['en' => 'Schedule a consultation with our experts', 'bn' => 'আমাদের বিশেষজ্ঞদের সাথে পরামর্শ নিন', 'ar' => 'حدد موعدًا للاستشارة مع خبرائنا'],
        'features' => [
            ['en' => 'Free Consultation', 'bn' => 'বিনামূল্যে পরামর্শ', 'ar' => 'استشارة مجانية'],
            ['en' => 'Flexible Timing', 'bn' => 'নমনীয় সময়', 'ar' => 'توقيت مرن'],
            ['en' => 'Online/Offline', 'bn' => 'অনলাইন/অফলাইন', 'ar' => 'عبر الإنترنت / بدون اتصال'],
        ],
    ],
    [
        'tab_key' => 'investor',
        'icon' => 'fas fa-chart-line',
        'label' => ['en' => 'Investor', 'bn' => 'বিনিয়োগকারী', 'ar' => 'مستثمر'],
        'title' => ['en' => 'Investment Services', 'bn' => 'বিনিয়োগ সেবা', 'ar' => 'خدمات الاستثمار'],
        'subtitle' => ['en' => 'MISA license & business setup in Saudi Arabia', 'bn' => 'সৌদি আরবে MISA লাইসেন্স ও ব্যবসা স্থাপন', 'ar' => 'ترخيص MISA وتأسيس الأعمال في المملكة العربية السعودية'],
        'features' => [
            ['en' => 'MISA License', 'bn' => 'MISA লাইসেন্স', 'ar' => 'ترخيص MISA'],
            ['en' => 'Company Setup', 'bn' => 'কোম্পানি সেটআপ', 'ar' => 'تأسيس شركة'],
            ['en' => 'Legal Support', 'bn' => 'আইনি সাপোর্ট', 'ar' => 'دعم قانوني'],
        ],
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
            'title' => is_string($tab->title) ? json_decode($tab->title, true) : ($tab->title ?? null),
            'subtitle' => is_string($tab->subtitle) ? json_decode($tab->subtitle, true) : ($tab->subtitle ?? null),
            'features' => $tab->features ?? [],
        ];
    })->toArray();
    
    // Merge with defaults to ensure all fields exist
    $displayTabs = [];
    foreach (!empty($dbTabsArray) ? $dbTabsArray : $defaultTabsArray as $tab) {
        $displayTabs[] = array_merge(end($defaultTabsArray) ?: [], $tab);
    }
    
    // If we have DB tabs, use them as base
    if (!empty($dbTabsArray)) {
        $displayTabs = array_map(function($dbTab) use ($defaultTabsArray) {
            // Find matching default
            $default = null;
            foreach ($defaultTabsArray as $d) {
                if ($d['tab_key'] === $dbTab['tab_key']) {
                    $default = $d;
                    break;
                }
            }
            // Merge, keeping DB values but falling back to defaults
            if ($default) {
                return [
                    'tab_key' => $dbTab['tab_key'],
                    'icon' => $dbTab['icon'] ?? $default['icon'],
                    'label' => $dbTab['label'] ?? $default['label'],
                    'title' => $dbTab['title'] ?? $default['title'],
                    'subtitle' => $dbTab['subtitle'] ?? $default['subtitle'],
                    'features' => $dbTab['features'] ?? $default['features'],
                ];
            }
            return $dbTab;
        }, $dbTabsArray);
    } else {
        $displayTabs = $defaultTabsArray;
    }
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

// Get locale
$locale = app()->getLocale();

// Encode tabs data for JavaScript
$tabsJson = json_encode($displayTabs, JSON_UNESCAPED_UNICODE);
?>

<!-- Dynamic Hero Section Component -->
<section class="hero-section" id="heroSection">
    <!-- Professional Background with Light Circles -->
    <div class="hero-bg-wrapper">
        <!-- Main Gradient Background -->
        <div class="hero-gradient"></div>
        
        <!-- Decorative Light Circles - Static -->
        <div class="circle circle-lg circle-1"></div>
        <div class="circle circle-md circle-2"></div>
        <div class="circle circle-sm circle-3"></div>
        <div class="circle circle-xs circle-4"></div>
    </div>
    
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Dynamic Content -->
            <div class="col-lg-6 hero-content-wrapper">
                <div class="hero-content" id="heroContent">
                    <!-- Content will be dynamically loaded -->
                    <h1 class="hero-title" id="heroTitle">{{ $displayTabs[0]['title'][$locale] ?? $displayTabs[0]['title']['en'] ?? 'Your Trusted Travel Partner' }}</h1>
                    <p class="hero-subtitle" id="heroSubtitle">{{ $displayTabs[0]['subtitle'][$locale] ?? $displayTabs[0]['subtitle']['en'] ?? 'Book flights, Umrah packages, visas, cargo & more - all in one place' }}</p>
                    
                    <!-- Dynamic Features -->
                    <div class="service-features" id="heroFeatures">
                        @if(!empty($displayTabs[0]['features']))
                            @foreach($displayTabs[0]['features'] as $feature)
                                <div class="feature-badge">
                                    <i class="{{ $displayTabs[0]['icon'] ?? 'fas fa-check' }}"></i>
                                    <span>{{ is_array($feature) ? ($feature[$locale] ?? $feature['en'] ?? '') : $feature }}</span>
                                </div>
                            @endforeach
                        @endif
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
                                        role="tab"
                                        data-tab-key="{{ $tab['tab_key'] }}">
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
/* Hero Section Styles - Professional Design */
.hero-section {
    background: linear-gradient(160deg, #252E72 0%, #343C90 30%, #E05522 70%, #C94718 100%);
    color: #fff;
    padding: 100px 0;
    position: relative;
    overflow: hidden;
    min-height: 680px;
}

/* Background Wrapper */
.hero-bg-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    z-index: 1;
}

/* Main Gradient - Static, no animation */
.hero-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

/* Static Light Circles - No Animation */
.circle {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.03) 50%, transparent 70%);
    z-index: 2;
    pointer-events: none;
}

.circle-lg {
    width: 400px;
    height: 400px;
}

.circle-md {
    width: 250px;
    height: 250px;
}

.circle-sm {
    width: 120px;
    height: 120px;
}

.circle-xs {
    width: 60px;
    height: 60px;
}

/* Static Circle Positions */
.circle-1 {
    top: -100px;
    right: -50px;
}

.circle-2 {
    bottom: -50px;
    left: -50px;
}

.circle-3 {
    top: 30%;
    right: 20%;
}

.circle-4 {
    bottom: 20%;
    right: 15%;
}

/* No Grid Pattern - Better Performance */

/* Content Wrapper */
.hero-content-wrapper {
    position: relative;
    z-index: 3;
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

/* Service Features - Simple */
.service-features {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
}

.feature-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.15);
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 500;
}

.feature-badge i {
    color: #ffd700;
    font-size: 12px;
}

/* Hero Booking Card */
.hero-form-wrapper {
    position: relative;
    z-index: 2;
}

.hero-booking-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.9);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    overflow: hidden;
    color: #1F2937;
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
    
    .service-features {
        gap: 8px;
    }
    
    .feature-badge {
        padding: 8px 14px;
        font-size: 13px;
    }
}
</style>

<!-- Dynamic Tab Content Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs data from PHP
    const tabsData = <?php echo $tabsJson; ?>;
    const locale = '<?php echo $locale; ?>';
    
    // Get elements
    const heroTitle = document.getElementById('heroTitle');
    const heroSubtitle = document.getElementById('heroSubtitle');
    const heroFeatures = document.getElementById('heroFeatures');
    const bookingTabs = document.getElementById('bookingTabs');
    
    // Helper function to get localized text
    function getLocalizedText(obj) {
        if (!obj) return '';
        if (typeof obj === 'string') return obj;
        if (typeof obj === 'object') {
            return obj[locale] || obj['en'] || '';
        }
        return '';
    }
    
    // Helper function to get localized feature text
    function getLocalizedFeature(feature) {
        if (!feature) return '';
        if (typeof feature === 'string') return feature;
        if (typeof feature === 'object') {
            return feature[locale] || feature['en'] || '';
        }
        return '';
    }
    
    // Update hero content based on tab
    function updateHeroContent(tabKey) {
        const tab = tabsData.find(t => t.tab_key === tabKey);
        if (!tab) return;
        
        // Update title
        const title = getLocalizedText(tab.title);
        if (title && heroTitle) heroTitle.textContent = title;
        
        // Update subtitle
        const subtitle = getLocalizedText(tab.subtitle);
        if (subtitle && heroSubtitle) heroSubtitle.textContent = subtitle;
        
        // Update features
        if (tab.features && tab.features.length > 0 && heroFeatures) {
            let featuresHtml = '';
            tab.features.forEach(function(feature) {
                const icon = tab.icon || 'fas fa-check';
                const text = getLocalizedFeature(feature);
                if (text) {
                    featuresHtml += '<div class="feature-badge"><i class="' + icon + '"></i><span>' + text + '</span></div>';
                }
            });
            heroFeatures.innerHTML = featuresHtml;
        }
    }
    
    // Add click listeners to all tab buttons
    if (bookingTabs) {
        const tabButtons = bookingTabs.querySelectorAll('.nav-link');
        tabButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const tabKey = this.getAttribute('data-tab-key');
                if (tabKey) {
                    updateHeroContent(tabKey);
                }
            });
        });
    }
});
</script>
