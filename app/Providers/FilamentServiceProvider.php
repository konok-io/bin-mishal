<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\FilamentManager;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Filament v3 auto-discovers resources from app/Filament/Resources
    }
}
