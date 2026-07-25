<?php
use App\Models\CMS\Setting;
?>

<!-- CTA Section Component -->
<section class="cta-section py-5" id="ctaSection">
    <div class="container">
        <div class="cta-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="cta-content">
                        <h2 class="cta-title">@lang('cta.title')</h2>
                        <p class="cta-subtitle">@lang('cta.subtitle')</p>
                        <div class="cta-features">
                            <div class="cta-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>@lang('cta.feature_1')</span>
                            </div>
                            <div class="cta-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>@lang('cta.feature_2')</span>
                            </div>
                            <div class="cta-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>@lang('cta.feature_3')</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cta-buttons">
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="btn btn-light btn-lg cta-btn-primary">
                            <i class="fas fa-phone"></i> @lang('cta.contact_now')
                        </a>
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', Setting::getValue('contact_whatsapp', '966XXXXXXXX')) }}" target="_blank" class="btn btn-outline-light btn-lg cta-btn-whatsapp">
                            <i class="fab fa-whatsapp"></i> @lang('cta.whatsapp')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partner Logos Section -->
<section class="partners-section py-4" id="partnersSection">
    <div class="container">
        <div class="text-center mb-4">
            <h5 class="partners-title">@lang('partners.title')</h5>
        </div>
        <div class="partners-slider">
            <div class="partners-track">
                <!-- Airlines/Partners -->
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Saudi_Airlines_logo.svg/200px-Saudi_Airlines_logo.svg.png" alt="Saudi Airlines">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Lufthansa_Logo.svg/200px-Lufthansa_Logo.svg.png" alt="Lufthansa">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Qatar_Airways_Logo.svg/200px-Qatar_Airways_Logo.svg.png" alt="Qatar Airways">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Emirates_Airlines_logo.svg/200px-Emirates_Airlines_logo.svg.png" alt="Emirates">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Oman_Air_Logo.svg/200px-Oman_Air_Logo.svg.png" alt="Oman Air">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/US-Bangla_Airlines_Logo.svg/200px-US-Bangla_Airlines_Logo.svg.png" alt="US-Bangla">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--primary-color, #2F378A) 0%, #242E75 100%);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.cta-section::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.cta-wrapper {
    position: relative;
    z-index: 1;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    padding: 50px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.cta-content {
    color: #fff;
}

.cta-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.cta-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 25px;
}

.cta-features {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.cta-feature {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.cta-feature i {
    color: var(--secondary-color, #E25A24);
}

.cta-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.cta-btn-primary {
    padding: 15px 30px;
    border-radius: 30px;
    font-weight: 600;
    background: #fff;
    color: var(--primary-color, #2F378A);
    border: none;
    transition: all 0.3s ease;
}

.cta-btn-primary:hover {
    background: var(--secondary-color, #E25A24);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.cta-btn-whatsapp {
    padding: 15px 30px;
    border-radius: 30px;
    font-weight: 600;
    border: 2px solid #fff;
    color: #fff;
    transition: all 0.3s ease;
}

.cta-btn-whatsapp:hover {
    background: #25D366;
    border-color: #25D366;
    transform: translateY(-3px);
}

/* Partners Section */
.partners-section {
    background: #f8fafc;
    border-top: 1px solid #eee;
}

.partners-title {
    color: #666;
    font-weight: 500;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.partners-slider {
    overflow: hidden;
    position: relative;
}

.partners-track {
    display: flex;
    gap: 50px;
    animation: scroll 30s linear infinite;
}

.partner-logo {
    flex-shrink: 0;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.partner-logo img {
    max-height: 50px;
    width: auto;
    filter: grayscale(100%);
    opacity: 0.6;
    transition: all 0.3s ease;
}

.partner-logo img:hover {
    filter: grayscale(0%);
    opacity: 1;
}

@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* Responsive */
@media (max-width: 991px) {
    .cta-wrapper {
        padding: 35px 25px;
        text-align: center;
    }
    
    .cta-title {
        font-size: 1.6rem;
    }
    
    .cta-features {
        justify-content: center;
    }
    
    .cta-buttons {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (max-width: 767px) {
    .cta-buttons {
        flex-direction: column;
    }
}
</style>
