<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorApplicationResource\Pages;

use App\Filament\Resources\InvestorApplicationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewInvestorApplication extends ViewRecord
{
    protected static ?string $resource = InvestorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
