<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Media Library
    |--------------------------------------------------------------------------
    */
    'disk' => 'public',
    
    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    */
    'collections' => [
        'avatar' => [
            'disk' => 'public',
            'directory' => 'avatars',
            'media_model' => \Spatie\MediaLibrary\MediaCollections\Models\Media::class,
            'conversions' => ['thumb', 'medium'],
        ],
        'passport' => [
            'disk' => 'encrypted',
            'directory' => 'documents/passports',
        ],
        'iqama' => [
            'disk' => 'encrypted',
            'directory' => 'documents/iqama',
        ],
        'tickets' => [
            'disk' => 'public',
            'directory' => 'tickets',
        ],
        'invoices' => [
            'disk' => 'public',
            'directory' => 'invoices',
        ],
        'gallery' => [
            'disk' => 'public',
            'directory' => 'gallery',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Conversions
    |--------------------------------------------------------------------------
    */
    'image_conversions' => [
        'thumb' => [
            'width' => 150,
            'height' => 150,
            'fit' => 'crop',
        ],
        'medium' => [
            'width' => 300,
            'height' => 300,
            'fit' => 'crop',
        ],
        'large' => [
            'width' => 800,
            'height' => null,
        ],
    ],
];
