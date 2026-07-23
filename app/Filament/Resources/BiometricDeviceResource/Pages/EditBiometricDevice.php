<?php

namespace App\Filament\Resources\BiometricDeviceResource\Pages;

use App\Filament\Resources\BiometricDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiometricDevice extends EditRecord
{
    protected static string $resource = BiometricDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('test_connection')
                ->label('Test Connection')
                ->icon('heroicon-o-wifi')
                ->color('info')
                ->action(function () {
                    $result = BiometricDeviceResource::testConnection($this->record);
                    $this->notify($result['success'] ? 'success' : 'danger', $result['message']);
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
