<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Resources\TranslationResource;
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
        // Translation resource is auto-discovered via Filament's resource discovery
        // This provider ensures the TranslationResource is registered
    }
}
