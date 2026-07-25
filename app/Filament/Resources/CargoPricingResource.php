<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CargoPricingResource\Pages;
use App\Models\Cargo\CargoPricing;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class CargoPricingResource extends BaseResource
{
    protected static ?string $model = CargoPricing::class;

    public static function getNavigationLabel(): string
    {
        return 'Cargo Pricing';
    }

    public static function getNavigationGroup(): string
    {
        return 'Services';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Route Configuration')
                    ->schema([
                        Schemas\Components\Select::make('cargo_type_id')
                            ->label('Cargo Type')
                            ->relationship('cargoType', 'name')
                            ->required(),
                        Schemas\Components\Select::make('origin_city_id')
                            ->label('Origin City')
                            ->relationship('originCity', 'name')
                            ->required(),
                        Schemas\Components\Select::make('destination_city_id')
                            ->label('Destination City')
                            ->relationship('destinationCity', 'name')
                            ->required(),
                    ])->columns(3),

                Schemas\Components\Section::make('Pricing Settings')
                    ->schema([
                        Schemas\Components\Select::make('pricing_type')
                            ->label('Pricing Type')
                            ->options(CargoPricing::PRICING_TYPES)
                            ->required()
                            ->reactive(),
                        Schemas\Components\Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'SAR' => 'SAR (Saudi Riyal)',
                                'BDT' => 'BDT (Bangladeshi Taka)',
                            ])
                            ->default('SAR'),
                    ])->columns(2),

                Schemas\Components\Section::make('Fixed Price Settings')
                    ->description('For fixed or hybrid pricing')
                    ->schema([
                        Schemas\Components\TextInput::make('flat_rate')
                            ->label('Flat Package Price')
                            ->numeric()
                            ->prefix('SAR')
                            ->visible(fn($get) => in_array($get('pricing_type'), ['fixed', 'tiered', 'hybrid'])),
                    ])->columns(1),

                Schemas\Components\Section::make('Per KG Settings')
                    ->description('For per kg or hybrid pricing')
                    ->schema([
                        Schemas\Components\TextInput::make('min_weight')
                            ->label('Base Weight (included in base price)')
                            ->numeric()
                            ->suffix('kg')
                            ->default(0)
                            ->visible(fn($get) => in_array($get('pricing_type'), ['per_kg', 'hybrid'])),
                        Schemas\Components\TextInput::make('base_price')
                            ->label('Base Price')
                            ->numeric()
                            ->prefix('SAR')
                            ->visible(fn($get) => in_array($get('pricing_type'), ['per_kg', 'hybrid'])),
                        Schemas\Components\TextInput::make('price_per_kg')
                            ->label('Rate per KG')
                            ->numeric()
                            ->prefix('SAR')
                            ->suffix('/kg')
                            ->default(15)
                            ->visible(fn($get) => in_array($get('pricing_type'), ['per_kg', 'hybrid'])),
                    ])->columns(3),

                Schemas\Components\Section::make('Tiered Pricing')
                    ->description('Define weight brackets with fixed prices')
                    ->schema([
                        Schemas\Components\Textarea::make('tiered_pricing')
                            ->label('Weight Tiers (JSON)')
                            ->default(json_encode([
                                ['min_weight' => 0, 'max_weight' => 5, 'price' => 100],
                                ['min_weight' => 5, 'max_weight' => 10, 'price' => 180],
                                ['min_weight' => 10, 'max_weight' => 15, 'price' => 250],
                                ['min_weight' => 15, 'max_weight' => 23, 'price' => 300],
                                ['min_weight' => 23, 'max_weight' => 30, 'price' => 350],
                            ], JSON_PRETTY_PRINT))
                            ->visible(fn($get) => $get('pricing_type') === 'tiered')
                            ->rows(10)
                            ->hint('JSON format: [{"min_weight": 0, "max_weight": 5, "price": 100}, ...]'),
                    ])->visible(fn($get) => $get('pricing_type') === 'tiered'),

                Schemas\Components\Section::make('VAT & Status')
                    ->schema([
                        Schemas\Components\TextInput::make('vat_percentage')
                            ->label('VAT Percentage')
                            ->numeric()
                            ->suffix('%')
                            ->default(15),
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cargoType.name')
                    ->label('Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('originCity.name')
                    ->label('Origin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('destinationCity.name')
                    ->label('Destination')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('pricing_type')
                    ->label('Pricing')
                    ->colors([
                        'primary' => 'fixed',
                        'success' => 'per_kg',
                        'warning' => 'tiered',
                        'info' => 'hybrid',
                    ])
                    ->formatStateUsing(fn($state) => CargoPricing::PRICING_TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('flat_rate')
                    ->label('Rate')
                    ->money('SAR'),
                Tables\Columns\TextColumn::make('price_per_kg')
                    ->label('Per KG')
                    ->money('SAR'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCargoPricings::route('/'),
            'create' => Pages\CreateCargoPricing::route('/create'),
            'edit' => Pages\EditCargoPricing::route('/{record}/edit'),
        ];
    }
}
