<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\QuickService;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class QuickServiceResource extends BaseResource
{
    protected static ?string $model = QuickService::class;

    public static function getNavigationLabel(): string
    {
        return 'Quick Service';
    }

    public static function getNavigationGroup(): string
    {
        return 'Homepage';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Grid::make(3)
                    ->schema([
                        Schemas\Components\TextInput::make('title')
                            ->label('Title (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('title_bn')
                            ->label('Title (Bengali)'),
                        Schemas\Components\TextInput::make('title_ar')
                            ->label('Title (Arabic)'),
                    ]),
                Schemas\Components\Grid::make(2)
                    ->schema([
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('heroicon-o-sparkles'),
                        Schemas\Components\TextInput::make('link')
                            ->label('Link URL'),
                    ]),
                Schemas\Components\Textarea::make('description')
                    ->label('Description (English)')
                    ->rows(2),
                Schemas\Components\Textarea::make('description_bn')
                    ->label('Description (Bengali)')
                    ->rows(2),
                Schemas\Components\Textarea::make('description_ar')
                    ->label('Description (Arabic)')
                    ->rows(2),
                Schemas\Components\Grid::make(2)
                    ->schema([
                        Schemas\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable(),
                Tables\Columns\IconColumn::make('icon')->label('Icon'),
                Tables\Columns\TextColumn::make('link')->label('Link')->limit(30),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\HomepageConfig\Pages\ListQuickServices::route('/'),
        ];
    }
}
