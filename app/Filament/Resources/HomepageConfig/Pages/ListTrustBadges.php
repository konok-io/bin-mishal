<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig\Pages;

use App\Filament\Resources\HomepageConfig\TrustBadgeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrustBadges extends ListRecords
{
    protected static ?string $resource = TrustBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
