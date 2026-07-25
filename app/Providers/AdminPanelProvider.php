<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Pages\CMS\AppearanceSettings;
use App\Filament\Pages\CMS\GlobalSettings;
use App\Filament\Pages\CMS\SeoSettings;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                GlobalSettings::class,
                AppearanceSettings::class,
                SeoSettings::class,
            ]);
    }
}
