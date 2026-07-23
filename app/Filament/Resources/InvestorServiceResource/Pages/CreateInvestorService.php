<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvestorServiceResource\Pages;

use App\Filament\Resources\InvestorServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvestorService extends CreateRecord
{
    protected static ?string $resource = InvestorServiceResource::class;
}
