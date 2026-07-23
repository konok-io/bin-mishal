<?php

namespace App\Filament\Resources\BiometricDeviceResource\Pages;

use App\Filament\Resources\BiometricDeviceResource;
use App\Models\BiometricAttendance;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManagerConfig;
use Filament\Resources\RelationManagers\RelationGroup;
use Illuminate\Database\Eloquent\Builder;

class ViewBiometricDevice extends ViewRecord
{
    protected static string $resource = BiometricDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('test_connection')
                ->label('Test Connection')
                ->icon('heroicon-o-wifi')
                ->color('info')
                ->action(function () {
                    $result = BiometricDeviceResource::testConnection($this->record);
                    $this->notify($result['success'] ? 'success' : 'danger', $result['message']);
                }),
            Actions\Action::make('sync')
                ->label('Sync Now')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(function () {
                    $result = BiometricDeviceResource::syncDevice($this->record);
                    $this->notify($result['success'] ? 'success' : 'danger', $result['message']);
                }),
        ];
    }

    protected function getFooterActions(): array
    {
        return [
            Actions\Action::make('import_csv')
                ->label('Import CSV')
                ->icon('heroicon-o-document-arrow-up')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('CSV File')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $service = app(\App\Services\BiometricService::class);
                    $results = $service->importFromCSV(
                        $this->record,
                        storage_path('app/' . $data['file'])
                    );
                    
                    $this->notify(
                        $results['success'] ? 'success' : 'danger',
                        "Imported {$results['imported']} records"
                    );
                }),
        ];
    }
}
