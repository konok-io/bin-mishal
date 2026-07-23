<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Filament\Resources\HomepageConfig\Pages\ListStatistics;
use App\Filament\Resources\HomepageConfig\Pages\ListTrustBadges;
use App\Filament\Resources\HomepageConfig\Pages\ListQuickServices;
use App\Filament\Resources\HomepageConfig\Pages\ListFeatureCards;
use App\Filament\Resources\HomepageConfig\Pages\ListFlightRoutes;
use App\Models\Statistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatisticResource extends Resource
{
    protected static ?string $model = Statistic::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Statistics';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->unique(Statistic::class, 'key', ignoreRecord: true)
                            ->helperText('Unique identifier (e.g., "customers", "tickets")'),
                        Forms\Components\TextInput::make('label')
                            ->label('Label (English)')
                            ->required(),
                        Forms\Components\TextInput::make('label_bn')
                            ->label('Label (Bengali)'),
                        Forms\Components\TextInput::make('label_ar')
                            ->label('Label (Arabic)'),
                    ]),
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('prefix')
                            ->label('Prefix (English)')
                            ->placeholder('e.g., SAR'),
                        Forms\Components\TextInput::make('suffix')
                            ->label('Suffix (English)')
                            ->placeholder('e.g., K+'),
                        Forms\Components\TextInput::make('suffix_bn')
                            ->label('Suffix (Bengali)'),
                        Forms\Components\TextInput::make('suffix_ar')
                            ->label('Suffix (Arabic)'),
                    ]),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('heroicon-o-users'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->default('#198754'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),
                Forms\Components\Toggle::make('is_active')
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
