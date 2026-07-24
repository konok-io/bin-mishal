<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Dynamic SEO from CMS --}}
    @php
        $siteName = \App\Models\CMS\Setting::getValue('site_name', __('app.app_name'));
        $metaTitle = \App\Models\CMS\Setting::getValue('meta_title', __('home.seo_title'));
        $metaDescription = \App\Models\CMS\Setting::getValue('meta_description', __('home.seo_description'));
        $favicon = \App\Models\CMS\Setting::getValue('favicon');
    @endphp
    
    <title>{{ $siteName }} - {{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($favicon) }}">
    @endif
    
    <!-- Google Fonts Fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Theme Colors from CMS -->
    @php
        $primaryColor = \App\Models\CMS\Setting::getValue('primary_color', '#006C35');
        $secondaryColor = \App\Models\CMS\Setting::getValue('secondary_color', '#C8A951');
        $accentColor = \App\Models\CMS\Setting::getValue('accent_color', '#1B3A5C');
    @endphp
    
    <!-- Bootstrap 5 RTL -->
    @if(is_rtl())
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Custom Fonts with Unicode Range for Auto-Script Detection */
        @font-face {
            font-family: 'BanglaFont';
            src: url('/fonts/bangla.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0980-09FF, U+09E0-09EF, U+200C-200D, U+20B9;
        }
        @font-face {
            font-family: 'EnglishFont';
            src: url('/fonts/English.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0000-007F, U+0080-00FF, U+0100-017F, U+1E00-1EFF, U+1F300-1F9FF;
        }
        @font-face {
            font-family: 'ArabicFont';
            src: url('/fonts/Arabic.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0600-06FF, U+0750-077F, U+08A0-08FF, U+FB50-FDFF, U+FE70-FEFF;
        }
        
        :root {
            --primary: {{ $primaryColor }};
            --primary-dark: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --accent: {{ $accentColor }};
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --bg-light: #F8FAFC;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Bengali pages - Auto-detect script and use appropriate font */
        html[lang="bn"] body,
        html[lang="bn"] {
            font-family: 'BanglaFont', 'Hind Siliguri', 'EnglishFont', 'ArabicFont', sans-serif;
        }
        
        /* Arabic pages */
        html[lang="ar"] body,
        html[lang="ar"] {
            font-family: 'ArabicFont', 'Noto Sans Arabic', 'EnglishFont', 'BanglaFont', sans-serif;
        }
        
        /* English pages */
        html[lang="en"] body,
        html[lang="en"] {
            font-family: 'EnglishFont', 'Inter', 'BanglaFont', 'ArabicFont', sans-serif;
        }
        
        body { 
            color: var(--text-dark);
            background: #fff;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--accent);
            color: #fff;
            padding: 8px 0;
            font-size: 13px;
        }
        .top-bar a { color: #fff; text-decoration: none; }
        .top-bar .lang-switcher .btn { padding: 2px 8px; font-size: 12px; }
        
        /* Header */
        .main-header {
            background: #fff;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .top-bar {
            position: relative;
            z-index: 1030;
        }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; font-size: 24px; }
        .navbar-nav .nav-link { color: var(--text-dark); font-weight: 500; padding: 20px 15px; }
        .navbar-nav .nav-link:hover { color: var(--primary); }
        .btn-primary-custom { background: var(--primary); border: none; color: #fff; }
        .btn-primary-custom:hover { background: var(--primary-dark); }
        
        /* Login Dropdown in Top Bar */
        .top-bar .dropdown-menu {
            z-index: 1040;
            position: absolute;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .top-bar .dropdown-menu .dropdown-item {
            color: #333;
            padding: 10px 16px;
        }
        .top-bar .dropdown-menu .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
        }
        .top-bar .dropdown-menu .dropdown-item i {
            margin-right: 8px;
            color: var(--primary);
        }
        .top-bar .dropdown-menu .dropdown-item:hover i {
            color: #fff;
        }
        
        /* Auth buttons */
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .auth-buttons .btn-register {
            background: transparent;
            border: 2px solid var(--secondary);
            color: var(--secondary);
            border-radius: 6px;
            padding: 6px 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .auth-buttons .btn-register:hover {
            background: var(--secondary);
            color: #fff;
        }
        .auth-buttons .btn-login {
            background: var(--primary);
            color: #fff;
            border-radius: 6px;
            padding: 6px 14px;
            font-weight: 600;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-section h1 { font-size: 48px; font-weight: 700; margin-bottom: 20px; }
        .hero-section p { font-size: 20px; opacity: 0.9; margin-bottom: 30px; }
        
        /* Search Widget */
        .search-widget {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-lg);
        }
        .search-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin-bottom: 16px !important;
        }
        .search-tabs .nav-link { 
            border-radius: 8px; 
            padding: 10px 16px; 
            font-weight: 600; 
            font-size: 14px;
            flex: 1;
            min-width: auto;
            text-align: center;
            white-space: nowrap;
        }
        .search-tabs .nav-link i { font-size: 14px; }
        .search-tabs .nav-link.active { background: var(--primary); color: #fff; }
        .form-control, .form-select { border-radius: 8px; padding: 12px 16px; border: 1px solid #E2E8F0; }
        .btn-search { background: var(--primary); color: #fff; padding: 14px 32px; border-radius: 8px; font-weight: 600; border: none; }
        .btn-search:hover { background: var(--primary-dark); }
        
        @media (max-width: 768px) {
            .search-tabs .nav-link { padding: 8px 10px; font-size: 12px; }
            .search-tabs .nav-link i { display: block; margin-bottom: 4px; font-size: 16px; }
        }
        
        /* Service Icons */
        ..quick-services { padding: 80px 0; background: var(--bg-light); }
        .service-icon-box {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .service-icon-box:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .service-icon-box i { font-size: 40px; color: var(--primary); margin-bottom: 15px; }
        .service-icon-box h5 { font-weight: 600; color: var(--text-dark); }
        
        /* Why Choose Us */
        .why-choose { padding: 80px 0; }
        .pillar-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
        }
        .pillar-card i { font-size: 48px; margin-bottom: 20px; }
        .pillar-card .counter { font-size: 48px; font-weight: 700; }
        .pillar-card h5 { font-weight: 600; }
        
        /* Package Cards */
        .umrah-packages { padding: 80px 0; background: var(--bg-light); }
        .section-title { font-size: 36px; font-weight: 700; text-align: center; margin-bottom: 50px; color: var(--text-dark); }
        .package-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        .package-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .package-badge { background: var(--secondary); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .package-price { color: var(--primary); font-size: 24px; font-weight: 700; }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        
        /* Flight Routes */
        .flight-routes { padding: 80px 0; }
        .route-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .route-card .route { font-weight: 600; font-size: 18px; }
        .route-card .fare { color: var(--primary); font-weight: 700; font-size: 20px; }
        
        /* Visa Grid */
        .visa-services { padding: 80px 0; background: var(--bg-light); }
        .visa-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        .visa-card:hover { transform: translateY(-5px); }
        .visa-card i { font-size: 48px; color: var(--primary); margin-bottom: 15px; }
        .visa-badge { background: var(--success); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 11px; }
        .visa-badge.warning { background: var(--warning); }
        
        /* Statistics */
        .statistics { padding: 80px 0; background: linear-gradient(135deg, var(--accent) 0%, #0f2744 100%); color: #fff; }
        .stat-item { text-align: center; }
        .stat-item .number { font-size: 48px; font-weight: 700; color: var(--secondary); }
        .stat-item h4 { font-weight: 600; }
        
        /* Testimonials */
        .testimonials { padding: 80px 0; background: var(--bg-light); }
        .testimonial-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
        }
        .testimonial-card img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
        .testimonial-card .stars { color: var(--secondary); }
        
        /* Trust Badges */
        .trust-badges { padding: 40px 0; background: #fff; border-top: 1px solid #E2E8F0; border-bottom: 1px solid #E2E8F0; }
        .trust-badges img { height: 50px; opacity: 0.7; filter: grayscale(100%); transition: all 0.3s; }
        .trust-badges img:hover { opacity: 1; filter: grayscale(0%); }
        
        /* Newsletter */
        .newsletter { padding: 80px 0; background: var(--primary); color: #fff; }
        .newsletter input { border: none; border-radius: 8px; padding: 14px 20px; }
        .btn-subscribe { background: var(--secondary); color: #fff; padding: 14px 32px; border-radius: 8px; font-weight: 600; border: none; }
        
        /* Footer */
        .main-footer { background: var(--accent); color: #fff; padding: 60px 0 30px; }
        .footer-title { font-weight: 700; margin-bottom: 20px; color: var(--secondary); }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 40px; }
        .social-icons a { color: #fff; font-size: 20px; margin-inline-end: 15px; transition: color 0.3s; }
        .social-icons a:hover { color: var(--secondary); }
        
        /* WhatsApp Float */
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
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* AI Chat Button */
        .ai-chat-btn {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: var(--accent);
            color: #fff;
            padding: 14px 24px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            cursor: pointer;
            border: none;
        }
        .ai-chat-btn i { margin-inline-end: 8px; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 { font-size: 32px; }
            .section-title { font-size: 28px; }
            .top-bar { display: none; }
        }
    </style>
</head>
<body>
    <!-- 1. Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 d-flex align-items-center gap-4">
                    <span><i class="fas fa-phone me-1"></i> +966 XX XXX XXXX</span>
                    <span><i class="fab fa-whatsapp me-1"></i> +966 XX XXX XXXX</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end gap-3 flex-wrap">
                        <!-- Language Switcher -->
                        <div class="lang-switcher d-flex align-items-center gap-2">
                            <span>{{ __('common.select_language') }}:</span>
                            <a href="{{ switch_locale_url('bn') }}" class="btn btn-sm {{ app()->getLocale() == 'bn' ? 'btn-primary' : 'btn-outline-light' }}">বাংলা</a>
                            <a href="{{ switch_locale_url('en') }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-light' }}">English</a>
                            <a href="{{ switch_locale_url('ar') }}" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-light' }}">العربية</a>
                        </div>
                        
                        <!-- Auth Buttons -->
                        <div class="auth-buttons">
                            <a href="{{ locale_route('portal.register') }}" class="btn-register">
                                <i class="bi bi-person-plus"></i> {{ __('navigation.register') }}
                            </a>
                            
                            @if(Route::has('login'))
                            <div class="dropdown">
                                <button class="btn btn-sm btn-login dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ __('navigation.login') }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ locale_route('portal.login') }}">
                                        <i class="bi bi-people"></i> {{ __('navigation.portal_login') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('employee.login') }}">
                                        <i class="bi bi-briefcase"></i> {{ __('navigation.employee_login') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.login') }}">
                                        <i class="bi bi-shield-lock"></i> {{ __('navigation.admin_login') }}
                                    </a></li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Sticky Header with Mega Menu -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">{{ __('app.app_name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/">{{ __('app.home') }}</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('navigation.services') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}">{{ __('app.umrah') }} Packages</a></li>
                                <li><a class="dropdown-item" href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}">{{ __('app.visa_processing') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}">{{ __('app.flight_booking') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('services.hotel', ['locale' => app()->getLocale()]) }}">{{ __('app.hotel_booking') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about', ['locale' => app()->getLocale()]) }}">{{ __('app.about') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('news', ['locale' => app()->getLocale()]) }}">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('app.contact') }}</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary-custom text-white ms-2" href="{{ route('appointment', ['locale' => app()->getLocale()]) }}">{{ __('navigation.book_now') }}</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- 3. Hero Slider with Search Widget -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Dynamic Left Content -->
                <div class="col-lg-5" data-aos="fade-right">
                    <div id="heroContent">
                        <!-- Flight Content (Default) -->
                        <div class="hero-content" data-tab="flight">
                            <h1>{{ __('home.hero_title') }}</h1>
                            <p>{{ __('home.hero_subtitle') }}</p>
                            <div class="hero-features mt-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Best prices on all routes</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Instant booking confirmation</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>24/7 customer support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Widget - Right Side -->
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="search-widget">
                        <ul class="nav search-tabs mb-3" id="searchTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="flight-tab" data-bs-toggle="tab" data-bs-target="#flight" type="button"><i class="fas fa-plane me-2"></i>Flight</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="umrah-tab" data-bs-toggle="tab" data-bs-target="#umrah" type="button"><i class="fas fa-mosque me-2"></i>Umrah</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="visa-tab" data-bs-toggle="tab" data-bs-target="#visa" type="button"><i class="fas fa-passport me-2"></i>Visa</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cargo-tab" data-bs-toggle="tab" data-bs-target="#cargo" type="button"><i class="fas fa-box me-2"></i>Cargo</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="appt-tab" data-bs-toggle="tab" data-bs-target="#appt" type="button"><i class="fas fa-calendar me-2"></i>Appointment</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="searchTabContent">
                            <div class="tab-pane fade show active" id="flight">
                                <form class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">From</label>
                                        <select class="form-select"><option>Dhaka (DAC)</option><option>Riyadh (RUH)</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">To</label>
                                        <select class="form-select"><option>Jeddah (JED)</option><option>Dammam (DMM)</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Departure</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Return</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-search w-100"><i class="fas fa-search me-2"></i>Search Flights</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="umrah">
                                <form class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Package Type</label>
                                        <select class="form-select"><option>Economy</option><option>Standard</option><option>Premium</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Duration</label>
                                        <select class="form-select"><option>7 Days</option><option>14 Days</option><option>21 Days</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Travel Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pilgrims</label>
                                        <select class="form-select"><option>1</option><option>2</option><option>3</option><option>4+</option></select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-search w-100"><i class="fas fa-search me-2"></i>Search Packages</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="visa">
                                <form class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Visa Type</label>
                                        <select class="form-select"><option>Tourist Visa</option><option>Business Visa</option><option>Transit Visa</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nationality</label>
                                        <select class="form-select"><option>Bangladesh</option><option>India</option><option>Pakistan</option></select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-search w-100"><i class="fas fa-search me-2"></i>Check Visa</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Cargo Tab -->
                            <div class="tab-pane fade" id="cargo">
                                <form class="row g-3" id="cargoForm">
                                    <div class="col-md-6">
                                        <label class="form-label">Origin City</label>
                                        <select class="form-select" name="origin" id="cargoOrigin">
                                            <option value="">Select Origin</option>
                                            <option value="riyadh">Riyadh</option>
                                            <option value="jeddah">Jeddah</option>
                                            <option value="dammam">Dammam</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Destination</label>
                                        <select class="form-select" name="destination" id="cargoDestination">
                                            <option value="">Select Destination</option>
                                            <option value="dhaka">Dhaka</option>
                                            <option value="chittagong">Chittagong</option>
                                            <option value="sylhet">Sylhet</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cargo Type</label>
                                        <select class="form-select" name="cargo_type" id="cargoType">
                                            <option value="">Select Type</option>
                                            <option value="documents">Documents</option>
                                            <option value="electronics">Electronics</option>
                                            <option value="clothing">Clothing</option>
                                            <option value="food">Food Items</option>
                                            <option value="parcel">Parcel</option>
                                            <option value="commercial">Commercial Goods</option>
                                            <option value="household">Household Goods</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" name="weight" id="cargoWeight" placeholder="Enter weight" min="0.1">
                                    </div>
                                    <div class="col-12" id="cargoPriceResult" style="display: none;">
                                        <div class="alert alert-info">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Estimated Price:</strong><br>
                                                    <small>Delivery: 3-5 Business Days</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fs-4 fw-bold text-success" id="cargoPrice">SAR 0.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-search w-100" onclick="calculateCargoPrice()">
                                            <i class="fas fa-calculator me-2"></i>Calculate Price
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane fade" id="appt">
                                <form class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Service Type</label>
                                        <select class="form-select"><option>Visa Appointment</option><option>Document Collection</option><option>Consultation</option></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Preferred Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-search w-100"><i class="fas fa-calendar-check me-2"></i>Book Appointment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Quick Service Icons -->
    <section class="quick-services">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="0">
                    <a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-mosque"></i>
                            <h5>{{ __('app.umrah') }}</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-passport"></i>
                            <h5>{{ __('app.visa') }}</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-plane"></i>
                            <h5>{{ __('app.air_ticket') }}</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('services.hotel', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-hotel"></i>
                            <h5>{{ __('app.hotel_booking') }}</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('visa-checker', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-clipboard-check"></i>
                            <h5>Visa Check</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ route('track', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-truck"></i>
                            <h5>Track Order</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="600">
                    <a href="{{ route('labour-law', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-gavel"></i>
                            <h5>Labour Law</h5>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="700">
                    <a href="{{ route('faqs', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="service-icon-box">
                            <i class="fas fa-question-circle"></i>
                            <h5>{{ __('app.faq') }}</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Why Choose Us -->
    <section class="why-choose">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Why Choose {{ __('app.app_name') }}?</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="pillar-card">
                        <i class="fas fa-award"></i>
                        <div class="counter">{{ __('home.experience_years') }}+</div>
                        <h5>Years Experience</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="pillar-card">
                        <i class="fas fa-users"></i>
                        <div class="counter">{{ __('home.happy_customers_number') }}</div>
                        <h5>Happy Customers</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pillar-card">
                        <i class="fas fa-ticket-alt"></i>
                        <div class="counter">{{ __('home.tickets_issued') }}+</div>
                        <h5>Tickets Issued</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="pillar-card">
                        <i class="fas fa-headset"></i>
                        <div class="counter">24/7</div>
                        <h5>Customer Support</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Featured Umrah Packages -->
    <section class="umrah-packages">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">{{ __('home.featured_umrah_packages') }}</h2>
            <div class="row g-4">
                @php
                $packages = [
                    ['name' => 'Economy Umrah Package', 'duration' => '7 Days', 'price' => '2,499', 'includes' => ['Visa', '3-Star Hotel', 'Transport', 'Ziarat']],
                    ['name' => 'Standard Umrah Package', 'duration' => '10 Days', 'price' => '3,299', 'includes' => ['Visa', '4-Star Hotel', 'Transport', 'Ziarat', 'Meals']],
                    ['name' => 'Premium Umrah Package', 'duration' => '14 Days', 'price' => '5,499', 'includes' => ['Visa', '5-Star Hotel', 'Transport', 'Ziarat', 'All Meals', 'Guided Tours']],
                ];
                @endphp
                @foreach($packages as $package)
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="package-card h-100">
                        <div class="position-relative">
                            <div style="height: 200px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-mosque fa-4x text-white opacity-50"></i>
                            </div>
                            <span class="package-badge position-absolute top-0 end-0 m-3">{{ $package['duration'] }}</span>
                        </div>
                        <div class="p-4">
                            <h5 class="mb-3">{{ $package['name'] }}</h5>
                            <ul class="list-unstyled mb-3">
                                @foreach($package['includes'] as $item)
                                <li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="package-price">SAR {{ $package['price'] }}</span>
                                <a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}" class="btn btn-primary-custom btn-lg">{{ __('home.all_packages') }} <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- 7. Popular Flight Routes -->
    <section class="flight-routes">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Popular Flight Routes</h2>
            <div class="row g-4">
                @php
                $routes = [
                    ['from' => 'Dhaka (DAC)', 'to' => 'Riyadh (RUH)', 'fare' => '1,299', 'airline' => 'Saudi Airlines'],
                    ['from' => 'Dhaka (DAC)', 'to' => 'Jeddah (JED)', 'fare' => '1,199', 'airline' => 'Biman Bangladesh'],
                    ['from' => 'Dhaka (DAC)', 'to' => 'Dammam (DMM)', 'fare' => '1,399', 'airline' => 'Saudi Airlines'],
                    ['from' => 'Chittagong (CGP)', 'to' => 'Riyadh (RUH)', 'fare' => '1,499', 'airline' => 'Biman Bangladesh'],
                ];
                @endphp
                @foreach($routes as $route)
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="route-card">
                        <div>
                            <div class="route"><i class="fas fa-plane me-2 text-primary"></i>{{ $route['from'] }} <i class="fas fa-arrow-right mx-2 text-muted"></i> {{ $route['to'] }}</div>
                            <small class="text-muted">{{ $route['airline'] }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fare">SAR {{ $route['fare'] }}</div>
                            <small class="text-muted">One Way</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 8. Visa Services Grid -->
    <section class="visa-services">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">{{ __('home.visa_services_title') }}</h2>
            <div class="row g-4">
                @php
                $visas = [
                    ['name' => 'Tourist Visa', 'time' => '3-5 Days', 'type' => 'success', 'icon' => 'fa-landmark'],
                    ['name' => 'Business Visa', 'time' => '5-7 Days', 'type' => 'warning', 'icon' => 'fa-briefcase'],
                    ['name' => 'Transit Visa', 'time' => '1-2 Days', 'type' => 'success', 'icon' => 'fa-plane-departure'],
                    ['name' => 'Work Visa', 'time' => '7-14 Days', 'type' => 'warning', 'icon' => 'fa-hard-hat'],
                    ['name' => 'Family Visit', 'time' => '5-7 Days', 'type' => 'success', 'icon' => 'fa-users'],
                    ['name' => 'Student Visa', 'time' => '7-10 Days', 'type' => 'warning', 'icon' => 'fa-graduation-cap'],
                ];
                @endphp
                @foreach($visas as $visa)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}" class="text-decoration-none">
                        <div class="visa-card h-100">
                            <i class="fas {{ $visa['icon'] }}"></i>
                            <h5 class="mt-3 mb-2">{{ $visa['name'] }}</h5>
                            <span class="visa-badge {{ $visa['type'] == 'warning' ? 'warning' : '' }}">Processing: {{ $visa['time'] }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 9. Statistics Counters -->
    <section class="statistics">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="stat-item">
                        <div class="number">{{ __('home.customers_served') }}+</div>
                        <h4>Customers Served</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="number">{{ __('home.tickets_issued') }}+</div>
                        <h4>Tickets Issued</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="number">{{ __('home.visas_processed') }}+</div>
                        <h4>Visas Processed</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="number">{{ __('home.experience_years') }}+</div>
                        <h4>Years Experience</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Testimonials -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">{{ __('home.testimonials_title') }}</h2>
            <div class="row g-4">
                @php
                $testimonials = [
                    ['name' => 'Mohammad Rahman', 'location' => 'Dhaka, Bangladesh', 'text' => 'Excellent service! The Umrah package was well organized and the staff was very helpful throughout the journey.', 'rating' => 5],
                    ['name' => 'Fatima Ahmed', 'location' => 'Chittagong, Bangladesh', 'text' => 'Got my Saudi visa within 5 days. Highly recommended for their quick and professional service.', 'rating' => 5],
                    ['name' => 'Kamal Hossain', 'location' => 'Riyadh, KSA', 'text' => 'Best travel agency for Bangladesh-KSA travel. Their package deals are very competitive.', 'rating' => 4],
                ];
                @endphp
                @foreach($testimonials as $t)
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="testimonial-card h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($t['name']) }}&background=006C35&color=fff" alt="{{ $t['name'] }}">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $t['name'] }}</h6>
                                <small class="text-muted">{{ $t['location'] }}</small>
                            </div>
                        </div>
                        <p class="text-muted mb-3">{{ $t['text'] }}</p>
                        <div class="stars">
                            @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star{{ $i >= $t['rating'] ? '-half-alt' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 11. Trust Badges -->
    <section class="trust-badges">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-6 col-md-2 mb-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/IATA_logo.svg/200px-IATA_logo.svg.png" alt="IATA" class="img-fluid">
                </div>
                <div class="col-6 col-md-2 mb-3">
                    <div style="font-size: 24px; font-weight: bold; color: var(--primary);">Saudi Tourism</div>
                    <small class="text-muted">Ministry Certified</small>
                </div>
                <div class="col-6 col-md-2 mb-3">
                    <div style="font-size: 24px; font-weight: bold; color: var(--secondary);">ATAB</div>
                    <small class="text-muted">Association Member</small>
                </div>
                <div class="col-6 col-md-2 mb-3">
                    <i class="fab fa-cc-visa fa-3x me-2"></i>
                    <i class="fab fa-cc-mastercard fa-3x"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. Newsletter -->
    <section class="newsletter">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h3 class="mb-3">{{ __('home.newsletter_title') }}</h3>
                    <p class="mb-4 opacity-75">{{ __('home.newsletter_subtitle') }}</p>
                    <form class="row g-2 justify-content-center">
                        <div class="col-md-6">
                            <input type="email" class="form-control form-control-lg" placeholder="{{ __('home.newsletter_placeholder') }}">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-subscribe btn-lg">{{ __('navigation.subscribe') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 13. Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="footer-title">{{ __('app.app_name') }}</h5>
                    <p class="opacity-75 mb-4">{{ __('home.footer_description') }}</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('about', ['locale' => app()->getLocale()]) }}">{{ __('app.about') }}</a></li>
                        <li><a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}">{{ __('app.umrah') }}</a></li>
                        <li><a href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}">{{ __('app.visa') }}</a></li>
                        <li><a href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('app.contact') }}</a></li>
                        <li><a href="{{ route('careers', ['locale' => app()->getLocale()]) }}">Careers</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Services</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}">{{ __('app.flight_booking') }}</a></li>
                        <li><a href="{{ route('services.hotel', ['locale' => app()->getLocale()]) }}">{{ __('app.hotel_booking') }}</a></li>
                        <li><a href="{{ route('visa-checker', ['locale' => app()->getLocale()]) }}">Visa Status</a></li>
                        <li><a href="{{ route('track', ['locale' => app()->getLocale()]) }}">Track Order</a></li>
                        <li><a href="{{ route('faqs', ['locale' => app()->getLocale()]) }}">{{ __('app.faq') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">{{ __('home.contact_info') }}</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Riyadh, Saudi Arabia</li>
                        <li><i class="fas fa-phone me-2"></i> +966 XX XXX XXXX</li>
                        <li><i class="fas fa-envelope me-2"></i> info@binmishal.com</li>
                        <li><i class="fas fa-clock me-2"></i> Sat-Thu: 9AM-6PM</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 opacity-75">&copy; {{ date('Y') }} {{ __('app.app_name') }}. {{ __('common.all_rights_reserved') }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('privacy-policy', ['locale' => app()->getLocale()]) }}" class="text-white-50 me-3">Privacy Policy</a>
                        <a href="{{ route('terms', ['locale' => app()->getLocale()]) }}" class="text-white-50 me-3">Terms</a>
                        <a href="{{ route('refund-policy', ['locale' => app()->getLocale()]) }}" class="text-white-50">Refund Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- AI Chat Button -->
    <button class="ai-chat-btn" onclick="alert('AI Chat coming soon!')">
        <i class="fas fa-robot"></i> AI Assistant
    </button>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/966XXXXXXXX" target="_blank" class="whatsapp-float">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Hero Content Data
        const heroContent = {
            flight: {
                title: "{{ __('home.hero_title') }}",
                subtitle: "{{ __('home.hero_subtitle') }}",
                features: [
                    'Best prices on all routes',
                    'Instant booking confirmation',
                    '24/7 customer support'
                ]
            },
            umrah: {
                title: 'Umrah Packages',
                subtitle: 'Complete Umrah packages with visa, hotel, transport & guided tours',
                features: [
                    'Licensed Umrah operator',
                    'Premium hotel accommodations',
                    'Experienced tour guides'
                ]
            },
            visa: {
                title: 'Visa Processing',
                subtitle: 'Fast and reliable visa services for Saudi Arabia',
                features: [
                    'Quick visa processing',
                    'Expert documentation help',
                    '100% approval guidance'
                ]
            },
            cargo: {
                title: 'Cargo & Logistics',
                subtitle: 'Ship your goods from Saudi Arabia to Bangladesh safely',
                features: [
                    'Door-to-door delivery',
                    'Real-time tracking',
                    'Competitive rates'
                ]
            },
            appt: {
                title: 'Book Appointment',
                subtitle: 'Schedule your visit to our office for personalized service',
                features: [
                    'Flexible scheduling',
                    'Multiple branches',
                    'Priority service'
                ]
            }
        };
        
        // Dynamic Hero Content
        document.querySelectorAll('.search-tabs .nav-link').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const tabId = event.target.id.replace('-tab', '');
                updateHeroContent(tabId);
            });
        });
        
        function updateHeroContent(tabId) {
            const content = heroContent[tabId];
            if (!content) return;
            
            const heroDiv = document.getElementById('heroContent');
            let featuresHtml = '';
            
            content.features.forEach(feature => {
                featuresHtml += `
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>${feature}</span>
                    </div>
                `;
            });
            
            heroDiv.innerHTML = `
                <div class="hero-content" data-tab="${tabId}">
                    <h1>${content.title}</h1>
                    <p>${content.subtitle}</p>
                    <div class="hero-features mt-4">
                        ${featuresHtml}
                    </div>
                </div>
            `;
        }
        
        // Cargo Price Calculator
        function calculateCargoPrice() {
            const origin = document.getElementById('cargoOrigin').value;
            const destination = document.getElementById('cargoDestination').value;
            const weight = parseFloat(document.getElementById('cargoWeight').value) || 0;
            
            if (!origin || !destination || !weight) {
                alert('Please fill in all fields');
                return;
            }
            
            // Simple pricing calculation (can be made dynamic via API)
            const baseRate = 15; // SAR per kg
            const vatRate = 0.15;
            
            const shippingCost = weight * baseRate;
            const vat = shippingCost * vatRate;
            const total = shippingCost + vat;
            
            document.getElementById('cargoPrice').textContent = 'SAR ' + total.toFixed(2);
            document.getElementById('cargoPriceResult').style.display = 'block';
        }
        
        // Update price on weight change
        document.getElementById('cargoWeight').addEventListener('input', function() {
            if (this.value) {
                calculateCargoPrice();
            }
        });
    </script>
</body>
</html>
