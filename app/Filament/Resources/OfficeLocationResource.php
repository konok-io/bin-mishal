<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeLocationResource\Pages;
use App\Models\OfficeLocation;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class OfficeLocationResource extends BaseResource
{
    protected static ?string $model = OfficeLocation::class;

    public static function getNavigationLabel(): string
    {
        return 'Office Location';
    }

    public static function getNavigationGroup(): string
    {
        return 'System';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Basic Information')
                    ->schema([
                        Schemas\Components\TextInput::make('name')
                            ->label('Office Name')
                            ->required()
                            ->placeholder('e.g., Riyadh Head Office'),
                        Schemas\Components\TextInput::make('city')
                            ->label('City')
                            ->placeholder('e.g., Riyadh'),
                        Schemas\Components\TextInput::make('country')
                            ->label('Country')
                            ->default('Saudi Arabia'),
                        Schemas\Components\Toggle::make('is_headquarters')
                            ->label('Headquarters'),
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
                
                Schemas\Components\Section::make('Address')
                    ->schema([
                        Schemas\Components\Textarea::make('address')
                            ->label('Full Address')
                            ->required()
                            ->rows(2),
                        Schemas\Components\TextInput::make('working_hours')
                            ->label('Working Hours')
                            ->placeholder('Sat-Thu: 9AM-6PM'),
                    ]),
                
                Schemas\Components\Section::make('Contact')
                    ->schema([
                        Schemas\Components\TextInput::make('phone')
                            ->label('Phone'),
                        Schemas\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp'),
                        Schemas\Components\TextInput::make('email')
                            ->label('Email'),
                    ])->columns(3),
                
                Schemas\Components\Section::make('Map Settings')
                    ->schema([
                        Schemas\Components\Grid::make(2)
                            ->schema([
                                Schemas\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric(),
                                Schemas\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric(),
                            ]),
                        Schemas\Components\Select::make('map_zoom')
                            ->label('Map Zoom')
                            ->options(collect(range(1, 21))->mapWithKeys(fn($i) => [$i => $i])),
                        Schemas\Components\Select::make('map_type')
                            ->label('Map Type')
                            ->options([
                                'roadmap' => 'Roadmap',
                                'satellite' => 'Satellite',
                                'hybrid' => 'Hybrid',
                                'terrain' => 'Terrain',
                            ]),
                    ])->columns(2),
                
                Schemas\Components\Section::make('Additional')
                    ->schema([
                        Schemas\Components\Textarea::make('description')
                            ->label('Description'),
                        Schemas\Components\FileUpload::make('image')
                            ->label('Office Image')
                            ->image(),
                        Schemas\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Office')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_headquarters')
                    ->label('HQ')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\Filter::make('headquarters')
                    ->query(fn($query) => $query->where('is_headquarters', true))
                    ->label('Headquarters Only'),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn($query) => $query->where('is_active', false))
                    ->label('Inactive'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('open_maps')
                    ->label('View on Maps')
                    ->icon('heroicon-o-map-pin')
                    ->url(fn(OfficeLocation $record) => $record->google_maps_url)
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListOfficeLocations::route('/'),
            'view' => Pages\ViewOfficeLocation::route('/{record}'),
            'edit' => Pages\EditOfficeLocation::route('/{record}/edit'),
        ];
    }
}
