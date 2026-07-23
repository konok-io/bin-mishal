<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig\Pages;

use App\Filament\Resources\HomepageConfig\StatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatistics extends ListRecords
{
    protected static ?string $resource = StatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
