<?php

declare(strict_types=1);

namespace App\Filament\Resources\CargoPricingResource\Pages;

use App\Filament\Resources\CargoPricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCargoPricing extends EditRecord
{
    protected static ?string $resource = CargoPricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
