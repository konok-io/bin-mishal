<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Resources\TranslationResource;
use Filament\FilamentManager;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
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
        // Auto-discover resources from app/Filament/Resources
    }
}
