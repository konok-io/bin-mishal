<?php

namespace App\Filament\Resources\LedgerEntryResource\Pages;

use App\Filament\Resources\LedgerEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLedgerEntry extends ViewRecord
{
    protected static string $resource = LedgerEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_reference')
                ->label('View Reference')
                ->icon('heroicon-o-link')
                ->visible(fn() => $this->record->reference_type && $this->record->reference_id)
                ->url(function () {
                    if (!$this->record->reference_type || !$this->record->reference_id) {
                        return null;
                    }
                    
                    return match($this->record->reference_type) {
                        'booking' => route('filament.admin.resources/bookings/view', $this->record->reference_id),
                        'payroll' => route('filament.admin/resources/payrolls/view', $this->record->reference_id),
                        default => null,
                    };
                }),
        ];
    }
}
