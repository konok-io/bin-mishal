<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationLabel = 'Audit Log';
    protected static ?int $navigationSort = 100;
    protected static bool $shouldRegisterNavigation = true;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('action')
                    ->label('Action')
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->colors([
                        'success' => AuditLog::ACTION_CREATE,
                        'warning' => AuditLog::ACTION_UPDATE,
                        'danger' => AuditLog::ACTION_DELETE,
                        'gray' => [AuditLog::ACTION_LOGIN, AuditLog::ACTION_LOGOUT, AuditLog::ACTION_VIEW],
                    ]),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Model')
                    ->formatStateUsing(fn($state) => class_basename($state)),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        AuditLog::ACTION_CREATE => 'Create',
                        AuditLog::ACTION_UPDATE => 'Update',
                        AuditLog::ACTION_DELETE => 'Delete',
                        AuditLog::ACTION_LOGIN => 'Login',
                        AuditLog::ACTION_LOGOUT => 'Logout',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
