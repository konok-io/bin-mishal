<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Translation;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TranslationExporter extends Exporter
{
    protected static ?string $model = Translation::class;

    public static function getColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('group'),
            \Filament\Tables\Columns\TextColumn::make('key'),
            \Filament\Tables\Columns\TextColumn::make('value_bn'),
            \Filament\Tables\Columns\TextColumn::make('value_en'),
            \Filament\Tables\Columns\TextColumn::make('value_ar'),
            \Filament\Tables\Columns\TextColumn::make('status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your translation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
