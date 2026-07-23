<?php

declare(strict_types=1);

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\AuditLogResource;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;

class ViewAuditLog extends ViewRecord
{
    protected static ?string $resource = AuditLogResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Action Details')
                    ->schema([
                        TextEntry::make('user.name')->label('User'),
                        TextEntry::make('action')->label('Action'),
                        TextEntry::make('description')->label('Description'),
                    ])->columns(3),

                Section::make('Model Info')
                    ->schema([
                        TextEntry::make('model_type')->label('Model Type'),
                        TextEntry::make('model_id')->label('Model ID'),
                    ])->columns(2),

                Section::make('Changes')
                    ->schema([
                        KeyValueEntry::make('old_values')->label('Old Values'),
                        KeyValueEntry::make('new_values')->label('New Values'),
                    ])->columns(2),

                Section::make('Request Info')
                    ->schema([
                        TextEntry::make('ip_address')->label('IP Address'),
                        TextEntry::make('user_agent')->label('User Agent'),
                        TextEntry::make('created_at')->label('Timestamp'),
                    ])->columns(3),
            ]);
    }
}
