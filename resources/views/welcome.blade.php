{{-- ========================================================================
    Professional Corporate Travel Website - Dynamic Welcome Page
    All content from CMS - No hardcoded content
============================================================================ --}}

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" data-locale="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $siteName = \App\Models\CMS\Setting::getValue('site_name', 'Bin Mishal Travels');
        $metaTitle = \App\Models\CMS\Setting::getValue('meta_title', 'Your Trusted Travel Partner');
        $metaDescription = \App\Models\CMS\Setting::getValue('meta_description', 'Book flights, Umrah packages, visas, cargo & more');
    @endphp
    
    <title>{{ $metaTitle }} | {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    
    @if(\App\Models\CMS\Setting::getValue('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(\App\Models\CMS\Setting::getValue('favicon')) }}">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @if(is_rtl())
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
    <style>
        /* Custom Fonts */
        @font-face {
            font-family: 'BanglaFont';
            src: url('/fonts/bangla.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'BanglaFont';
            src: url('/fonts/bangla.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'EnglishFont';
            src: url('/fonts/English.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'EnglishFont';
            src: url('/fonts/English.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'ArabicFont';
            src: url('/fonts/Arabic.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'ArabicFont';
            src: url('/fonts/Arabic.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        
        :root {
            --primary-color: {{ \App\Models\CMS\Setting::getValue('primary_color', '#E05522') }};
            --primary-dark: {{ \App\Models\CMS\Setting::getValue('primary_dark', '#C94718') }};
            --secondary-color: {{ \App\Models\CMS\Setting::getValue('secondary_color', '#343C90') }};
            --accent-color: {{ \App\Models\CMS\Setting::getValue('accent_color', '#1F2937') }};
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --bg-light: #F8FAFC;
        }
        
        html[lang="bn"] body, html[lang="bn"] * { font-family: 'BanglaFont', 'Hind Siliguri', sans-serif; }
        html[lang="ar"] body, html[lang="ar"] * { font-family: 'ArabicFont', 'Noto Sans Arabic', sans-serif; direction: rtl; }
        html[lang="en"] body, html[lang="en"] * { font-family: 'EnglishFont', 'Inter', sans-serif; }
        
        body { color: var(--text-dark); background: #fff; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 4px; }
        
        .section-padding { padding: 80px 0; }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-header h2 { font-size: 2.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; }
        .section-header p { font-size: 1.1rem; color: var(--text-muted); max-width: 600px; margin: 0 auto; }
        .section-badge { 
            display: inline-block; 
            background: rgba(0, 108, 53, 0.1); 
            color: var(--primary-color); 
            padding: 8px 20px; 
            border-radius: 30px; 
            font-size: 0.9rem; 
            font-weight: 600; 
            margin-bottom: 15px; 
        }
        
        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 108, 53, 0.3);
        }
        
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 30px;
            box-shadow: 0 5px 20px rgba(37, 211, 102, 0.4);
            z-index: 9999;
            animation: pulse 2s infinite;
            text-decoration: none;
        }
        .whatsapp-float:hover { color: #fff; transform: scale(1.1); }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .why-card { transition: all 0.3s; border: 1px solid #eee; }
        .why-card:hover { transform: translateY(-5px); border-color: var(--primary-color); }
        .why-icon { width: 70px; height: 70px; background: rgba(0, 108, 53, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .why-icon i { font-size: 28px; color: var(--primary-color); }
        
        .step-card { padding: 30px; background: #fff; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .step-number { font-size: 3rem; font-weight: 700; color: var(--primary-color); opacity: 0.2; line-height: 1; }
        .step-icon { width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .step-icon i { font-size: 24px; color: #fff; }
        
        .destination-card { transition: all 0.3s; }
        .destination-card:hover { transform: translateY(-5px); }
        
        .partners-section { background: #f8fafc; }
        .partners-slider { overflow: hidden; }
        .partners-track { display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; }
        .partner-item { 
            flex: 0 0 auto; 
            min-width: 120px; 
            max-width: 150px; 
            background: white; 
            padding: 15px; 
            border-radius: 10px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); 
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .partner-item:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 5px 15px rgba(52, 60, 144, 0.12);
        }
        .partner-item i { color: var(--primary-color); }
        .partner-item h6 { font-size: 11px; font-weight: 600; color: #555; text-align: center; margin: 0; }
    </style>
</head>
<body>
    @include('components.frontend.header')
    @include('components.frontend.hero-section')
    
    <!-- Booking Search Tabs -->
    @php
        $bookingTabs = \Illuminate\Support\Facades\Cache::remember('welcome_booking_tabs_' . app()->getLocale(), 600, function() {
            try {
                return \App\Models\HeroTab::where('is_active', true)->ordered()->take(6)->get() ?? collect();
            } catch (\Exception $e) {
                return collect();
            }
        });
    @endphp
    <section class="booking-search py-5 bg-light">
        <div class="container">
            <div class="booking-tabs bg-white rounded-4 shadow-lg p-4">
                <ul class="nav nav-pills mb-4" id="bookingTabs" role="tablist">
                    @foreach($bookingTabs as $index => $tab)
                        @if($tab instanceof \App\Models\HeroTab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="{{ $tab->tab_key }}-tab" data-bs-toggle="pill" data-bs-target="#{{ $tab->tab_key }}" type="button" role="tab">
                                <i class="{{ $tab->icon }}"></i>
                                {{ $tab->translated_label }}
                            </button>
                        </li>
                        @endif
                    @endforeach
                </ul>
                <div class="tab-content p-4" id="bookingTabsContent">
                    @foreach($bookingTabs as $index => $tab)
                        @if($tab instanceof \App\Models\HeroTab)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $tab->tab_key }}" role="tabpanel">
                            <form action="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}" method="GET" class="row g-3">
                                @if($tab->tab_key === 'flight')
                                    <div class="col-md-3"><label class="form-label">From</label><select class="form-select" name="from_city"><option value="">Select</option><option value="riyadh">Riyadh</option><option value="jeddah">Jeddah</option><option value="dammam">Dammam</option></select></div>
                                    <div class="col-md-3"><label class="form-label">To</label><select class="form-select" name="to_city"><option value="">Select</option><option value="dhaka">Dhaka</option><option value="chittagong">Chittagong</option><option value="sylhet">Sylhet</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Date</label><input type="date" class="form-control" name="departure_date"></div>
                                    <div class="col-md-2"><label class="form-label">Passengers</label><select class="form-select" name="passengers"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5+">5+</option></select></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-search"></i></button></div>
                                @elseif($tab->tab_key === 'umrah')
                                    <div class="col-md-3"><label class="form-label">Package</label><select class="form-select" name="package_type"><option value="">Select</option><option value="economy">Economy</option><option value="standard">Standard</option><option value="premium">Premium</option><option value="vip">VIP</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Date</label><input type="date" class="form-control" name="travel_date"></div>
                                    <div class="col-md-3"><label class="form-label">Days</label><select class="form-select" name="duration"><option value="">Select</option><option value="7">7 Days</option><option value="14">14 Days</option><option value="21">21 Days</option></select></div>
                                    <div class="col-md-2"><label class="form-label">Pilgrims</label><select class="form-select" name="pilgrims"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4+">4+</option></select></div>
                                    <div class="col-md-1 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-search"></i></button></div>
                                @elseif($tab->tab_key === 'visa')
                                    <div class="col-md-3"><label class="form-label">Visa Type</label><select class="form-select" name="visa_type"><option value="">Select</option><option value="tourist">Tourist</option><option value="business">Business</option><option value="work">Work</option><option value="family">Family</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Nationality</label><select class="form-select" name="nationality"><option value="">Select</option><option value="bangladesh">Bangladesh</option><option value="india">India</option><option value="pakistan">Pakistan</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Entry</label><select class="form-select" name="entry_type"><option value="single">Single</option><option value="multiple">Multiple</option></select></div>
                                    <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-paper-plane"></i> Apply</button></div>
                                @elseif($tab->tab_key === 'cargo')
                                    <div class="col-md-3"><label class="form-label">From</label><select class="form-select" name="from_country"><option value="">Select</option><option value="saudi">Saudi Arabia</option><option value="uae">UAE</option></select></div>
                                    <div class="col-md-3"><label class="form-label">To</label><select class="form-select" name="to_country"><option value="">Select</option><option value="bangladesh">Bangladesh</option><option value="india">India</option></select></div>
                                    <div class="col-md-2"><label class="form-label">Weight (kg)</label><input type="number" class="form-control" name="weight" placeholder="kg"></div>
                                    <div class="col-md-2"><label class="form-label">Type</label><select class="form-select" name="cargo_type"><option value="air">Air</option><option value="sea">Sea</option><option value="land">Land</option></select></div>
                                    <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-calculator"></i></button></div>
                                @elseif($tab->tab_key === 'appointment')
                                    <div class="col-md-3"><label class="form-label">Service</label><select class="form-select" name="service_type"><option value="">Select</option><option value="consultation">Consultation</option><option value="document">Document</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Date</label><input type="date" class="form-control" name="appointment_date"></div>
                                    <div class="col-md-3"><label class="form-label">Time</label><select class="form-select" name="appointment_time"><option value="">Select</option><option value="09:00">9:00 AM</option><option value="10:00">10:00 AM</option><option value="14:00">2:00 PM</option></select></div>
                                    <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-calendar-check"></i> Book</button></div>
                                @elseif($tab->tab_key === 'investor')
                                    <div class="col-md-3"><label class="form-label">Service</label><select class="form-select" name="invest_type"><option value="">Select</option><option value="misa">MISA License</option><option value="company">Company Registration</option><option value="consultation">Consultation</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Sector</label><select class="form-select" name="sector"><option value="">Select</option><option value="tourism">Tourism</option><option value="retail">Retail</option></select></div>
                                    <div class="col-md-3"><label class="form-label">Investment</label><select class="form-select" name="investment_range"><option value="">Select</option><option value="small">Under 500K</option><option value="medium">500K-2M</option></select></div>
                                    <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-chart-line"></i> Get Started</button></div>
                                @endif
                            </form>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    @include('components.frontend.services-section')
    
    <!-- Why Choose Us -->
    <section class="why-choose-us section-padding bg-white">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Why Choose Us</span>
                <h2>Why Choose Bin Mishal Travels?</h2>
                <p>We provide exceptional travel services with years of experience</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="why-card text-center p-4 rounded-4 shadow-sm h-100">
                        <div class="why-icon mb-3"><i class="fas fa-shield-alt"></i></div>
                        <h4>Licensed & Trusted</h4>
                        <p class="text-muted mb-0">Government approved agency</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="why-card text-center p-4 rounded-4 shadow-sm h-100">
                        <div class="why-icon mb-3"><i class="fas fa-headset"></i></div>
                        <h4>24/7 Support</h4>
                        <p class="text-muted mb-0">Round-the-clock support</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="why-card text-center p-4 rounded-4 shadow-sm h-100">
                        <div class="why-icon mb-3"><i class="fas fa-tags"></i></div>
                        <h4>Best Prices</h4>
                        <p class="text-muted mb-0">Competitive pricing</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="why-card text-center p-4 rounded-4 shadow-sm h-100">
                        <div class="why-icon mb-3"><i class="fas fa-file-alt"></i></div>
                        <h4>Easy Documentation</h4>
                        <p class="text-muted mb-0">Hassle-free processing</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('components.frontend.statistics-section')
    
    <!-- How It Works -->
    <section class="how-it-works section-padding bg-light">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Simple Process</span>
                <h2>How It Works</h2>
                <p>Book in just 4 simple steps</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card text-center">
                        <div class="step-number">01</div>
                        <div class="step-icon mb-3"><i class="fas fa-search"></i></div>
                        <h4>Search & Compare</h4>
                        <p class="text-muted mb-0">Find the best options</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card text-center">
                        <div class="step-number">02</div>
                        <div class="step-icon mb-3"><i class="fas fa-file-upload"></i></div>
                        <h4>Submit Documents</h4>
                        <p class="text-muted mb-0">Upload required docs</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card text-center">
                        <div class="step-number">03</div>
                        <div class="step-icon mb-3"><i class="fas fa-check-circle"></i></div>
                        <h4>We Process</h4>
                        <p class="text-muted mb-0">Team handles everything</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card text-center">
                        <div class="step-number">04</div>
                        <div class="step-icon mb-3"><i class="fas fa-plane-departure"></i></div>
                        <h4>Travel Ready</h4>
                        <p class="text-muted mb-0">Receive confirmation</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Popular Destinations -->
    <section class="popular-destinations section-padding bg-white">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Explore More</span>
                <h2>Popular Destinations</h2>
                <p>Discover our most sought-after routes</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="destination-card rounded-4 overflow-hidden shadow-sm">
                        <div class="destination-image" style="background: linear-gradient(135deg, #E05522 0%, #C94718 100%); height: 250px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-kaaba fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="destination-info p-4 bg-white">
                            <h4>Makkah & Madinah</h4>
                            <p class="text-muted">Umrah & Hajj Packages</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">From SAR 3,500</span>
                                <a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="destination-card rounded-4 overflow-hidden shadow-sm">
                        <div class="destination-image" style="background: linear-gradient(135deg, #1E3A5C 0%, #0f1f33 100%); height: 250px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-landmark fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="destination-info p-4 bg-white">
                            <h4>Dhaka, Bangladesh</h4>
                            <p class="text-muted">Flights & Visa</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">From SAR 450</span>
                                <a href="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}" class="btn btn-sm btn-outline-primary">Book</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="destination-card rounded-4 overflow-hidden shadow-sm">
                        <div class="destination-image" style="background: linear-gradient(135deg, #E05522 0%, #C94718 100%); height: 250px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-passport fa-5x text-white opacity-50"></i>
                        </div>
                        <div class="destination-info p-4 bg-white">
                            <h4>Saudi Arabia</h4>
                            <p class="text-muted">Tourist & Business Visa</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">From SAR 350</span>
                                <a href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}" class="btn btn-sm btn-outline-primary">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('components.frontend.testimonials-section')
    
    <!-- Partners -->
    <section class="partners-section section-padding">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-badge">Trusted Partners</span>
                <h2>Our Partners & Airlines</h2>
            </div>
            <div class="partners-track" data-aos="fade-up">
                @foreach(['Saudi Arabian Airlines', 'Biman Bangladesh', 'US-Bangla Airlines', 'Flydubai', 'Air Arabia', 'Qatar Airways', 'Oman Air', 'Emirates'] as $partner)
                    <div class="partner-item">
                        <i class="fas fa-plane fa-2x"></i>
                        <h6>{{ $partner }}</h6>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    @include('components.frontend.gallery-section')
    @include('components.frontend.faq-section')
    @include('components.frontend.cta-section')
    
    <!-- Newsletter -->
    <section class="newsletter-section py-5" style="background: var(--primary-color);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white mb-4 mb-lg-0">
                    <h3 class="mb-2">Subscribe to Newsletter</h3>
                    <p class="mb-0 opacity-75">Get latest travel deals and updates</p>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('newsletter.subscribe', ['locale' => app()->getLocale()]) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Your email" required>
                        <button type="submit" class="btn btn-light btn-lg px-4">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    @include('components.frontend.footer')
    
    @php
        $whatsappEnabled = \App\Models\CMS\Setting::getValue('whatsapp_float', true);
        $whatsappNumber = \App\Models\CMS\Setting::getValue('contact_whatsapp', '966501234567');
        $whatsappMessage = \App\Models\CMS\Setting::getValue('whatsapp_message', 'Hello!');
    @endphp
    @if($whatsappEnabled)
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" target="_blank" class="whatsapp-float">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
    </script>
</body>
</html>
