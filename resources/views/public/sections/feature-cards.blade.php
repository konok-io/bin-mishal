{{-- Feature Cards Section --}}
@props(['section', 'content', 'settings', 'items'])

@php
    $columns = $settings['columns'] ?? 3;
@endphp

<section 
    class="feature-cards {{ $settings['custom_css_class'] ?? '' }}"
    id="{{ $settings['custom_id'] ?? '' }}"
    style="padding-top: {{ $settings['padding_top'] ?? 'default' }}; padding-bottom: {{ $settings['padding_bottom'] ?? 'default' }};"
>
    <div class="container {{ $settings['container_width'] ?? 'contained' }}">
        @if(!empty($content['heading']) || !empty($content['subheading']))
            <div class="section-header text-{{ $settings['heading_alignment'] ?? 'center' }}">
                @if(!empty($content['heading']))
                    <h2 class="section-header__heading">{{ $content['heading'] }}</h2>
                @endif
                @if(!empty($content['subheading']))
                    <p class="section-header__subheading">{{ $content['subheading'] }}</p>
                @endif
            </div>
        @endif
        
        <div class="feature-cards__grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $columns }} gap-6">
            @forelse($items as $item)
                <div class="feature-card feature-card--{{ $settings['card_style'] ?? 'default' }}">
                    @if(!empty($item->icon))
                        <div class="feature-card__icon">
                            <x-dynamic-component :component="'heroicons-' . ($settings['icon_position'] ?? 'top') . '.' . $item->icon" class="w-12 h-12" />
                        </div>
                    @endif
                    
                    <h3 class="feature-card__title">{{ $item->translated_title }}</h3>
                    
                    @if(!empty($item->translated_description))
                        <p class="feature-card__description">{{ $item->translated_description }}</p>
                    @endif
                    
                    @if(!empty($item->link_text) && !empty($item->link_url))
                        <a href="{{ $item->resolved_url }}" class="feature-card__link">
                            {{ $item->translated_link_text ?? $item->link_text }}
                            <x-heroicon-s-arrow-right class="w-4 h-4" />
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-muted col-span-full text-center">
                    {{ __('cms.no_features_added') }}
                </p>
            @endforelse
        </div>
    </div>
</section>

@push('styles')
<style>
.feature-cards {
    background: {{ $settings['background_color'] ?? 'transparent' }};
}

.section-header {
    margin-bottom: 3rem;
}

.section-header__heading {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.section-header__subheading {
    font-size: 1.125rem;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
}

.section-header--left .section-header__subheading {
    margin: 0;
}

.feature-card {
    padding: 2rem;
    border-radius: 0.75rem;
    background: #fff;
    border: 1px solid #e5e7eb;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.feature-card--elevated {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.feature-card--outlined {
    background: transparent;
    border: 2px solid #e5e7eb;
}

.feature-card--minimal {
    background: transparent;
    border: none;
    box-shadow: none;
}

.feature-card:hover {
    transform: translateY(-4px);
}

.feature-card__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color, #059669), var(--primary-dark, #047857));
    color: #fff;
    margin-bottom: 1.5rem;
}

.feature-card__title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.feature-card__description {
    color: #6b7280;
    margin-bottom: 1rem;
}

.feature-card__link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color, #059669);
    font-weight: 500;
    text-decoration: none;
}

.feature-card__link:hover {
    text-decoration: underline;
}
</style>
@endpush
