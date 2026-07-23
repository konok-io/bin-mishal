<?php

declare(strict_types=1);

namespace App\Filament\Resources\HeroTabResource\Pages;

use App\Filament\Resources\HeroTabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeroTabs extends ListRecords
{
    protected static ?string $resource = HeroTabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
