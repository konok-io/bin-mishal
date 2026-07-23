{{-- ============================================================================
    PUBLIC LAYOUT (PERMANENT — will be reused by CMS)
    DO NOT use Tailwind CDN here. All styles come through Vite from
    resources/css/app.css which uses Tailwind v4 with @theme brand tokens.
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
        <title>{{ $title ?? __('app.app_name') }}</title>

        {{-- Custom Fonts --}}
        <style>
            @font-face {
                font-family: 'Bangla';
                src: url('/fonts/bangla.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: 'English';
                src: url('/fonts/English.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: 'Arabic';
                src: url('/fonts/Arabic.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
        </style>

        {{-- Per-locale Google Fonts (fallback) --}}
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

        {{-- Vite assets (builds app.css which includes Tailwind + brand @theme) --}}
        @vite

        {{-- Language-specific font styles --}}
        <style>
            html[lang="bn"] body,
            html[lang="bn"] * {
                font-family: 'Bangla', 'Hind Siliguri', sans-serif !important;
            }
            html[lang="ar"] body,
            html[lang="ar"] * {
                font-family: 'Arabic', 'IBM Plex Sans Arabic', sans-serif !important;
            }
            html[lang="en"] body,
            html[lang="en"] * {
                font-family: 'English', 'Inter', sans-serif !important;
            }
        </style>

        {{-- Extra head content from child pages --}}
        @isset($head)
            {!! $head !!}
        @endif
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">

        {{-- Header slot --}}
        @hasSection('header')
            @yield('header')
        @endif

        {{-- Main content slot --}}
        <main class="min-h-screen">
            @yield('content')
        </main>

        {{-- Footer slot --}}
        @hasSection('footer')
            @yield('footer')
        @endif

        {{-- Page-specific scripts from child pages --}}
        @isset($scripts)
            {!! $scripts !!}
        @endif

    </body>
</html>
