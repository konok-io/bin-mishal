<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Filament\Resources\HomepageConfig\Pages\ListStatistics;
use App\Filament\Resources\HomepageConfig\Pages\ListTrustBadges;
use App\Filament\Resources\HomepageConfig\Pages\ListQuickServices;
use App\Filament\Resources\HomepageConfig\Pages\ListFeatureCards;
use App\Filament\Resources\HomepageConfig\Pages\ListFlightRoutes;
use App\Models\Statistic;
use Filament\Schemas;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class StatisticResource extends BaseResource
{
    protected static ?string $model = Statistic::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Grid::make(3)
                    ->schema([
                        Schemas\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->unique(Statistic::class, 'key', ignoreRecord: true)
                            ->helperText('Unique identifier (e.g., "customers", "tickets")'),
                        Schemas\Components\TextInput::make('label')
                            ->label('Label (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('label_bn')
                            ->label('Label (Bengali)'),
                        Schemas\Components\TextInput::make('label_ar')
                            ->label('Label (Arabic)'),
                    ]),
                Schemas\Components\Grid::make(4)
                    ->schema([
                        Schemas\Components\TextInput::make('value')
                            ->label('Value')
                            ->numeric()
                            ->required(),
                        Schemas\Components\TextInput::make('prefix')
                            ->label('Prefix (English)')
                            ->placeholder('e.g., SAR'),
                        Schemas\Components\TextInput::make('suffix')
                            ->label('Suffix (English)')
                            ->placeholder('e.g., K+'),
                        Schemas\Components\TextInput::make('suffix_bn')
                            ->label('Suffix (Bengali)'),
                        Schemas\Components\TextInput::make('suffix_ar')
                            ->label('Suffix (Arabic)'),
                    ]),
                Schemas\Components\Grid::make(3)
                    ->schema([
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('heroicon-o-users'),
                        Schemas\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->default('#198754'),
                        Schemas\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),
                Schemas\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Key')->sortable(),
                Tables\Columns\TextColumn::make('label')->label('Label')->sortable(),
                Tables\Columns\TextColumn::make('value')->label('Value')->sortable(),
                Tables\Columns\IconColumn::make('icon')->label('Icon'),
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
            'index' => ListStatistics::route('/'),
        ];
    }
}
