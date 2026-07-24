{{-- CMS Layout - Used for dynamic pages --}}
<!DOCTYPE html>
<html
    lang="{{ app()->getLocale() }}"
    dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"
    data-locale="{{ app()->getLocale() }}"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- SEO Meta Tags --}}
        @if(!empty($seo['title']))
            <title>{{ $seo['title'] }} | {{ __('app.app_name') }}</title>
        @else
            <title>{{ __('app.app_name') }}</title>
        @endif
        
        @if(!empty($seo['description']))
            <meta name="description" content="{{ $seo['description'] }}">
        @endif
        
        @if(!empty($seo['keywords']))
            <meta name="keywords" content="{{ $seo['keywords'] }}">
        @endif
        
        @if(!empty($seo['canonical']))
            <link rel="canonical" href="{{ url($seo['canonical']) }}">
        @endif
        
        @if(!empty($seo['noindex']))
            <meta name="robots" content="noindex, nofollow">
        @endif
        
        {{-- Open Graph --}}
        @if(!empty($seo['og_image']))
            <meta property="og:image" content="{{ url($seo['og_image']) }}">
        @endif
        
        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="style"
              href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
              crossorigin>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
              media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet"
                  href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
        </noscript>

        {{-- Vite --}}
        @vite

        {{-- Theme CSS Variables --}}
        @stack('theme-variables')
        
        {{-- Extra head content --}}
        @isset($head)
            {!! $head !!}
        @endif
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        
        {{-- Header --}}
        @if($showHeader)
            @hasSection('header')
                @yield('header')
            @else
                <x-public.header 
                    :menu="$headerMenu ?? []" 
                    :mobileMenu="$mobileMenu ?? []" 
                />
            @endif
        @endif

        {{-- Main Content --}}
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        @if($showFooter)
            @hasSection('footer')
                @yield('footer')
            @else
                <x-public.footer 
                    :col1="$footerCol1 ?? []"
                    :col2="$footerCol2 ?? []"
                    :col3="$footerCol3 ?? []"
                    :bottom="$footerBottom ?? []"
                />
            @endif
        @endif

        {{-- Floating Widgets (WhatsApp + AI Chat) - Only on non-admin pages --}}
        @if(!request()->is('admin/*') && !request()->is('api/*'))
            @livewire('public.floating-widgets')
        @endif

        {{-- Page-specific scripts --}}
        @isset($scripts)
            {!! $scripts !!}
        @endif
        
        @stack('scripts')
        
        {{-- Schema.org --}}
        @if(!empty($seo['schema_type']))
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "{{ $seo['schema_type'] }}",
                "name": "{{ $seo['title'] ?? '' }}",
                "description": "{{ $seo['description'] ?? '' }}"
            }
            </script>
        @endif
    </body>
</html>
