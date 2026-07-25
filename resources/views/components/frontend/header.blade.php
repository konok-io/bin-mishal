<?php
use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use App\Models\CMS\Setting;
use App\Models\HeroTab;
use Illuminate\Support\Facades\Cache;

// Get HeroTabs with fallback - cache key includes locale for correct translations
$cacheKey = 'header_nav_tabs_' . app()->getLocale();
$navTabs = Cache::remember($cacheKey, 600, function() {
    try {
        return \App\Models\HeroTab::where('is_active', 1)
            ->where('show_in_nav', 1)
            ->orderBy('order')
            ->get() ?? collect();
    } catch (\Exception $e) {
        return collect();
    }
});
?>

<!-- Dynamic Header Component -->
<header class="main-header" id="mainHeader">
    <!-- Top Bar -->
    <div class="header-topbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Contact Info -->
                <div class="topbar-contact d-none d-md-flex">
                    <a href="tel:{{ Setting::getValue('contact_phone', '+966 XX XXX XXXX') }}">
                        <i class="fas fa-phone"></i>
                        {{ Setting::getValue('contact_phone', '+966 XX XXX XXXX') }}
                    </a>
                    <a href="mailto:{{ Setting::getValue('contact_email', 'info@binmishal.com') }}">
                        <i class="fas fa-envelope"></i>
                        {{ Setting::getValue('contact_email', 'info@binmishal.com') }}
                    </a>
                </div>
                
                <!-- Right Side -->
                <div class="topbar-right d-flex align-items-center gap-3">
                    <!-- Working Hours -->
                    <span class="d-none d-lg-inline">
                        <i class="fas fa-clock"></i>
                        {{ Setting::getValue('working_hours', 'Sat-Thu: 9AM-6PM') }}
                    </span>
                    
                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <button class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i>
                            {{ strtoupper(app()->getLocale()) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('locale', 'en') }}">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="{{ route('locale', 'bn') }}">🇧🇩 বাংলা</a></li>
                            <li><a class="dropdown-item" href="{{ route('locale', 'ar') }}">🇸🇦 العربية</a></li>
                        </ul>
                    </div>
                    
                    <!-- Portal Links -->
                    <div class="portal-links d-none d-lg-flex align-items-center gap-2">
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-headset me-1"></i> {{ __('nav.support') }}
                        </a>
                        <a href="{{ route('portal.login', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('login') }}
                        </a>
                        <a href="{{ route('portal.register', ['locale' => app()->getLocale()]) }}" class="btn btn-light btn-sm text-primary">
                            <i class="fas fa-user-plus me-1"></i> {{ __('register') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                @if(Setting::getValue('logo_light'))
                    <img src="{{ Storage::url(Setting::getValue('logo_light')) }}" alt="{{ Setting::getValue('site_name', 'Bin Mishal') }}" height="45">
                @else
                    <span class="brand-text">{{ Setting::getValue('site_name', 'Bin Mishal') }}</span>
                @endif
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-home"></i> @lang('nav.home')
                        </a>
                    </li>
                    
                    <!-- Services Dropdown (Dynamic from HeroTabs) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button">
                            <i class="fas fa-plane"></i> @lang('nav.services') <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            @foreach($navTabs as $tab)
                                @if($tab instanceof \App\Models\HeroTab)
                                <li>
                                    <a class="dropdown-item" href="{{ $tab->button_url_resolved ?? '#' }}">
                                        <i class="{{ $tab->icon ?? 'fas fa-angle-right' }}"></i>
                                        {{ $tab->translated_label }}
                                    </a>
                                </li>
                                @endif
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('services', ['locale' => app()->getLocale()]) }}">@lang('nav.all_services')</a></li>
                        </ul>
                    </li>
                    
                    <!-- About -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-info-circle"></i> @lang('nav.about')
                        </a>
                    </li>
                    
                    <!-- Contact -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-envelope"></i> @lang('nav.contact')
                        </a>
                    </li>
                    
                    <!-- Careers -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('careers') ? 'active' : '' }}" href="{{ route('careers', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-briefcase"></i> @lang('nav.careers')
                        </a>
                    </li>
                    
                    <!-- Mobile Portal Links -->
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-headset me-1"></i> {{ __('nav.support') }}
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ route('portal.login', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('login') }}
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link text-primary" href="{{ route('portal.register', ['locale' => app()->getLocale()]) }}">
                            <i class="fas fa-user-plus me-1"></i> {{ __('register') }}
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- CTA Button -->
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="btn btn-primary d-none d-lg-flex header-cta">
                <i class="fas fa-phone"></i> @lang('nav.get_quote')
            </a>
        </div>
    </nav>
    
    <!-- Notice Ticker (if active notices exist) -->
    @php
        $activeNotices = \App\Models\Notice::active()->current()->forLocale()->ordered()->take(1)->get();
    @endphp
    @if($activeNotices->isNotEmpty())
        <div class="notice-ticker">
            <div class="container">
                <div class="ticker-content">
                    <span class="ticker-badge"><i class="fas fa-bullhorn"></i> @lang('common.announcement')</span>
                    <span class="ticker-text">{{ $activeNotices->first()->translated_content }}</span>
                </div>
            </div>
        </div>
    @endif
</header>

<style>
/* Header Styles - Modern & Dynamic */
.main-header {
    background: #fff;
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    position: relative;
    z-index: 1000;
}

/* Top Bar */
.header-topbar {
    background: linear-gradient(135deg, #343C90 0%, #252E72 100%);
    color: #fff;
    padding: 10px 0;
    font-size: 13px;
}

.header-topbar a {
    color: #fff;
    text-decoration: none;
    margin-right: 15px;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.header-topbar a:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}

.header-topbar .fa-phone,
.header-topbar .fa-envelope,
.header-topbar .fa-clock {
    margin-right: 5px;
    font-size: 12px;
}

/* Language Switcher */
.language-switcher .lang-btn {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    backdrop-filter: blur(5px);
}

.language-switcher .lang-btn:hover {
    background: rgba(255,255,255,0.25);
}

.language-switcher .dropdown-menu {
    min-width: 130px;
    border-radius: 12px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    border: none;
    padding: 8px;
    animation: dropdownFade 0.25s ease;
}

.language-switcher .dropdown-item {
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    transition: all 0.2s;
}

.language-switcher .dropdown-item:hover {
    background: #343C90;
    color: #fff;
}

/* Main Navigation */
.navbar {
    padding: 15px 0;
    background: #fff;
    transition: all 0.3s;
}

.navbar-brand {
    font-weight: 800;
    font-size: 26px;
    color: #343C90 !important;
    text-decoration: none;
    transition: all 0.3s;
}

.navbar-brand:hover {
    transform: scale(1.02);
}

.brand-text {
    color: #343C90;
    font-weight: 800;
    font-size: 24px;
}

/* Nav Links - Modern Style */
.navbar-nav .nav-link {
    color: #374151;
    font-weight: 600;
    padding: 12px 18px;
    transition: all 0.3s;
    position: relative;
    font-size: 15px;
}

.navbar-nav .nav-link i {
    font-size: 11px;
    margin-left: 4px;
    transition: transform 0.3s;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: #E05522;
}

/* Underline Animation */
.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 18px;
    right: 18px;
    height: 3px;
    background: linear-gradient(90deg, #E05522, #C94718);
    border-radius: 2px;
    transform: scaleX(0);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar-nav .nav-link:hover::after,
.navbar-nav .nav-link.active::after {
    transform: scaleX(1);
}

/* Dropdown - Show on Hover */
.navbar-nav .dropdown {
    position: relative;
}

.navbar-nav .dropdown-menu {
    display: block;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    padding: 15px;
    min-width: 220px;
    margin-top: 0;
}

.navbar-nav .dropdown:hover > .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.navbar-nav .dropdown:hover .nav-link i {
    transform: rotate(180deg);
}

/* Dropdown Items */
.dropdown-menu .dropdown-item {
    border-radius: 10px;
    padding: 12px 16px;
    font-weight: 500;
    color: #374151;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
}

.dropdown-menu .dropdown-item:last-child {
    margin-bottom: 0;
}

.dropdown-menu .dropdown-item:hover {
    background: linear-gradient(135deg, #343C90 0%, #252E72 100%);
    color: #fff;
    transform: translateX(5px);
}

.dropdown-menu .dropdown-item i {
    font-size: 14px;
    color: #E05522;
    width: 20px;
    text-align: center;
}

.dropdown-menu .dropdown-item:hover i {
    color: #fff;
}

.dropdown-divider {
    border-color: #f0f0f0;
    margin: 8px 0;
}

/* CTA Button */
.header-cta {
    margin-left: 15px;
    padding: 12px 24px;
    border-radius: 30px;
    background: linear-gradient(135deg, #E05522 0%, #C94718 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(224, 85, 34, 0.3);
}

.header-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(224, 85, 34, 0.4);
    color: #fff;
}

.header-cta i {
    margin-right: 6px;
}

/* Mobile Toggle */
.navbar-toggler {
    border: 2px solid #343C90;
    padding: 8px 10px;
    border-radius: 8px;
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(52, 60, 144, 0.2);
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23343C90' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Notice Ticker */
.notice-ticker {
    background: linear-gradient(90deg, #E05522 0%, #C94718 100%);
    color: #fff;
    padding: 8px 0;
    font-size: 13px;
}

.ticker-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ticker-badge {
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 15px;
    font-weight: 600;
}

.ticker-text {
    animation: ticker 25s linear infinite;
}

@keyframes ticker {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

/* Animations */
@keyframes dropdownFade {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Portal Links */
.portal-links .btn-outline-light {
    border-color: rgba(255,255,255,0.3);
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 20px;
}

.portal-links .btn-outline-light:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
}

.portal-links .btn-light {
    border-radius: 20px;
    font-size: 12px;
    padding: 6px 12px;
}

/* Responsive */
@media (max-width: 991px) {
    .header-topbar {
        display: none;
    }
    
    .navbar {
        padding: 10px 0;
    }
    
    .navbar-collapse {
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        margin-top: 15px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .navbar-nav .nav-link {
        padding: 14px 15px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
    }
    
    .navbar-nav .nav-link::after {
        display: none;
    }
    
    .navbar-nav .dropdown-menu {
        display: none;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        padding: 0 0 0 20px;
        border-radius: 0;
        background: #f8fafc;
    }
    
    .navbar-nav .dropdown:hover > .dropdown-menu {
        display: block;
    }
    
    .header-cta {
        display: none !important;
    }
    
    .portal-links {
        display: none !important;
    }
    
    .mobile-auth {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-top: 10px;
    }
}
</style>
