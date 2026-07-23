{{-- Generic Section (Fallback) --}}
@props(['section', 'content', 'settings', 'items', 'dynamicData'])

<section 
    class="section section--{{ $section->section_type }} {{ $settings['custom_css_class'] ?? '' }}"
    id="{{ $settings['custom_id'] ?? '' }}"
    style="padding-top: {{ $settings['padding_top'] ?? 'default' }}; padding-bottom: {{ $settings['padding_bottom'] ?? 'default' }};"
>
    <div class="container {{ $settings['container_width'] ?? 'contained' }}">
        @if(!empty($content['heading']))
            <h2 class="section__heading text-{{ $settings['heading_alignment'] ?? 'center' }}">
                {{ $content['heading'] }}
            </h2>
        @endif
        
        @if(!empty($content['subheading']))
            <p class="section__subheading text-{{ $settings['heading_alignment'] ?? 'center' }}">
                {{ $content['subheading'] }}
            </p>
        @endif
        
        <div class="section__content">
            @forelse($items as $item)
                <div class="section__item">
                    @if(!empty($item->title))
                        <h3>{{ $item->translated_title }}</h3>
                    @endif
                    @if(!empty($item->description))
                        <p>{{ $item->translated_description }}</p>
                    @endif
                </div>
            @empty
                @if($dynamicData)
                    <p class="text-muted">Dynamic data loaded ({{ $dynamicData->count() }} items)</p>
                @else
                    <p class="text-muted">Section: {{ $section->section_type }}</p>
                @endif
            @endforelse
        </div>
    </div>
</section>
