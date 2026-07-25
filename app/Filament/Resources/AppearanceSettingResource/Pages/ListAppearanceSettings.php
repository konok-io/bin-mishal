<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppearanceSettingResource\Pages;

use App\Filament\Resources\AppearanceSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppearanceSettings extends ListRecords
{
    protected static string $resource = AppearanceSettingResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
