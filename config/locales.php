<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled Locales
    |--------------------------------------------------------------------------
    |
    | List of enabled locales for the application. The first locale (bn)
    | is the default. Each locale has configuration for rendering.
    |
    */
    'enabled' => [
        'bn' => [
            'code' => 'bn',
            'name' => 'Bengali',
            'native_name' => 'বাংলা',
            'direction' => 'ltr',
            'flag' => '🇧🇩',
            'font_family' => "'Hind Siliguri', 'Noto Sans Bengali', sans-serif",
            'date_format' => 'd M Y',
            'number_system' => 'bengali',
            'enabled' => true,
        ],
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'direction' => 'ltr',
            'flag' => '🇬🇧',
            'font_family' => "'Inter', 'Segoe UI', system-ui, sans-serif",
            'date_format' => 'M d, Y',
            'number_system' => 'latin',
            'enabled' => true,
        ],
        'ar' => [
            'code' => 'ar',
            'name' => 'Arabic',
            'native_name' => 'العربية',
            'direction' => 'rtl',
            'flag' => '🇸🇦',
            'font_family' => "'Noto Sans Arabic', 'Segoe UI', sans-serif",
            'date_format' => 'd M Y',
            'number_system' => 'arabic',
            'enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale that will be used when a locale is not
    | specified in the URL, session, or user preference.
    |
    */
    'default' => env('APP_LOCALE', 'bn'),

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used when the requested locale is not available.
    |
    */
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Supported Number Systems
    |--------------------------------------------------------------------------
    |
    | Number systems for different locales.
    |
    */
    'number_systems' => [
        'bengali' => ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
        'arabic' => ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
        'latin' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
    ],
];
