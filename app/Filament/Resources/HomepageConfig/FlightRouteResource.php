<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\FlightRoute;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class FlightRouteResource extends BaseResource
{
    protected static ?string $model = FlightRoute::class;

    public static function getNavigationLabel(): string
    {
        return 'Flight Route';
    }

    public static function getNavigationGroup(): string
    {
        return 'Homepage';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('From')
                    ->schema([
                        Schemas\Components\Grid::make(3)
                            ->schema([
                                Schemas\Components\TextInput::make('from_city')
                                    ->label('City (English)')
                                    ->required(),
                                Schemas\Components\TextInput::make('from_city_bn')
                                    ->label('City (Bengali)'),
                                Schemas\Components\TextInput::make('from_city_ar')
                                    ->label('City (Arabic)'),
                            ]),
                        Schemas\Components\Select::make('from_country')
                            ->label('Country')
                            ->options([
                                'SA' => 'Saudi Arabia',
                                'BD' => 'Bangladesh',
                                'AE' => 'UAE',
                                'QA' => 'Qatar',
                                'KW' => 'Kuwait',
                            ])
                            ->default('SA'),
                    ]),
                Schemas\Components\Section::make('To')
                    ->schema([
                        Schemas\Components\Grid::make(3)
                            ->schema([
                                Schemas\Components\TextInput::make('to_city')
                                    ->label('City (English)')
                                    ->required(),
                                Schemas\Components\TextInput::make('to_city_bn')
                                    ->label('City (Bengali)'),
                                Schemas\Components\TextInput::make('to_city_ar')
                                    ->label('City (Arabic)'),
                            ]),
                        Schemas\Components\Select::make('to_country')
                            ->label('Country')
                            ->options([
                                'SA' => 'Saudi Arabia',
                                'BD' => 'Bangladesh',
                                'AE' => 'UAE',
                                'QA' => 'Qatar',
                                'KW' => 'Kuwait',
                            ])
                            ->default('BD'),
                    ]),
                Schemas\Components\Section::make('Details')
                    ->schema([
                        Schemas\Components\Grid::make(4)
                            ->schema([
                                Schemas\Components\TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Schemas\Components\Select::make('currency')
                                    ->label('Currency')
                                    ->options([
                                        'SAR' => 'SAR',
                                        'BDT' => 'BDT',
                                    ])
                                    ->default('SAR'),
                                Schemas\Components\TextInput::make('airline')
                                    ->label('Airline'),
                                Schemas\Components\TextInput::make('image_url')
                                    ->label('Image URL'),
                            ]),
                        Schemas\Components\Grid::make(3)
                            ->schema([
                                Schemas\Components\Toggle::make('is_featured')
                                    ->label('Featured'),
                                Schemas\Components\TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0),
                                Schemas\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_city')->label('From')->sortable(),
                Tables\Columns\TextColumn::make('to_city')->label('To')->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Price')->money('SAR'),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->defaultSort('is_featured', 'desc')
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
            'index' => \App\Filament\Resources\HomepageConfig\Pages\ListFlightRoutes::route('/'),
        ];
    }
}
