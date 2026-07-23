{{-- Dynamic Hero Section with Service Tabs --}}
@php
    $tabs = \App\Models\HeroTab::getActiveTabs();
    $firstTab = $tabs->first();
@endphp

<section class="hero-section" @if($firstTab && $firstTab->image) style="background-image: url('{{ $firstTab->image_url }}'); background-size: cover; background-position: center;" @endif>
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-7">
                <div class="hero-content" id="heroContent">
                    @if($firstTab)
                        <h1 class="display-4 fw-bold mb-3">{{ $firstTab->translated_title }}</h1>
                        <p class="lead mb-4">{{ $firstTab->translated_subtitle }}</p>
                        
                        @if($firstTab->translated_features)
                            <div class="hero-features mb-4">
                                @foreach($firstTab->translated_features as $feature)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ $firstTab->getButtonUrlResolved() }}" class="btn btn-light btn-lg px-4 me-2">
                            <i class="{{ $firstTab->icon ?? 'fas fa-arrow-right' }} me-2"></i>
                            {{ $firstTab->translated_button_text }}
                        </a>
                    @else
                        <h1 class="display-4 fw-bold mb-3">{{ __('app.app_name') }}</h1>
                        <p class="lead mb-4">{{ __('home.hero_subtitle') }}</p>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="search-widget">
                    {{-- Tab Navigation --}}
                    <ul class="nav nav-tabs search-tabs mb-3" id="heroTabs" role="tablist">
                        @foreach($tabs as $index => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                        id="{{ $tab->tab_key }}-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#{{ $tab->tab_key }}-pane"
                                        type="button" 
                                        role="tab"
                                        data-tab-key="{{ $tab->tab_key }}"
                                        aria-controls="{{ $tab->tab_key }}-pane">
                                    <i class="{{ $tab->icon ?? 'fas fa-circle' }} me-1"></i>
                                    <span class="d-none d-md-inline">{{ $tab->translated_label }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    
                    {{-- Tab Content --}}
                    <div class="tab-content p-3" id="heroTabsContent">
                        @foreach($tabs as $index => $tab)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                 id="{{ $tab->tab_key }}-pane" 
                                 role="tabpanel" 
                                 aria-labelledby="{{ $tab->tab_key }}-tab"
                                 data-title="{{ $tab->translated_title }}"
                                 data-subtitle="{{ $tab->translated_subtitle }}"
                                 data-features='@json($tab->translated_features)'
                                 data-button-text="{{ $tab->translated_button_text }}"
                                 data-button-url="{{ $tab->getButtonUrlResolved() }}"
                                 data-icon="{{ $tab->icon ?? 'fas fa-circle' }}">
                                
                                <h5 class="mb-3 text-center">{{ $tab->translated_title }}</h5>
                                
                                {{-- Dynamic form based on tab type --}}
                                @switch($tab->tab_key)
                                    @case('flight')
                                        @include('public.sections.partials.flight-form')
                                        @break
                                    @case('umrah')
                                        @include('public.sections.partials.umrah-form')
                                        @break
                                    @case('visa')
                                        @include('public.sections.partials.visa-form')
                                        @break
                                    @case('cargo')
                                        @include('public.sections.partials.cargo-form')
                                        @break
                                    @case('appointment')
                                        @include('public.sections.partials.appointment-form')
                                        @break
                                    @case('investor')
                                        @include('public.sections.partials.investor-form')
                                        @break
                                    @default
                                        <p class="text-muted text-center">Coming soon...</p>
                                @endswitch
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching handler
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(event) {
            const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
            updateHeroContent(targetPane);
        });
    });
    
    function updateHeroContent(pane) {
        if (!pane) return;
        
        const title = pane.dataset.title;
        const subtitle = pane.dataset.subtitle;
        const features = JSON.parse(pane.dataset.features || '[]');
        const buttonText = pane.dataset.buttonText;
        const buttonUrl = pane.dataset.buttonUrl;
        const icon = pane.dataset.icon;
        
        let featuresHtml = '';
        features.forEach(feature => {
            featuresHtml += `
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span>${feature}</span>
                </div>
            `;
        });
        
        const heroDiv = document.getElementById('heroContent');
        if (heroDiv) {
            heroDiv.innerHTML = `
                <h1 class="display-4 fw-bold mb-3">${title}</h1>
                <p class="lead mb-4">${subtitle}</p>
                ${featuresHtml ? '<div class="hero-features mb-4">' + featuresHtml + '</div>' : ''}
                <a href="${buttonUrl}" class="btn btn-light btn-lg px-4 me-2">
                    <i class="${icon} me-2"></i>
                    ${buttonText}
                </a>
            `;
        }
    }
});
</script>
@endpush
