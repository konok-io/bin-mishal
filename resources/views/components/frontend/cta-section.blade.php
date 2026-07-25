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
<section class="partners-section" id="partnersSection">
    <div class="container-fluid px-4">
        <div class="text-center mb-3">
            <h6 class="partners-title mb-0">@lang('partners.title')</h6>
        </div>
        <div class="partners-track">
            <div class="partner-logo">
                <i class="fas fa-plane"></i>
                <span>Saudi Airlines</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-plane-departure"></i>
                <span>Biman Bangladesh</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-globe-americas"></i>
                <span>US-Bangla</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-plane-arrival"></i>
                <span>Flydubai</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-suitcase-rolling"></i>
                <span>Air Arabia</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-earth-americas"></i>
                <span>Qatar Airways</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-cloud-moon"></i>
                <span>Oman Air</span>
            </div>
            <div class="partner-logo">
                <i class="fas fa-mosque"></i>
                <span>Emirates</span>
            </div>
        </div>
    </div>
</section>

<style>
/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--primary-color, #343C90) 0%, #252E72 100%);
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
    color: var(--secondary-color, #E05522);
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
    color: var(--primary-color, #343C90);
    border: none;
    transition: all 0.3s ease;
}

.cta-btn-primary:hover {
    background: var(--secondary-color, #E05522);
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
    padding: 20px 0;
    border-top: 1px solid #eee;
}

.partners-title {
    color: #343C90;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.partners-track {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
}

.partner-logo {
    flex-shrink: 0;
    min-width: 100px;
    max-width: 130px;
    height: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 8px 12px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border: 1px solid #eee;
    transition: all 0.3s ease;
}

.partner-logo:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(52, 60, 144, 0.1);
    border-color: #343C90;
}

.partner-logo i {
    font-size: 18px;
    color: var(--primary-color, #343C90);
    transition: all 0.3s ease;
}

.partner-logo:hover i {
    color: var(--secondary-color, #E05522);
}

.partner-logo span {
    font-size: 10px;
    font-weight: 600;
    color: #555;
    text-align: center;
    white-space: nowrap;
    line-height: 1.2;
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
