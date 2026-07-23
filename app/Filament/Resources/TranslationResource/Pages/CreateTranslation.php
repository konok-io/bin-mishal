<?php

declare(strict_types=1);

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use App\Models\Translation;
use Filament\Resources\Pages\CreateRecord;

class CreateTranslation extends CreateRecord
{
    protected static string $resource = TranslationResource::class;

    protected function afterCreate(): void
    {
        // Clear translation cache
        Translation::clearCache();
    }
}
