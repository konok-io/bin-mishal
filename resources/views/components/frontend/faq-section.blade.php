<?php
use App\Models\Faq;
?>

<!-- FAQ Section Component -->
<section class="faq-section py-5" id="faqSection">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5">
            <span class="section-badge">@lang('faq.title')</span>
            <h2 class="section-title">@lang('faq.subtitle')</h2>
            <p class="section-subtitle">@lang('faq.description')</p>
        </div>
        
        <!-- FAQ Accordion -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion faq-accordion" id="faqAccordion">
                    @foreach(Faq::active()->take(10)->get() as $index => $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq{{ $faq->id }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                    <span class="faq-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    {{ $faq->question }}
                                </button>
                            </h3>
                            <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Default FAQs if no data -->
                    @if(Faq::active()->take(10)->get()->isEmpty())
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                                    <span class="faq-number">01</span>
                                    @lang('faq.default_q1')
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>@lang('faq.default_a1')</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <span class="faq-number">02</span>
                                    @lang('faq.default_q2')
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>@lang('faq.default_a2')</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <span class="faq-number">03</span>
                                    @lang('faq.default_q3')
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>@lang('faq.default_a3')</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <span class="faq-number">04</span>
                                    @lang('faq.default_q4')
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>@lang('faq.default_a4')</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- View All FAQ -->
                <div class="text-center mt-4">
                    <a href="{{ route('faqs', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-question-circle"></i> @lang('faq.view_all')
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* FAQ Section */
.faq-section {
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
}

.faq-accordion {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.accordion-item {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.accordion-header {
    margin: 0;
}

.accordion-button {
    width: 100%;
    padding: 20px 25px;
    background: #fff;
    border: none;
    text-align: left;
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.accordion-button:hover {
    color: var(--primary-color, #E05522);
}

.accordion-button:not(.collapsed) {
    background: var(--primary-color, #E05522);
    color: #fff;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: transparent;
}

.faq-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: rgba(0,108,53,0.1);
    border-radius: 50%;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary-color, #E05522);
    flex-shrink: 0;
}

.accordion-button:not(.collapsed) .faq-number {
    background: rgba(255,255,255,0.2);
    color: #fff;
}

.accordion-button::after {
    display: none;
}

.accordion-collapse {
    background: #fff;
}

.accordion-body {
    padding: 0 25px 25px 75px;
    color: #666;
    line-height: 1.8;
}

/* Responsive */
@media (max-width: 767px) {
    .accordion-button {
        padding: 15px 20px;
        font-size: 0.95rem;
    }
    
    .faq-number {
        width: 30px;
        height: 30px;
        font-size: 0.75rem;
    }
    
    .accordion-body {
        padding-left: 25px;
    }
}
</style>
