<?php

declare(strict_types=1);

namespace App\Filament\Resources\AppearanceSettingResource\Pages;

use App\Filament\Resources\AppearanceSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAppearanceSetting extends CreateRecord
{
    protected static string $resource = AppearanceSettingResource::class;
}
