<?php

return [
    'default' => 'admin',

    'panels' => [
        'admin' => [
            'path' => 'admin',
            'auth' => [
                'guard' => 'admin',
                'pages' => [
                    'login' => \Filament\Pages\Auth\Login::class,
                ],
            ],
        ],
    ],
];
