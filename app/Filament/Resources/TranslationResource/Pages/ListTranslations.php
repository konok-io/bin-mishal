<?php

declare(strict_types=1);

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslations extends ListRecords
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sync Keys')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    \Illuminate\Support\Facades\Artisan::call('translations:sync');
                    $this->notify('success', 'Translation keys synced successfully.');
                }),
            Actions\CreateAction::make(),
        ];
    }
}
