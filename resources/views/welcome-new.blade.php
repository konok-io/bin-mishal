{{-- ========================================================================
    Dynamic Welcome Page - All content from CMS
    Components: Header, Hero, Services, Statistics, Testimonials, Gallery, FAQ, CTA
============================================================================ --}}

<!DOCTYPE html>
<html 
    lang="{{ app()->getLocale() }}" 
    dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"
    data-locale="{{ app()->getLocale() }}"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Dynamic SEO --}}
    @php
        $siteName = \App\Models\CMS\Setting::getValue('site_name', __('app.app_name'));
        $metaTitle = \App\Models\CMS\Setting::getValue('meta_title', __('home.seo_title'));
        $metaDescription = \App\Models\CMS\Setting::getValue('meta_description', __('home.seo_description'));
    @endphp
    
    <title>{{ $metaTitle }} | {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    
    <!-- Favicon -->
    @if(\App\Models\CMS\Setting::getValue('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(\App\Models\CMS\Setting::getValue('favicon')) }}">
    @endif
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 RTL/LTR -->
    @if(is_rtl())
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Theme Colors -->
    <style>
        :root {
            --primary-color: {{ \App\Models\CMS\Setting::getValue('primary_color', '#006C35') }};
            --secondary-color: {{ \App\Models\CMS\Setting::getValue('secondary_color', '#C8A951') }};
            --accent-color: {{ \App\Models\CMS\Setting::getValue('accent_color', '#1B3A5C') }};
        }
        
        html[lang="bn"] body,
        html[lang="bn"] {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
        }
        
        html[lang="ar"] body,
        html[lang="ar"] {
            font-family: 'Noto Sans Arabic', 'Inter', sans-serif;
        }
        
        html[lang="en"] body,
        html[lang="en"] {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header Component -->
    @include('components.frontend.header')
    
    <!-- Hero Section -->
    @include('components.frontend.hero-section')
    
    <!-- Services Section -->
    @include('components.frontend.services-section')
    
    <!-- Statistics Section -->
    @include('components.frontend.statistics-section')
    
    <!-- Testimonials Section -->
    @include('components.frontend.testimonials-section')
    
    <!-- Gallery Section -->
    @include('components.frontend.gallery-section')
    
    <!-- FAQ Section -->
    @include('components.frontend.faq-section')
    
    <!-- CTA Section -->
    @include('components.frontend.cta-section')
    
    <!-- Footer Component -->
    @include('components.frontend.footer')
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
    
    @stack('scripts')
</body>
</html>
