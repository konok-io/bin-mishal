<?php

declare(strict_types=1);

namespace App\Filament\Resources\BookingConfigurationResource\Pages;

use App\Filament\Resources\BookingConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingConfigurations extends ListRecords
{
    protected static ?string $resource = BookingConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('initialize')
                ->label('Initialize Defaults')
                ->icon('heroicon-o-plus')
                ->action(function () {
                    \App\Models\BookingConfiguration::initializeDefaults();
                    $this->notify('success', 'Default configurations created.');
                }),
        ];
    }
}
