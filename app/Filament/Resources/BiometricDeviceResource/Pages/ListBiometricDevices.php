<?php

namespace App\Filament\Resources\BiometricDeviceResource\Pages;

use App\Filament\Resources\BiometricDeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBiometricDevices extends ListRecords
{
    protected static string $resource = BiometricDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Device'),
            Actions\Action::make('sync_all')
                ->label('Sync All Devices')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $service = app(\App\Services\BiometricService::class);
                    $devices = \App\Models\BiometricDevice::active()->whereNotNull('ip_address')->get();
                    $count = 0;
                    foreach ($devices as $device) {
                        try {
                            $service->syncFromDevice($device);
                            $count++;
                        } catch (\Exception $e) {
                            // Skip failed devices
                        }
                    }
                    $this->notify('success', "Synced {$count} devices");
                }),
        ];
    }

    protected function getHeaderTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All Devices')
                ->modifyQueryUsing(fn(Builder $query) => $query),
            Tab::make('active')
                ->label('Active')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'active')),
            Tab::make('offline')
                ->label('Offline')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'offline')),
            Tab::make('maintenance')
                ->label('Maintenance')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'maintenance')),
        ];
    }
}
