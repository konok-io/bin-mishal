<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BiometricDeviceResource\Pages;
use App\Models\BiometricDevice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class BiometricDeviceResource extends Resource
{
    protected static ?string $model = BiometricDevice::class;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationGroup = 'HR';
    protected static ?string $navigationLabel = 'Biometric Devices';
    protected static ?int $navigationSort = 15;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('biometric.manage'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Device Information')
                    ->schema([
                        Forms\Components\TextInput::make('device_id')
                            ->label('Device ID / Serial Number')
                            ->required()
                            ->unique(BiometricDevice::class, 'device_id', fn($record) => $record),
                        Forms\Components\TextInput::make('name')
                            ->label('Device Name')
                            ->required()
                            ->placeholder('e.g., Main Entrance - Floor 1'),
                    ])->columns(2),

                Section::make('Location')
                    ->schema([
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch/Office')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Device Configuration')
                    ->schema([
                        Forms\Components\Select::make('brand')
                            ->label('Device Brand')
                            ->options(BiometricDevice::BRANDS)
                            ->default('zkteco')
                            ->required(),
                        Forms\Components\TextInput::make('model')
                            ->label('Model Number')
                            ->placeholder('e.g., C3-400'),
                    ])->columns(2),

                Section::make('Connection Settings')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->placeholder('192.168.1.100')
                            ->ip(),
                        Forms\Components\TextInput::make('port')
                            ->label('Port')
                            ->default(4370)
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(65535),
                        Forms\Components\TextInput::make('comm_key')
                            ->label('Communication Key')
                            ->password()
                            ->revealable(),
                    ])->columns(3),

                Section::make('Sync Configuration')
                    ->schema([
                        Forms\Components\Select::make('sync_method')
                            ->label('Sync Method')
                            ->options(BiometricDevice::SYNC_METHODS)
                            ->default('webhook')
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('webhook_url')
                            ->label('Webhook URL')
                            ->url()
                            ->placeholder('https://yoursite.com/api/biometric/webhook/{device_id}')
                            ->visible(fn($get) => $get('sync_method') === 'webhook'),
                        Forms\Components\TextInput::make('api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->visible(fn($get) => in_array($get('sync_method'), ['webhook', 'polling'])),
                        Forms\Components\TextInput::make('sync_interval')
                            ->label('Sync Interval (minutes)')
                            ->numeric()
                            ->default(5)
                            ->visible(fn($get) => $get('sync_method') === 'polling'),
                    ])->columns(2),

                Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Device Status')
                            ->options(BiometricDevice::STATUSES)
                            ->default('active'),
                        Forms\Components\DateTimePicker::make('last_sync_at')
                            ->label('Last Sync')
                            ->disabled()
                            ->visible(fn($record) => $record && $record->last_sync_at),
                    ])->columns(2),

                Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Additional notes about this device...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn($state) => match($state) {
                        'active' => 'heroicon-o-check-circle',
                        'inactive' => 'heroicon-o-x-circle',
                        'offline' => 'heroicon-o-wifi-off',
                        'maintenance' => 'heroicon-o-wrench',
                    })
                    ->color(fn($state) => match($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'offline' => 'danger',
                        'maintenance' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Device Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device_id')
                    ->label('Device ID')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Brand')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->copyable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('sync_method')
                    ->label('Sync')
                    ->colors([
                        'info' => 'webhook',
                        'warning' => 'polling',
                        'gray' => 'manual',
                        'secondary' => 'csv',
                    ]),
                Tables\Columns\TextColumn::make('last_sync_at')
                    ->label('Last Sync')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance_count')
                    ->label('Records Today')
                    ->getStateUsing(fn($record) => $record->attendance()->whereDate('punch_time', today())->count()),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(BiometricDevice::STATUSES),
                Tables\Filters\SelectFilter::make('brand')
                    ->options(BiometricDevice::BRANDS),
                Tables\Filters\SelectFilter::make('sync_method')
                    ->options(BiometricDevice::SYNC_METHODS),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('test_connection')
                    ->label('Test Connection')
                    ->icon('heroicon-o-wifi')
                    ->color('info')
                    ->action(fn(BiometricDevice $record) => static::testConnection($record)),
                Tables\Actions\Action::make('sync')
                    ->label('Sync Now')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(fn(BiometricDevice $record) => static::syncDevice($record)),
                Tables\Actions\Action::make('mark_online')
                    ->label('Mark Online')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(BiometricDevice $record) => $record->update(['status' => 'active']))
                    ->visible(fn(BiometricDevice $record) => $record->status === 'offline'),
                Tables\Actions\Action::make('mark_offline')
                    ->label('Mark Offline')
                    ->icon('heroicon-o-wifi-off')
                    ->color('danger')
                    ->action(fn(BiometricDevice $record) => $record->markOffline())
                    ->visible(fn(BiometricDevice $record) => $record->status === 'active'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Device Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('device_id'),
                        TextEntry::make('brand')
                            ->badge(),
                        TextEntry::make('model'),
                    ])->columns(2),

                Section::make('Location & Status')
                    ->schema([
                        TextEntry::make('branch.name'),
                        IconEntry::make('status')
                            ->icon(fn($state) => match($state) {
                                'active' => 'heroicon-o-check-circle',
                                'offline' => 'heroicon-o-wifi-off',
                                default => 'heroicon-o-minus-circle',
                            })
                            ->color(fn($state) => match($state) {
                                'active' => 'success',
                                'offline' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('last_sync_at')
                            ->dateTime(),
                    ])->columns(3),

                Section::make('Connection')
                    ->schema([
                        TextEntry::make('ip_address'),
                        TextEntry::make('port'),
                        TextEntry::make('sync_method')
                            ->badge(),
                    ])->columns(3),

                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->placeholder('No notes'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBiometricDevices::route('/'),
            'view' => Pages\ViewBiometricDevice::route('/{record}'),
            'edit' => Pages\EditBiometricDevice::route('/{record}/edit'),
        ];
    }

    public static function testConnection(BiometricDevice $record): array
    {
        $service = app(\App\Services\BiometricService::class);
        $isOnline = $service->testConnection($record);

        if ($isOnline) {
            $record->update(['status' => 'active']);
            return ['success' => true, 'message' => 'Device is reachable and online'];
        }

        return ['success' => false, 'message' => 'Device is not reachable'];
    }

    public static function syncDevice(BiometricDevice $record): array
    {
        $service = app(\App\Services\BiometricService::class);
        
        try {
            $results = $service->syncFromDevice($record);
            return [
                'success' => $results['success'],
                'message' => "Synced {$results['records']} records",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ];
        }
    }
}
