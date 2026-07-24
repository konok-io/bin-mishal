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

        {{-- Custom Fonts with Unicode Range --}}
        <style>
            @font-face {
                font-family: 'BanglaFont';
                src: url('/fonts/bangla.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                unicode-range: U+0980-09FF, U+09E0-09EF, U+200C-200D, U+20B9;
            }
            @font-face {
                font-family: 'EnglishFont';
                src: url('/fonts/English.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                unicode-range: U+0000-007F, U+0080-00FF, U+0100-017F, U+1E00-1EFF, U+1F300-1F9FF;
            }
            @font-face {
                font-family: 'ArabicFont';
                src: url('/fonts/Arabic.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                unicode-range: U+0600-06FF, U+0750-077F, U+08A0-08FF, U+FB50-FDFF, U+FE70-FEFF;
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
        @vite('resources/js/app.js')

        {{-- Language-specific font styles --}}
        <style>
            html[lang="bn"] body {
                font-family: 'BanglaFont', 'Hind Siliguri', 'EnglishFont', 'ArabicFont', sans-serif;
            }
            html[lang="ar"] body {
                font-family: 'ArabicFont', 'IBM Plex Sans Arabic', 'EnglishFont', 'BanglaFont', sans-serif;
            }
            html[lang="en"] body {
                font-family: 'EnglishFont', 'Inter', 'BanglaFont', 'ArabicFont', sans-serif;
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
