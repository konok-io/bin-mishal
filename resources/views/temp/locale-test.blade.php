{{-- ============================================================================
    TEMPORARY LOCALE TEST PAGE (A11)
    This page is throwaway — it will be replaced by the CMS home page.
    Used to verify Step A (locale infrastructure) is working correctly.
============================================================================ --}}

{{-- Pass the test data from the view composer / controller --}}
@php
    $testNumber = 1234567.89;
    $testDate = \Carbon\Carbon::now();
    
    // Detect which locale resolution source won
    $resolutionSource = 'default';
    if (request()->route('locale')) {
        $resolutionSource = 'route ({locale} segment)';
    } elseif (Session::get('locale')) {
        $resolutionSource = 'session';
    } elseif (request()->cookie('locale')) {
        $resolutionSource = 'cookie';
    } elseif (auth()->check() && auth()->user()->preferred_language) {
        $resolutionSource = 'user preference';
    } elseif (request()->header('Accept-Language')) {
        $resolutionSource = 'Accept-Language header';
    }

    // Route helper test - try 3 known route names
    $routeTests = [];
    foreach (['home', 'login', 'root'] as $name) {
        $routeTests[$name] = route_exists($name) ? route($name, ['locale' => current_locale()]) : "route '{$name}' does not exist";
    }

    // Check for missing translation keys by trying known keys
    $testKeys = [
        'common.app_name', 'common.loading', 'common.no_data',
        'navigation.home', 'navigation.login', 'navigation.services',
        'buttons.save', 'buttons.cancel', 'buttons.submit',
        'forms.email', 'forms.password', 'forms.phone',
        'messages.success', 'messages.error', 'messages.warning',
    ];
@endphp

@extends('layouts.public')

@section('title', 'Locale Test — ' . current_locale())

@section('header')
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">[ TEMPORARY TEST PAGE — A11 ]</span>
            <span class="font-bold text-emerald-700">{{ __('app.app_name') }}</span>
        </div>
        <div class="flex items-center gap-4">
            <x-language-switcher />
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    {{-- ============================================================ --}}
    {{-- SECTION 1: ACTIVE LOCALE BLOCK                                   --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-emerald-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>1.</span> ACTIVE LOCALE
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded p-3">
                <div class="text-xs text-gray-500 uppercase">Locale Code</div>
                <div class="text-xl font-mono font-bold">{{ app()->getLocale() }}</div>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <div class="text-xs text-gray-500 uppercase">Native Name</div>
                <div class="text-xl font-bold">{{ locale_config()['native_name'] ?? 'N/A' }}</div>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <div class="text-xs text-gray-500 uppercase">Direction</div>
                <div class="text-xl font-bold">{{ locale_config()['direction'] ?? 'N/A' }}</div>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <div class="text-xs text-gray-500 uppercase">Resolution Source</div>
                <div class="text-sm font-mono text-blue-700">{{ $resolutionSource }}</div>
            </div>
        </div>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
            <div class="text-xs text-yellow-700">
                <strong>RENDERED:</strong>
                <code>&lt;html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"&gt;</code>
                → <code>dir="{{ is_rtl() ? 'rtl' : 'ltr' }}"</code>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 2: LANGUAGE SWITCHER                                     --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>2.</span> LANGUAGE SWITCHER
        </h2>
        <div class="mb-4">
            <x-language-switcher />
        </div>
        <div class="p-3 bg-blue-50 border border-blue-200 rounded">
            <div class="text-xs text-blue-700 font-mono break-all">
                <strong>CURRENT URL:</strong> {{ url()->current() }}
            </div>
            <div class="text-xs text-blue-700 font-mono break-all mt-1">
                <strong>FULL URL:</strong> {{ url()->full() }}
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 3: TRANSLATION CHECK                                     --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>3.</span> TRANSLATION CHECK — 15 KEYS
        </h2>
        <p class="text-xs text-gray-500 mb-3">Raw key shown = missing translation</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach($testKeys as $key)
                @php
                    $translated = __($key);
                    $isMissing = Str::startsWith($translated, $key) && !Str::contains($translated, '.');
                @endphp
                <div class="flex items-start gap-2 p-2 rounded {{ $isMissing ? 'bg-red-50 border border-red-300' : 'bg-gray-50' }}">
                    <span class="text-xs font-mono text-gray-400 min-w-0 truncate">{{ $key }}</span>
                    <span class="font-medium text-sm {{ $isMissing ? 'text-red-600' : 'text-gray-800' }}">
                        → {{ $translated }}
                    </span>
                    @if($isMissing)
                        <span class="text-xs text-red-600 font-bold">⚠ MISSING</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 4: VALIDATION MESSAGE CHECK                              --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>4.</span> VALIDATION MESSAGE CHECK
        </h2>
        <p class="text-xs text-gray-500 mb-3">
            Submit with empty fields to see validation errors in {{ current_locale() }}.
        </p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-300 rounded text-sm">
                <strong>{{ __('validation.errors_found', ['count' => $errors->count()]) ?? 'Errors (' . $errors->count() . '):' }}</strong>
                <ul class="mt-1 list-disc list-inside text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('locale.test', ['locale' => current_locale()]) }}" class="space-y-4 max-w-md">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('forms.name') }} *</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    value="{{ old('name') }}">
                @error('name')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('forms.email') }} *</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    value="{{ old('email') }}">
                @error('email')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700">
                    {{ __('buttons.submit') }}
                </button>
                <button type="reset" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                    {{ __('buttons.reset') }}
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 5: NUMBER & DATE CHECK                                   --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>5.</span> NUMBER &amp; DATE CHECK
        </h2>
        <div class="space-y-3">
            <div>
                <div class="text-xs text-gray-500 mb-1">bn_number(1234567.89) — Bengali locale should show ১২৩৪৫৬৭</div>
                <div class="text-2xl font-mono bg-gray-50 rounded px-3 py-2 inline-block">
                    Raw: 1234567.89 → {{ bn_number(1234567.89) }}
                    @if(current_locale() === 'bn' && preg_match('/[০-৯]/', bn_number(1234567.89)))
                        <span class="text-green-600 text-sm ml-2">✅ Bengali numerals</span>
                    @elseif(current_locale() !== 'bn')
                        <span class="text-gray-400 text-sm ml-2">(bn locale only)</span>
                    @endif
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">localized_date() — 3 formats</div>
                <div class="space-y-1 text-sm font-mono bg-gray-50 rounded px-3 py-2">
                    <div>{{ localized_date($testDate, 'd M Y') }}</div>
                    <div>{{ localized_date($testDate, 'F j, Y') }}</div>
                    <div>{{ localized_date($testDate, 'j F Y') }}</div>
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">money() — currency formatting</div>
                <div class="flex flex-wrap gap-4 text-sm font-mono">
                    <div class="bg-gray-50 rounded px-3 py-2">SAR: {{ money(4500, 'SAR') }}</div>
                    <div class="bg-gray-50 rounded px-3 py-2">BDT: {{ money(150000, 'BDT') }}</div>
                    <div class="bg-gray-50 rounded px-3 py-2">USD: {{ money(1200, 'USD') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 6: FONT CHECK                                           --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>6.</span> FONT CHECK — All 3 Languages On Every Locale
        </h2>
        <p class="text-xs text-gray-500 mb-4">
            Computed font-family for this locale: <code>{{ locale_config()['font_family'] }}</code>
        </p>
        <div class="space-y-4">
            @php
                $fontSamples = [
                    'bn' => ['বিন মিশাল ট্রাভেল', 'হিন্দি সিলিগুরি ফন্টে বাংলা টেক্সট'],
                    'en' => ['Bin Mishal Travel', 'The quick brown fox jumps over the lazy dog.'],
                    'ar' => ['بن ميثال للسفر', 'نص عربي بلغة عربية لاختبار الخط'],
                ];
            @endphp
            @foreach($fontSamples as $lang => $samples)
                <div class="border rounded-lg p-4">
                    <div class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ $lang === 'bn' ? 'Bengali' : ($lang === 'ar' ? 'Arabic' : 'English') }}</div>
                    <div class="text-xl mb-1" style="font-family: {{ $lang === 'bn' ? "'Hind Siliguri', 'Noto Sans Bengali', sans-serif" : ($lang === 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Inter', sans-serif") }}">
                        {{ $samples[0] }}
                    </div>
                    <div class="text-base text-gray-700" style="font-family: {{ $lang === 'bn' ? "'Hind Siliguri', 'Noto Sans Bengali', sans-serif" : ($lang === 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Inter', sans-serif") }}">
                        {{ $samples[1] }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1 font-mono">
                        font-family: {{ $lang === 'bn' ? "'Hind Siliguri', 'Noto Sans Bengali', sans-serif" : ($lang === 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Inter', sans-serif") }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 7: RTL LAYOUT CHECK                                     --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>7.</span> RTL / LTR LAYOUT CHECK
        </h2>
        <p class="text-xs text-gray-500 mb-4">Current: <strong>{{ is_rtl() ? 'RTL' : 'LTR' }}</strong></p>

        {{-- 3 boxes with logical properties --}}
        <div class="mb-6">
            <div class="text-xs text-gray-500 mb-2">Inline layout (SHOULD FLIP IN ARABIC):</div>
            <div class="flex items-center gap-2 p-4 bg-gray-50 rounded mb-2">
                <div class="px-4 py-2 bg-blue-500 text-white rounded text-sm font-medium text-center w-20">START</div>
                <div class="flex-1 px-4 py-2 bg-green-500 text-white rounded text-sm font-medium text-center">MIDDLE</div>
                <div class="px-4 py-2 bg-red-500 text-white rounded text-sm font-medium text-center w-20">END</div>
            </div>
        </div>

        {{-- Icon + text pair --}}
        <div class="mb-6">
            <div class="text-xs text-gray-500 mb-2">Icon + text (icon should flip in RTL):</div>
            <div class="flex items-center gap-2 p-3 bg-gray-50 rounded w-fit" style="display: flex; {{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
                <span style="font-size: 20px;">📞</span>
                <span class="text-sm font-medium">{{ __('navigation.contact') }}</span>
            </div>
        </div>

        {{-- Text alignment --}}
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 mb-1">text-{{ is_rtl() ? 'right' : 'left' }} (ltr default):</div>
                <div class="text-sm bg-gray-50 p-2 rounded text-left">
                    {{ __('navigation.services') }} — {{ __('navigation.about') }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">text-center:</div>
                <div class="text-sm bg-gray-50 p-2 rounded text-center">
                    {{ __('buttons.book_now') }}
                </div>
            </div>
        </div>

        {{-- Form input with leading icon --}}
        <div class="mb-6">
            <div class="text-xs text-gray-500 mb-2">Input with leading icon (icon should be on left in LTR, right in RTL):</div>
            <div class="relative max-w-sm">
                <span class="absolute top-1/2 -translate-y-1/2 px-3 text-gray-400" style="{{ is_rtl() ? 'right: 0; left: auto;' : 'left: 0; right: auto;' }}">✉</span>
                <input type="email" placeholder="{{ __('forms.email') }}"
                    class="w-full border border-gray-300 rounded px-10 py-2 text-sm"
                    style="{{ is_rtl() ? 'text-align: right; padding-right: 40px; padding-left: 12px;' : 'text-align: left; padding-left: 40px; padding-right: 12px;' }}">
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div>
            <div class="text-xs text-gray-500 mb-2">Breadcrumb (separator should flip in RTL):</div>
            <nav class="flex items-center gap-2 text-sm text-gray-600" aria-label="Breadcrumb" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
                <a href="#" class="hover:underline">{{ __('navigation.home') }}</a>
                <span>›</span>
                <a href="#" class="hover:underline">{{ __('navigation.services') }}</a>
                <span>›</span>
                <span class="text-gray-900 font-medium">{{ __('navigation.umrah') }}</span>
            </nav>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 8: ROUTE HELPER CHECK                                   --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>8.</span> ROUTE HELPER CHECK
        </h2>

        <div class="space-y-4">
            <div>
                <div class="text-xs text-gray-500 mb-2">locale_route() for 3 routes:</div>
                @foreach($routeTests as $name => $url)
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded min-w-20">{{ $name }}</span>
                        <a href="{{ $url }}" class="text-sm text-blue-600 hover:underline break-all">{{ $url }}</a>
                    </div>
                @endforeach
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-2">switch_locale_url() for all 3 locales (current path: {{ request()->path() }}):</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(['bn', 'en', 'ar'] as $loc)
                        <a href="{{ switch_locale_url($loc) }}"
                            class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm rounded hover:bg-indigo-200">
                            {{ $loc }} → {{ switch_locale_url($loc) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 9: SYSTEM INFO FOOTER                                   --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
            <span>9.</span> SYSTEM INFO
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
            <div class="bg-gray-50 rounded p-2">
                <div class="text-xs text-gray-500">config('app.locale')</div>
                <div class="font-mono">{{ config('app.locale') }}</div>
            </div>
            <div class="bg-gray-50 rounded p-2">
                <div class="text-xs text-gray-500">config('app.fallback_locale')</div>
                <div class="font-mono">{{ config('app.fallback_locale') }}</div>
            </div>
            <div class="bg-gray-50 rounded p-2">
                <div class="text-xs text-gray-500">app()->getLocale()</div>
                <div class="font-mono font-bold">{{ app()->getLocale() }}</div>
            </div>
            <div class="bg-gray-50 rounded p-2">
                <div class="text-xs text-gray-500">Carbon::getLocale()</div>
                <div class="font-mono">{{ \Carbon\Carbon::getLocale() }}</div>
            </div>
            <div class="bg-gray-50 rounded p-2 col-span-2">
                <div class="text-xs text-gray-500">lang path</div>
                <div class="font-mono text-xs break-all">{{ base_path('lang') }}</div>
            </div>
            <div class="bg-gray-50 rounded p-2 col-span-3">
                <div class="text-xs text-gray-500 mb-1">enabled locales from config/locales.php</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(config('locales.enabled', []) as $code => $cfg)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-200 rounded text-xs">
                            {{ $cfg['flag'] }} {{ $code }} — {{ $cfg['direction'] }} — {{ $cfg['font_family'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
