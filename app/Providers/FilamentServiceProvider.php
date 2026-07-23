<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Imports\TranslationImporter;
use App\Filament\Resources\TranslationResource;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin/filament')
            ->login()
            ->brandName('Bin Mishal CMS')
            ->colors([
                'primary' => [
                    50 => '236 72 153',
                    100 => '236 72 153',
                    200 => '236 72 153',
                    300 => '236 72 153',
                    400 => '236 72 153',
                    500 => '059669',
                    600 => '047857',
                    700 => '034d3d',
                    800 => '023b2a',
                    900 => '012818',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ])
            ->authMiddleware([
                \App\Http\Middleware\EnsureRole::class . ':admin,super_admin',
            ])
            ->imports([
                TranslationImporter::class,
            ])
            ->resources([
                TranslationResource::class,
            ])
            ->widgets([
                TranslationResource\Widgets\TranslationStatsWidget::class,
            ]);
    }
}
