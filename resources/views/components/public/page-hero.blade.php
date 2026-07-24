{{-- Page Hero Component --}}

@php
$title = $page->title ?? '';
$subtitle = $page->subtitle ?? '';
$backgroundImage = $page->hero_image ?? null;
$heroStyle = $page->hero_style ?? 'default';
@endphp

<section class="page-hero {{ $heroStyle === 'minimal' ? 'page-hero--minimal' : '' }}"
    @if($backgroundImage)
        style="background-image: url('{{ Storage::url($backgroundImage) }}');"
    @endif>
    <div class="page-hero__overlay"></div>
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">{{ $title }}</h1>
            @if($subtitle)
                <p class="page-hero__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
.page-hero {
    position: relative;
    padding: 4rem 0;
    background-size: cover;
    background-position: center;
    background-color: #1f2937;
    min-height: 300px;
    display: flex;
    align-items: center;
}

.page-hero--minimal {
    min-height: 150px;
    padding: 2rem 0;
}

.page-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
}

.page-hero__content {
    position: relative;
    z-index: 1;
    text-align: center;
    color: white;
    max-width: 800px;
    margin: 0 auto;
}

.page-hero__title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.page-hero__subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* RTL Support */
[dir="rtl"] .page-hero__content {
    text-align: center;
}

@media (max-width: 768px) {
    .page-hero {
        min-height: 200px;
        padding: 3rem 0;
    }
    
    .page-hero__title {
        font-size: 1.75rem;
    }
    
    .page-hero__subtitle {
        font-size: 1rem;
    }
}
</style>
@endpush
