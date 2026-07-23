<?php

declare(strict_types=1);

namespace App\Filament\Resources\BookingConfigurationResource\Pages;

use App\Filament\Resources\BookingConfigurationResource;
use Filament\Resources\Pages\EditRecord;

class EditBookingConfiguration extends EditRecord
{
    protected static ?string $resource = BookingConfigurationResource::class;
}
