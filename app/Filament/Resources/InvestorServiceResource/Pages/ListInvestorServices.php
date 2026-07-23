<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorServiceResource\Pages;

use App\Filament\Resources\InvestorServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestorServices extends ListRecords
{
    protected static ?string $resource = InvestorServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
