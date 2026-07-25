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
                    
                    <!-- Auth Links -->
                    <div class="auth-links d-none d-lg-flex">
                        <a href="{{ route('portal.login', ['locale' => app()->getLocale()]) }}" class="me-2" title="{{ __('auth.login') }}">{{ __('auth.login') }}</a>
                        <a href="{{ route('portal.register', ['locale' => app()->getLocale()]) }}" class="btn btn-sm btn-primary" title="{{ __('auth.register') }}">{{ __('auth.register') }}</a>
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
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-plane"></i> @lang('nav.services')
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
                    
                    <!-- Mobile Auth -->
                    <li class="nav-item d-lg-none mobile-auth">
                        <a class="nav-link" href="{{ route('portal.login', ['locale' => app()->getLocale()]) }}" title="{{ __('auth.login') }}">{{ __('auth.login') }}</a>
                    </li>
                    <li class="nav-item d-lg-none mobile-auth">
                        <a class="nav-link text-primary" href="{{ route('portal.register', ['locale' => app()->getLocale()]) }}" title="{{ __('auth.register') }}">{{ __('auth.register') }}</a>
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
/* Header Styles */
.main-header {
    background: #fff;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    position: relative;
    z-index: 1000;
}

.header-topbar {
    background: var(--primary-color, #343C90);
    color: #fff;
    padding: 8px 0;
    font-size: 13px;
}

.header-topbar a {
    color: #fff;
    text-decoration: none;
    margin-right: 15px;
    transition: opacity 0.3s;
}

.header-topbar a:hover {
    opacity: 0.8;
}

.header-topbar .fa-phone,
.header-topbar .fa-envelope,
.header-topbar .fa-clock {
    margin-right: 5px;
}

.language-switcher .lang-btn {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
}

.language-switcher .dropdown-menu {
    min-width: 120px;
    border-radius: 8px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.language-switcher .dropdown-item {
    padding: 8px 15px;
    font-size: 13px;
}

.navbar {
    padding: 12px 0;
    background: #fff;
}

.navbar-brand {
    font-weight: 700;
    font-size: 24px;
    color: var(--primary-color, #343C90) !important;
}

.brand-text {
    color: var(--primary-color, #343C90);
    font-weight: 800;
    font-size: 22px;
}

.navbar-nav .nav-link {
    color: #333;
    font-weight: 500;
    padding: 10px 15px;
    transition: all 0.3s;
    position: relative;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: var(--primary-color, #343C90);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 15px;
    right: 15px;
    height: 2px;
    background: var(--primary-color, #343C90);
    transform: scaleX(0);
    transition: transform 0.3s;
}

.navbar-nav .nav-link:hover::after,
.navbar-nav .nav-link.active::after {
    transform: scaleX(1);
}

.navbar-toggler {
    border: 2px solid var(--primary-color, #343C90);
    padding: 8px;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23006C35' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

.header-cta {
    margin-left: 20px;
    padding: 10px 20px;
    border-radius: 25px;
}

/* Notice Ticker */
.notice-ticker {
    background: linear-gradient(90deg, #ff6b6b 0%, #ee5a24 100%);
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
    padding: 3px 10px;
    border-radius: 15px;
    font-weight: 600;
}

.ticker-text {
    animation: ticker 20s linear infinite;
}

@keyframes ticker {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

/* Dropdown Animation */
.dropdown-menu {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    padding: 10px;
    animation: dropdownFade 0.3s ease;
}

@keyframes dropdownFade {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
    border-radius: 8px;
    padding: 10px 15px;
    font-weight: 500;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background: var(--primary-color, #343C90);
    color: #fff;
}

.dropdown-item i {
    margin-right: 8px;
    color: var(--primary-color, #343C90);
}

.dropdown-item:hover i {
    color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
    .header-topbar {
        display: none;
    }
    
    .navbar-collapse {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        margin-top: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .navbar-nav .nav-link {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .navbar-nav .nav-link::after {
        display: none;
    }
    
    .header-cta {
        display: none !important;
    }
    
    .mobile-auth {
        border-top: 1px solid #eee;
        padding-top: 10px;
    }
}
</style>
