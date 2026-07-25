<?php

use App\Providers\AdminPanelProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FilamentServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\TranslationServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    FilamentServiceProvider::class,
    FortifyServiceProvider::class,
    TranslationServiceProvider::class,
];
