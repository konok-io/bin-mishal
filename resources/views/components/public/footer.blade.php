{{-- Public Footer Component --}}

<footer class="site-footer">
    {{-- Main Footer --}}
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                {{-- Column 1: About --}}
                <div class="footer-col footer-col--about">
                    <a href="{{ locale_route('home') }}" class="footer-logo">
                        {{ __('app.app_name') }}
                    </a>
                    <p class="footer-about__text">
                        {{ __('app.footer_description') ?? 'Your trusted partner for Umrah, Visa & Travel Services in Saudi Arabia.' }}
                    </p>
                    <div class="footer-social">
                        @if(setting('facebook_url'))
                            <a href="{{ setting('facebook_url') }}" target="_blank" rel="noopener" aria-label="Facebook">
                                <x-heroicon-s-globe-alt class="w-5 h-5" />
                            </a>
                        @endif
                        @if(setting('whatsapp_number'))
                            <a href="https://wa.me/{{ setting('whatsapp_number') }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <x-heroicon-s-globe-alt class="w-5 h-5" />
                            </a>
                        @endif
                        @if(setting('instagram_url'))
                            <a href="{{ setting('instagram_url') }}" target="_blank" rel="noopener" aria-label="Instagram">
                                <x-heroicon-s-globe-alt class="w-5 h-5" />
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Column 2: Services Links --}}
                <div class="footer-col">
                    <h4 class="footer-col__title">{{ __('navigation.services') }}</h4>
                    <x-public.menu location="footer_col1" />
                </div>

                {{-- Column 3: Quick Links --}}
                <div class="footer-col">
                    <h4 class="footer-col__title">{{ __('navigation.quick_links') }}</h4>
                    <x-public.menu location="footer_col2" />
                </div>

                {{-- Column 4: Contact Info --}}
                <div class="footer-col">
                    <h4 class="footer-col__title">{{ __('navigation.contact') }}</h4>
                    <ul class="footer-contact">
                        @if(setting('company_phone'))
                            <li>
                                <x-heroicon-s-phone class="w-5 h-5" />
                                <span>{{ setting('company_phone') }}</span>
                            </li>
                        @endif
                        @if(setting('company_email'))
                            <li>
                                <x-heroicon-s-envelope class="w-5 h-5" />
                                <span>{{ setting('company_email') }}</span>
                            </li>
                        @endif
                        @if(setting('company_address'))
                            <li>
                                <x-heroicon-s-map-pin class="w-5 h-5" />
                                <span>{{ setting('company_address') }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom__content">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} {{ __('app.app_name') }}. {{ __('common.all_rights_reserved') }}.
                </p>
                <x-public.menu location="footer_bottom" />
            </div>
        </div>
    </div>
</footer>

@push('styles')
<style>
.site-footer {
    background: #1f2937;
    color: #fff;
    margin-top: auto;
}

.footer-main {
    padding: 4rem 0;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 768px) {
    .footer-grid {
        grid-template-columns: 2fr 1fr 1fr 1fr;
    }
}

.footer-col__title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #fff;
}

.footer-logo {
    display: inline-block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    margin-bottom: 1rem;
}

.footer-about__text {
    color: #9ca3af;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.footer-social {
    display: flex;
    gap: 1rem;
}

.footer-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    color: #fff;
    transition: background 0.2s;
}

.footer-social a:hover {
    background: var(--primary-color, #059669);
}

.footer-col .menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-col .menu-item a {
    display: block;
    padding: 0.5rem 0;
    color: #9ca3af;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-col .menu-item a:hover {
    color: #fff;
}

.footer-contact {
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-contact li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: #9ca3af;
}

.footer-contact svg {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.footer-bottom {
    padding: 1.5rem 0;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.footer-bottom__content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
    text-align: center;
}

@media (min-width: 768px) {
    .footer-bottom__content {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
    }
}

.footer-copyright {
    color: #9ca3af;
    font-size: 0.875rem;
}

.footer-bottom .menu {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-bottom .menu-item a {
    color: #9ca3af;
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.2s;
}

.footer-bottom .menu-item a:hover {
    color: #fff;
}

/* RTL Support */
[dir="rtl"] .footer-contact li {
    flex-direction: row-reverse;
}

[dir="rtl"] .footer-bottom .menu {
    flex-direction: row-reverse;
}
</style>
@endpush
