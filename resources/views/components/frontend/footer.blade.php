<?php
use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use App\Models\CMS\Setting;
use App\Models\SocialLink;
?>

<!-- Dynamic Footer Component -->
<footer class="main-footer">
    <!-- Footer Main -->
    <div class="footer-main">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-4">
                        @if(Setting::getValue('logo_light'))
                            <img src="{{ Storage::url(Setting::getValue('logo_light')) }}" alt="{{ Setting::getValue('site_name', 'Bin Mishal') }}" height="50" class="mb-3">
                        @endif
                        <h4>{{ Setting::getValue('site_name', 'Bin Mishal Travels') }}</h4>
                        <p class="text-muted">{{ Setting::getValue('site_tagline', 'Your Trusted Travel Partner') }}</p>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-contact">
                        @if(Setting::getValue('contact_address'))
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ Setting::getValue('contact_address') }}</span>
                            </div>
                        @endif
                        
                        @if(Setting::getValue('contact_phone'))
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:{{ Setting::getValue('contact_phone') }}">{{ Setting::getValue('contact_phone') }}</a>
                            </div>
                        @endif
                        
                        @if(Setting::getValue('contact_email'))
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:{{ Setting::getValue('contact_email') }}">{{ Setting::getValue('contact_email') }}</a>
                            </div>
                        @endif
                        
                        @if(Setting::getValue('working_hours'))
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ Setting::getValue('working_hours') }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Social Links -->
                    <div class="footer-social mt-4">
                        <h5>@lang('footer.follow_us')</h5>
                        <div class="social-icons">
                            @foreach(\App\Models\SocialLink::visible()->ordered()->get() as $social)
                                <a href="{{ $social->url }}" target="_blank" class="social-icon" title="{{ $social->translated_name }}">
                                    <i class="{{ $social->icon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links Column 1 -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">@lang('footer.quick_links')</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}">@lang('nav.home')</a></li>
                        <li><a href="{{ route('about', ['locale' => app()->getLocale()]) }}">@lang('nav.about')</a></li>
                        <li><a href="{{ route('services', ['locale' => app()->getLocale()]) }}">@lang('nav.services')</a></li>
                        <li><a href="{{ route('blog', ['locale' => app()->getLocale()]) }}">@lang('nav.blog')</a></li>
                        <li><a href="{{ route('careers', ['locale' => app()->getLocale()]) }}">@lang('nav.careers')</a></li>
                        <li><a href="{{ route('contact', ['locale' => app()->getLocale()]) }}">@lang('nav.contact')</a></li>
                    </ul>
                </div>
                
                <!-- Services Column -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">@lang('footer.our_services')</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('services.airticket', ['locale' => app()->getLocale()]) }}">@lang('services.flight_booking')</a></li>
                        <li><a href="{{ route('services.umrah', ['locale' => app()->getLocale()]) }}">@lang('services.umrah_packages')</a></li>
                        <li><a href="{{ route('services.visa', ['locale' => app()->getLocale()]) }}">@lang('services.visa_processing')</a></li>
                        <li><a href="{{ route('cargo', ['locale' => app()->getLocale()]) }}">@lang('services.cargo_shipping')</a></li>
                        <li><a href="{{ route('investor', ['locale' => app()->getLocale()]) }}">@lang('services.investment_licenses')</a></li>
                        <li><a href="{{ route('appointment', ['locale' => app()->getLocale()]) }}">@lang('services.appointment')</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter Column -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">@lang('footer.newsletter')</h5>
                    <p class="text-muted mb-3">@lang('footer.newsletter_desc')</p>
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="@lang('footer.enter_email')" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Payment Methods -->
                    <div class="payment-methods mt-4">
                        <h6>@lang('footer.we_accept')</h6>
                        <div class="payment-icons">
                            <img src="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/flags/4x3/sa.svg" alt="SAR" title="Saudi Riyal">
                            <span class="payment-text">SAR</span>
                            <img src="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/flags/4x3/bd.svg" alt="BDT" title="Bangladeshi Taka">
                            <span class="payment-text">BDT</span>
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-apple-pay"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} {{ Setting::getValue('site_name', 'Bin Mishal Travels') }}. 
                        @lang('footer.all_rights')
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="footer-legal">
                        <li><a href="{{ route('privacy-policy', ['locale' => app()->getLocale()]) }}">@lang('legal.privacy_policy')</a></li>
                        <li><a href="{{ route('terms', ['locale' => app()->getLocale()]) }}">@lang('legal.terms_conditions')</a></li>
                        <li><a href="{{ route('refund-policy', ['locale' => app()->getLocale()]) }}">@lang('legal.refund_policy')</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
@if(Setting::getValue('whatsapp_float', true))
    <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', Setting::getValue('contact_whatsapp', '966XXXXXXXX')) }}?text={{ urlencode(Setting::getValue('whatsapp_message', 'Hello! I need help with...')) }}" 
       target="_blank" 
       class="whatsapp-float" 
       title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="whatsapp-tooltip">@lang('footer.chat_whatsapp')</span>
    </a>
@endif

<!-- Back to Top Button -->
@if(Setting::getValue('back_to_top', true))
    <button class="back-to-top" id="backToTop" title="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>
@endif

<style>
/* Footer Styles */
.main-footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #fff;
    position: relative;
}

.footer-main {
    padding: 60px 0 40px;
}

.footer-brand h4 {
    color: #fff;
    font-weight: 700;
    margin-bottom: 5px;
}

.footer-brand p {
    color: rgba(255,255,255,0.7);
}

.footer-title {
    color: #fff;
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: var(--primary-color, #006C35);
    border-radius: 2px;
}

.footer-contact .contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
    color: rgba(255,255,255,0.8);
}

.footer-contact .contact-item i {
    width: 20px;
    margin-right: 10px;
    margin-top: 3px;
    color: var(--secondary-color, #C8A951);
}

.footer-contact .contact-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.3s;
}

.footer-contact .contact-item a:hover {
    color: #fff;
}

.social-icons {
    display: flex;
    gap: 10px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    transition: all 0.3s;
}

.social-icon:hover {
    background: var(--primary-color, #006C35);
    transform: translateY(-3px);
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
}

.footer-links a::before {
    content: '›';
    margin-right: 8px;
    color: var(--secondary-color, #C8A951);
    font-size: 18px;
}

.footer-links a:hover {
    color: #fff;
    transform: translateX(5px);
}

/* Newsletter */
.newsletter-form .input-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.newsletter-form .form-control {
    border: none;
    padding: 12px 20px;
    border-radius: 25px 0 0 25px;
}

.newsletter-form .btn {
    border-radius: 0 25px 25px 0;
    padding: 12px 20px;
    background: var(--primary-color, #006C35);
    border: none;
}

.payment-methods h6 {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
    margin-bottom: 10px;
}

.payment-icons {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.6);
}

.payment-icons img {
    width: 24px;
    height: auto;
    border-radius: 3px;
}

.payment-icons .fab {
    font-size: 24px;
    color: rgba(255,255,255,0.6);
}

/* Footer Bottom */
.footer-bottom {
    background: rgba(0,0,0,0.2);
    padding: 20px 0;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.footer-bottom p {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
}

.footer-legal {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
    justify-content: flex-end;
}

.footer-legal a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.footer-legal a:hover {
    color: #fff;
}

/* WhatsApp Float */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    left: 30px;
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
    transition: all 0.3s;
    text-decoration: none;
}

.whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
}

.whatsapp-tooltip {
    position: absolute;
    left: 70px;
    background: #333;
    color: #fff;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 13px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.whatsapp-float:hover .whatsapp-tooltip {
    opacity: 1;
    visibility: visible;
}

/* Back to Top */
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: var(--primary-color, #006C35);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 9998;
    box-shadow: 0 5px 15px rgba(0,108,53,0.3);
}

.back-to-top.show {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    transform: translateY(-5px);
    background: var(--secondary-color, #C8A951);
}

/* Responsive */
@media (max-width: 767px) {
    .footer-main {
        padding: 40px 0 30px;
    }
    
    .footer-legal {
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .footer-bottom .col-md-6 {
        text-align: center !important;
    }
    
    .whatsapp-float {
        bottom: 20px;
        left: 20px;
        width: 55px;
        height: 55px;
        font-size: 26px;
    }
    
    .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Back to Top
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
