<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\TrustBadge;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class TrustBadgeResource extends BaseResource
{
    protected static ?string $model = TrustBadge::class;

    public static function getNavigationLabel(): string
    {
        return 'Trust Badge';
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
                        Schemas\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Schemas\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                    ]),
                Schemas\Components\Grid::make(2)
                    ->schema([
                        Schemas\Components\TextInput::make('image_url')
                            ->label('Image URL')
                            ->url(),
                        Schemas\Components\TextInput::make('link')
                            ->label('Link URL')
                            ->url(),
                    ]),
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
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
                Tables\Columns\ImageColumn::make('image_url')->label('Image'),
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
            'index' => \App\Filament\Resources\HomepageConfig\Pages\ListTrustBadges::route('/'),
        ];
    }
}
