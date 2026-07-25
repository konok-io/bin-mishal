<?php

declare(strict_types=1);

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\Pages\BasePage;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Components\KeyValueEntry;

{
    protected static string $resource = \App\Filament\Resources\AuditLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
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
