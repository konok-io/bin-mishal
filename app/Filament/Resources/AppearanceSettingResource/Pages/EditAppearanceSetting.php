<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppearanceSettingResource\Pages;

use App\Filament\Resources\AppearanceSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppearanceSetting extends EditRecord
{
    protected static string $resource = AppearanceSettingResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
