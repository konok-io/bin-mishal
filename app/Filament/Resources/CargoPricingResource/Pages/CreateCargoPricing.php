<?php

declare(strict_types=1);

namespace App\Filament\Resources\CargoPricingResource\Pages;

use App\Filament\Resources\CargoPricingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCargoPricing extends CreateRecord
{
    protected static ?string $resource = CargoPricingResource::class;
}
