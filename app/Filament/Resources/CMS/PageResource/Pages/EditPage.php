<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS\PageResource\Pages;

use App\Filament\Resources\CMS\PageResource;
use App\Models\CMS\PageVersion;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn() => "/{$this->record->slug['en']}?preview=1")
                ->openUrlInNewTab(),
            Actions\Action::make('history')
                ->label('History')
                ->icon('heroicon-o-clock')
                ->form([
                    \Filament\Infolists\Components\TextEntry::make('version_number'),
                    \Filament\Infolists\Components\TextEntry::make('created_at'),
                    \Filament\Infolists\Components\TextEntry::make('change_summary'),
                ])
                ->action(function () {
                    // Show version history
                }),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Create a version snapshot
        if (auth()->check()) {
            PageVersion::createSnapshot($this->record, auth()->user(), 'Auto-saved');
        }
    }
}
