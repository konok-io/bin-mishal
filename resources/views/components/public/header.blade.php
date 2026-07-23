{{-- Public Header Component --}}

<header class="site-header" x-data="{ mobileOpen: false, scrolled: false }" 
    @scroll.window="scrolled = window.scrollY > 50"
    :class="{ 'is-scrolled': scrolled }"
>
    {{-- Top Bar --}}
    <div class="top-bar">
        <div class="container">
            <div class="top-bar__content">
                <div class="top-bar__left">
                    <x-public.menu location="top_bar" />
                </div>
                <div class="top-bar__right">
                    <x-language-switcher />
                </div>
            </div>
        </div>
    </div>

    {{-- Main Navigation --}}
    <div class="main-nav">
        <div class="container">
            <div class="main-nav__content">
                {{-- Logo --}}
                <a href="{{ locale_route('home') }}" class="logo">
                    <span class="logo__text">{{ __('app.app_name') }}</span>
                </a>

                {{-- Desktop Menu --}}
                <nav class="nav-desktop">
                    <x-public.menu location="header" />
                </nav>

                {{-- CTA Button --}}
                <div class="nav-actions">
                    <a href="{{ locale_route('contact') }}" class="btn btn-primary">
                        {{ __('navigation.book_now') }}
                    </a>
                    
                    {{-- Mobile Toggle --}}
                    <button 
                        class="nav-toggle"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen"
                        aria-label="Toggle navigation"
                    >
                        <span x-show="!mobileOpen">
                            <x-heroicon-s-bars-3 class="w-6 h-6" />
                        </span>
                        <span x-show="mobileOpen">
                            <x-heroicon-s-x-mark class="w-6 h-6" />
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div class="nav-mobile" x-show="mobileOpen" x-transition>
        <div class="container">
            <nav class="nav-mobile__menu">
                <x-public.menu location="mobile" />
            </nav>
            
            <div class="nav-mobile__actions">
                <x-language-switcher />
                <a href="{{ locale_route('contact') }}" class="btn btn-primary btn-block">
                    {{ __('navigation.book_now') }}
                </a>
            </div>
        </div>
    </div>
</header>

@push('styles')
<style>
.site-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #fff;
    transition: box-shadow 0.3s;
}

.site-header.is-scrolled {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.top-bar {
    background: #1f2937;
    color: #fff;
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.top-bar__content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar .menu {
    display: flex;
    gap: 1rem;
}

.top-bar .menu-item a {
    color: #fff;
    opacity: 0.9;
}

.top-bar .menu-item a:hover {
    opacity: 1;
}

.main-nav {
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.main-nav__content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color, #059669);
    text-decoration: none;
}

.nav-desktop {
    display: none;
}

@media (min-width: 1024px) {
    .nav-desktop {
        display: block;
    }
}

.nav-desktop .menu {
    display: flex;
    gap: 2rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-desktop .menu-item a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.nav-desktop .menu-item a:hover,
.nav-desktop .menu-item.is-active a {
    color: var(--primary-color, #059669);
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.nav-toggle {
    display: flex;
    padding: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
}

@media (min-width: 1024px) {
    .nav-toggle {
        display: none;
    }
}

.nav-mobile {
    display: none;
    padding: 1rem 0;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
}

.nav-mobile.is-open {
    display: block;
}

@media (min-width: 1024px) {
    .nav-mobile {
        display: none !important;
    }
}

.nav-mobile__menu .menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-mobile__menu .menu-item a {
    display: block;
    padding: 1rem 0;
    color: #374151;
    text-decoration: none;
    border-bottom: 1px solid #e5e7eb;
}

.nav-mobile__actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}

/* RTL Support */
[dir="rtl"] .top-bar__content {
    flex-direction: row-reverse;
}

[dir="rtl"] .main-nav__content {
    flex-direction: row-reverse;
}

[dir="rtl"] .nav-desktop .menu {
    flex-direction: row-reverse;
}

[dir="rtl"] .nav-actions {
    flex-direction: row-reverse;
}
</style>
@endpush
