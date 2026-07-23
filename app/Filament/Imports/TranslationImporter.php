<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Translation;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TranslationImporter extends Importer
{
    protected static ?string $model = Translation::class;

    public static function getColumns(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('group')->required(),
            \Filament\Forms\Components\TextInput::make('key')->required(),
            \Filament\Forms\Components\TextInput::make('value_bn'),
            \Filament\Forms\Components\TextInput::make('value_en'),
            \Filament\Forms\Components\TextInput::make('value_ar'),
        ];
    }

    public function resolveRecord(): ?Translation
    {
        return Translation::firstOrCreate(
            [
                'group' => $this->data['group'],
                'key' => $this->data['key'],
            ]
        );
    }

    public function handleRecordCreation(Translation $record, array $data): Translation
    {
        $record->update([
            'value_bn' => $data['value_bn'] ?? null,
            'value_en' => $data['value_en'] ?? null,
            'value_ar' => $data['value_ar'] ?? null,
            'source' => 'imported',
        ]);

        $record->updateStatus();
        $record->save();

        // Clear cache
        Translation::clearCache();

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your translation import has completed and ' . number_format($import->successful_rows) . ' rows imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' rows failed to import.';
        }

        return $body;
    }
}
