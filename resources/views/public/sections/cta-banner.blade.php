{{-- CTA Banner Section --}}
@props(['section', 'content', 'settings', 'items'])

@php
    $bgStyle = match($settings['background_type'] ?? 'gradient')
    {
        'gradient' => "background: linear-gradient({$settings['gradient_from'] ?? '#059669'}, {$settings['gradient_to'] ?? '#047857'});",
        'image' => "background-image: url({$settings['background_image'] ?? ''}); background-size: cover; background-position: center;",
        'color' => "background-color: {$settings['background_color'] ?? '#059669'};",
        default => "background-color: {$settings['background_color'] ?? '#059669'};",
    };
@endphp

<section 
    class="cta-banner {{ $settings['custom_css_class'] ?? '' }}"
    id="{{ $settings['custom_id'] ?? '' }}"
    style="{{ $bgStyle }}"
>
    <div class="container {{ $settings['container_width'] ?? 'contained' }}">
        <div class="cta-banner__content text-{{ $settings['text_color'] ?? 'light' }}">
            @if(!empty($content['heading']))
                <h2 class="cta-banner__heading">{{ $content['heading'] }}</h2>
            @endif
            
            @if(!empty($content['description']))
                <p class="cta-banner__description">{{ $content['description'] }}</p>
            @endif
            
            <div class="cta-banner__actions">
                @if(!empty($content['button_text']))
                    <a href="{{ $content['button_url'] ?? '#' }}" 
                       class="btn btn-light btn-lg">
                        {{ $content['button_text'] }}
                    </a>
                @endif
                
                @if(!empty($content['secondary_button_text']))
                    <a href="{{ $content['secondary_button_url'] ?? '#' }}" 
                       class="btn btn-outline-light btn-lg">
                        {{ $content['secondary_button_text'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.cta-banner {
    padding: 4rem 0;
}

.cta-banner__content {
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}

.cta-banner__heading {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-banner--light .cta-banner__heading,
.cta-banner--light .cta-banner__description {
    color: #fff;
}

.cta-banner--dark .cta-banner__heading,
.cta-banner--dark .cta-banner__description {
    color: #1f2937;
}

.cta-banner__description {
    font-size: 1.125rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-banner__actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
</style>
@endpush
