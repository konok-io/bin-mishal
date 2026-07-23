<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\FlightRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlightRouteResource extends Resource
{
    protected static ?string $model = FlightRoute::class;
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Flight Routes';
    protected static ?int $navigationSort = 24;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('From')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('from_city')
                                    ->label('City (English)')
                                    ->required(),
                                Forms\Components\TextInput::make('from_city_bn')
                                    ->label('City (Bengali)'),
                                Forms\Components\TextInput::make('from_city_ar')
                                    ->label('City (Arabic)'),
                            ]),
                        Forms\Components\Select::make('from_country')
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
                Forms\Components\Section::make('To')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('to_city')
                                    ->label('City (English)')
                                    ->required(),
                                Forms\Components\TextInput::make('to_city_bn')
                                    ->label('City (Bengali)'),
                                Forms\Components\TextInput::make('to_city_ar')
                                    ->label('City (Arabic)'),
                            ]),
                        Forms\Components\Select::make('to_country')
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
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Forms\Components\Select::make('currency')
                                    ->label('Currency')
                                    ->options([
                                        'SAR' => 'SAR',
                                        'BDT' => 'BDT',
                                    ])
                                    ->default('SAR'),
                                Forms\Components\TextInput::make('airline')
                                    ->label('Airline'),
                                Forms\Components\TextInput::make('image_url')
                                    ->label('Image URL'),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Toggle::make('is_active')
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
