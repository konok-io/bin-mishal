<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorApplicationResource\Pages;

use App\Filament\Resources\InvestorApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListInvestorApplications extends ListRecords
{
    protected static ?string $resource = InvestorApplicationResource::class;
}
