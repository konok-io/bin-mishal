<?php

declare(strict_types=1);

namespace App\Filament\Resources\HeroTabResource\Pages;

use App\Filament\Resources\HeroTabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeroTab extends EditRecord
{
    protected static ?string $resource = HeroTabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
