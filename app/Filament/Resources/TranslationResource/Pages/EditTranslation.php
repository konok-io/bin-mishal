<?php

declare(strict_types=1);

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use App\Models\Translation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslation extends EditRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Update status based on values
        $this->record->updateStatus();
        $this->record->updated_by = auth()->id();
        $this->record->save();

        // Clear translation cache
        Translation::clearCache();
    }
}
