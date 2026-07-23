<?php

declare(strict_types=1);

namespace App\Filament\Resources\HeroTabResource\Pages;

use App\Filament\Resources\HeroTabResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHeroTab extends CreateRecord
{
    protected static ?string $resource = HeroTabResource::class;
}
