{{-- Hero Simple Section --}}
@props(['section', 'content', 'settings', 'items'])

@php
    $bgStyle = match($settings['background_type'] ?? 'gradient')
    {
        'gradient' => "background: linear-gradient({$settings['gradient_from'] ?? '#059669'}, {$settings['gradient_to'] ?? '#047857'});",
        'image' => "background-image: url({$settings['background_image'] ?? ''}); background-size: cover; background-position: center;",
        'color' => "background-color: {$settings['background_color'] ?? '#059669'};",
        default => '',
    };
    
    $alignment = $settings['text_alignment'] ?? 'center';
    $textColor = $settings['text_color'] ?? 'light';
@endphp

<section 
    class="hero-simple hero-simple--{{ $alignment }} hero-simple--{{ $textColor }} {{ $settings['custom_css_class'] ?? '' }}"
    id="{{ $settings['custom_id'] ?? '' }}"
    style="{{ $bgStyle }}"
>
    @if($settings['overlay_opacity'] ?? false)
        <div class="hero-simple__overlay" style="opacity: {{ $settings['overlay_opacity'] / 100 }}"></div>
    @endif
    
    <div class="container {{ $settings['container_width'] ?? 'contained' }}">
        <div class="hero-simple__content">
            @if(!empty($content['heading']))
                <h1 class="hero-simple__heading animate-fade-in">
                    {{ $content['heading'] }}
                </h1>
            @endif
            
            @if(!empty($content['subheading']))
                <p class="hero-simple__subheading animate-fade-in" style="animation-delay: 100ms">
                    {{ $content['subheading'] }}
                </p>
            @endif
            
            @if(!empty($content['description']))
                <div class="hero-simple__description animate-fade-in" style="animation-delay: 200ms">
                    {!! $content['description'] !!}
                </div>
            @endif
            
            <div class="hero-simple__actions animate-fade-in" style="animation-delay: 300ms">
                @if(!empty($content['button_text']))
                    <a href="{{ $content['button_url'] ?? '#' }}" 
                       class="btn btn-primary btn-lg">
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
.hero-simple {
    position: relative;
    padding: var(--section-padding-y, 100px) 0;
    min-height: {{ $settings['min_height'] ?? 500 }}px;
    display: flex;
    align-items: center;
}

.hero-simple--center .hero-simple__content { text-align: center; }
.hero-simple--left .hero-simple__content { text-align: left; }
.hero-simple--right .hero-simple__content { text-align: right; }

.hero-simple__content {
    max-width: 800px;
}

.hero-simple--left .hero-simple__content { margin-right: auto; }
.hero-simple--right .hero-simple__content { margin-left: auto; }

.hero-simple__heading {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-simple--light .hero-simple__heading,
.hero-simple--light .hero-simple__subheading,
.hero-simple--light .hero-simple__description { color: #fff; }

.hero-simple--dark .hero-simple__heading,
.hero-simple--dark .hero-simple__subheading,
.hero-simple--dark .hero-simple__description { color: #1f2937; }

.hero-simple__subheading {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.hero-simple__description {
    font-size: 1.125rem;
    margin-bottom: 2rem;
    opacity: 0.85;
}

.hero-simple__actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.hero-simple--center .hero-simple__actions { justify-content: center; }

.hero-simple__overlay {
    position: absolute;
    inset: 0;
    background: #000;
    pointer-events: none;
}
</style>
@endpush
