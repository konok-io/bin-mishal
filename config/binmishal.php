<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Application Info
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'Bin Mishal Travel'),
    'name_bn' => env('APP_NAME_BN', 'বিন মিশাল ট্রাভেল'),
    'name_ar' => env('APP_NAME_AR', 'بن ميثال للسفر'),

    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    */
    'company' => [
        'phone' => env('COMPANY_PHONE', '+966 XX XXX XXXX'),
        'whatsapp' => env('COMPANY_WHATSAPP', '+966 XX XXX XXXX'),
        'email' => env('COMPANY_EMAIL', 'info@binmishal.com'),
        'address' => env('COMPANY_ADDRESS', 'Saudi Arabia'),
        'city' => env('COMPANY_CITY', 'Al Hufuf'),
        'country' => 'Saudi Arabia',
        'cr_number' => env('CR_NUMBER'),
        'vat_number' => env('VAT_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Links
    |--------------------------------------------------------------------------
    */
    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK'),
        'instagram' => env('SOCIAL_INSTAGRAM'),
        'twitter' => env('SOCIAL_TWITTER'),
        'youtube' => env('SOCIAL_YOUTUBE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Settings
    |--------------------------------------------------------------------------
    */
    'business' => [
        'tax_rate' => env('TAX_RATE', 15),
        'currency' => env('CURRENCY', 'SAR'),
        'currency_symbol' => env('CURRENCY_SYMBOL', 'ر.س'),
        'date_format' => env('DATE_FORMAT', 'd M Y'),
        'time_format' => env('TIME_FORMAT', 'H:i'),
        'timezone' => env('TIMEZONE', 'Asia/Riyadh'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Settings
    |--------------------------------------------------------------------------
    */
    'booking' => [
        'prefix' => 'BK',
        'auto_confirm' => env('AUTO_CONFIRM_BOOKING', false),
        'require_payment' => env('REQUIRE_PAYMENT_FOR_ISSUING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Settings
    |--------------------------------------------------------------------------
    */
    'invoice' => [
        'prefix' => 'INV',
        'due_days' => env('INVOICE_DUE_DAYS', 30),
        'auto_reminder' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Visa Settings
    |--------------------------------------------------------------------------
    */
    'visa' => [
        'government_fee_currency' => 'SAR',
        'processing_days_default' => 14,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_size' => env('UPLOAD_MAX_SIZE', 10240), // KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Integration
    |--------------------------------------------------------------------------
    */
    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', false),
        'api_url' => env('WHATSAPP_API_URL'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'phone_number' => env('WHATSAPP_PHONE_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'moyasar' => [
            'enabled' => env('MOYASAR_ENABLED', false),
            'publishable_key' => env('MOYASAR_PUBLISHABLE_KEY'),
            'secret_key' => env('MOYASAR_SECRET_KEY'),
            'environment' => env('MOYASAR_ENVIRONMENT', 'test'),
        ],
        'hyperpay' => [
            'enabled' => env('HYPERPAY_ENABLED', false),
            'entity_id' => env('HYPERPAY_ENTITY_ID'),
            'access_token' => env('HYPERPAY_ACCESS_TOKEN'),
        ],
    ],
];
