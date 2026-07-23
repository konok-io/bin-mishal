<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig\Pages;

use App\Filament\Resources\HomepageConfig\FeatureCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeatureCards extends ListRecords
{
    protected static ?string $resource = FeatureCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
