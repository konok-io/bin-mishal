<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig\Pages;

use App\Filament\Resources\HomepageConfig\FlightRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFlightRoutes extends ListRecords
{
    protected static ?string $resource = FlightRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
