<?php
use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use App\Models\Setting;
use App\Services\CMS\MenuBuilder;

// Get settings values
$siteName = Setting::getValue('app_name', 'Bin Mishal Travel');
$contactPhone = Setting::getValue('contact_phone', '+966 13 XXXXXXX');
$contactEmail = Setting::getValue('contact_email', 'info@binmishal.com');
$workingHours = Setting::getValue('working_hours', 'Sat-Thu: 9AM-9PM');
$logoUrl = Setting::getValue('logo_light') ? Storage::url(Setting::getValue('logo_light')) : null;
$showLogin = Setting::getValue('show_login_button', true);
$showRegister = Setting::getValue('show_register_button', true);

// Get header menu from CMS
$menuBuilder = app(MenuBuilder::class);
$headerMenu = $menuBuilder->header();

// Fallback menu if no CMS menu exists
$fallbackMenu = [
    ['title' => __('nav.home'), 'url' => locale_route('home'), 'icon' => 'fas fa-home', 'is_active' => request()->routeIs('home'), 'children' => []],
    ['title' => __('nav.services'), 'url' => '#', 'icon' => 'fas fa-plane', 'is_active' => false, 'children' => [
        ['title' => __('nav.umrah'), 'url' => locale_route('services.umrah'), 'icon' => 'fas fa-kaaba'],
        ['title' => __('nav.visa'), 'url' => locale_route('services.visa'), 'icon' => 'fas fa-passport'],
        ['title' => __('nav.airticket'), 'url' => locale_route('services.airticket'), 'icon' => 'fas fa-plane-departure'],
        ['title' => __('nav.hotel'), 'url' => locale_route('services.hotel'), 'icon' => 'fas fa-hotel'],
        ['title' => __('nav.cargo'), 'url' => locale_route('services.cargo'), 'icon' => 'fas fa-truck'],
        ['title' => __('nav.investor'), 'url' => locale_route('investor'), 'icon' => 'fas fa-handshake'],
    ]],
    ['title' => __('nav.about'), 'url' => locale_route('about'), 'icon' => 'fas fa-info-circle', 'is_active' => request()->routeIs('about'), 'children' => []],
    ['title' => __('nav.news'), 'url' => locale_route('news'), 'icon' => 'fas fa-newspaper', 'is_active' => request()->routeIs('news'), 'children' => []],
    ['title' => __('nav.gallery'), 'url' => locale_route('gallery'), 'icon' => 'fas fa-images', 'is_active' => request()->routeIs('gallery'), 'children' => []],
    ['title' => __('nav.careers'), 'url' => locale_route('careers'), 'icon' => 'fas fa-briefcase', 'is_active' => request()->routeIs('careers'), 'children' => []],
    ['title' => __('nav.contact'), 'url' => locale_route('contact'), 'icon' => 'fas fa-envelope', 'is_active' => request()->routeIs('contact'), 'children' => []],
];

$navItems = !empty($headerMenu) ? $headerMenu : $fallbackMenu;
?>

<!-- Dynamic Header Component -->
<header class="main-header" id="mainHeader">
    <!-- Top Bar -->
    <div class="header-topbar">
        <div class="container">
            <div class="topbar-inner">
                <!-- Left: Contact Info -->
                <div class="topbar-left">
                    <a href="tel:{{ $contactPhone }}" class="topbar-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ $contactPhone }}</span>
                    </a>
                    <a href="mailto:{{ $contactEmail }}" class="topbar-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $contactEmail }}</span>
                    </a>
                    <span class="topbar-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $workingHours }}</span>
                    </span>
                </div>
                
                <!-- Right: Actions -->
                <div class="topbar-right">
                    <!-- Portal Links -->
                    <div class="portal-links">
                        @if($showLogin)
                        <a href="{{ locale_route('portal.login') }}" class="btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>{{ __('nav.login') }}</span>
                        </a>
                        @endif
                        @if($showRegister)
                        <span class="btn-divider">|</span>
                        <a href="{{ locale_route('portal.register') }}" class="btn">
                            <i class="fas fa-user-plus"></i>
                            <span>{{ __('nav.register') }}</span>
                        </a>
                        @endif
                    </div>
                    
                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <button class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ switch_locale_url('en') }}">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="{{ switch_locale_url('bn') }}">🇧🇩 বাংলা</a></li>
                            <li><a class="dropdown-item" href="{{ switch_locale_url('ar') }}">🇸🇦 العربية</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ locale_route('home') }}">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" height="45">
                @else
                    <span class="brand-text">{{ $siteName }}</span>
                @endif
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    @foreach($navItems as $item)
                    <li class="nav-item {{ !empty($item['children']) ? 'dropdown' : '' }}">
                        @if(!empty($item['children']))
                        <a class="nav-link dropdown-toggle" href="#" id="menu-{{ $loop->index }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                            {{ $item['title'] }} <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menu-{{ $loop->index }}">
                            @foreach($item['children'] as $child)
                            <li>
                                <a class="dropdown-item" href="{{ $child['url'] ?? '#' }}" {{ !empty($child['target']) ? 'target="'.$child['target'].'"' : '' }}>
                                    @if(!empty($child['icon']))<i class="{{ $child['icon'] }}"></i>@endif
                                    {{ $child['title'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <a class="nav-link {{ $item['is_active'] ?? false ? 'active' : '' }}" href="{{ $item['url'] ?? '#' }}" {{ !empty($item['target']) ? 'target="'.$item['target'].'"' : '' }}>
                            @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                            {{ $item['title'] }}
                        </a>
                        @endif
                    </li>
                    @endforeach
                    
                    <!-- Mobile Portal Links (visible only on mobile) -->
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ locale_route('contact') }}">
                            <i class="fas fa-headset me-1"></i> {{ __('nav.support') }}
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ locale_route('portal.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('nav.login') }}
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link text-primary" href="{{ locale_route('portal.register') }}">
                            <i class="fas fa-user-plus me-1"></i> {{ __('nav.register') }}
                        </a>
                    </li>
                </ul>
            </div>
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
/* Header Styles - Clean & Fast */
.main-header {
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    position: relative;
    z-index: 1000;
}

/* Top Bar - Modern Design */
.header-topbar {
    background: linear-gradient(135deg, #343C90 0%, #252E72 100%);
    color: #fff;
    padding: 12px 0;
    font-size: 13px;
}

.topbar-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.topbar-item {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s;
}

.topbar-item:hover {
    color: #fff;
}

.topbar-item i {
    font-size: 12px;
    opacity: 0.8;
}

/* Language Switcher - Modern Pill */
.language-switcher .lang-btn {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.language-switcher .lang-btn:hover {
    background: rgba(255,255,255,0.25);
}

.language-switcher .dropdown-menu {
    min-width: 140px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    border: none;
    padding: 8px;
}

.language-switcher .dropdown-item {
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.language-switcher .dropdown-item:hover {
    background: #343C90;
    color: #fff;
}

/* Portal Buttons - Simple Text Style */
.portal-links {
    display: flex;
    align-items: center;
    gap: 5px;
}

.portal-links .btn {
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: transparent;
    color: #fff;
    text-decoration: none;
    transition: opacity 0.2s;
}

.portal-links .btn:hover {
    opacity: 0.8;
    color: #fff;
}

.portal-links .btn i {
    font-size: 12px;
}

.portal-links .btn-divider {
    color: rgba(255,255,255,0.3);
    font-size: 14px;
}

/* Main Navigation */
.navbar {
    padding: 12px 0;
    background: #fff;
}

.navbar-brand {
    font-weight: 800;
    font-size: 26px;
    color: #343C90 !important;
    text-decoration: none;
}

.brand-text {
    color: #343C90;
    font-weight: 800;
    font-size: 24px;
}

/* Nav Links */
.navbar-nav .nav-link {
    color: #374151;
    font-weight: 600;
    padding: 10px 16px;
    font-size: 15px;
}

.navbar-nav .nav-link i {
    font-size: 10px;
    margin-left: 4px;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: #E05522;
}

/* Dropdown - Hover with Animation */
.navbar-nav .dropdown {
    position: relative;
}

.navbar-nav .dropdown-menu {
    display: block;
    visibility: hidden;
    opacity: 0;
    position: absolute;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    padding: 12px;
    min-width: 200px;
    top: 100%;
    transform: translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.navbar-nav .dropdown:hover > .dropdown-menu,
.navbar-nav .dropdown.show > .dropdown-menu {
    visibility: visible;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

/* Dropdown Items */
.dropdown-menu .dropdown-item {
    border-radius: 8px;
    padding: 10px 14px;
    font-weight: 500;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 2px;
}

.dropdown-menu .dropdown-item:last-child {
    margin-bottom: 0;
}

.dropdown-menu .dropdown-item:hover {
    background: #343C90;
    color: #fff;
}

.dropdown-menu .dropdown-item i {
    font-size: 13px;
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

/* Mobile Toggle */
.navbar-toggler {
    border: 2px solid #343C90;
    padding: 8px 10px;
    border-radius: 8px;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23343C90' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

/* Notice Ticker */
.notice-ticker {
    background: #E05522;
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
    border-radius: 12px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 991px) {
    .header-topbar {
        padding: 10px 0;
    }
    
    .topbar-inner {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .topbar-left {
        justify-content: center;
        gap: 15px;
    }
    
    .topbar-item span {
        display: none;
    }
    
    .topbar-item {
        font-size: 16px;
    }
    
    .topbar-right {
        justify-content: center;
    }
    
    .portal-links .btn span {
        display: none;
    }
    
    .portal-links .btn {
        padding: 10px 14px;
    }
    
    .navbar {
        padding: 10px 0;
    }
    
    .navbar-collapse {
        background: #fff;
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }
    
    .navbar-nav .nav-link {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .navbar-nav .dropdown-menu {
        display: none;
        box-shadow: none;
        padding: 0 0 0 20px;
        background: #f8fafc;
    }
    
    .navbar-nav .dropdown:hover > .dropdown-menu {
        display: block;
    }
    
    .portal-links {
        display: none !important;
    }
}
</style>
