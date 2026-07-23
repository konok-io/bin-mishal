<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig\Pages;

use App\Filament\Resources\HomepageConfig\QuickServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuickServices extends ListRecords
{
    protected static ?string $resource = QuickServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
