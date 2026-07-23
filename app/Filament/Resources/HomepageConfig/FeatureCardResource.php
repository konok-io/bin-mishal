<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\FeatureCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeatureCardResource extends Resource
{
    protected static ?string $model = FeatureCard::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Feature Cards';
    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title (English)')
                            ->required(),
                        Forms\Components\TextInput::make('title_bn')
                            ->label('Title (Bengali)'),
                        Forms\Components\TextInput::make('title_ar')
                            ->label('Title (Arabic)'),
                    ]),
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label('Number')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('number_suffix')
                            ->label('Suffix (English)')
                            ->placeholder('K+'),
                        Forms\Components\TextInput::make('number_suffix_bn')
                            ->label('Suffix (Bengali)'),
                        Forms\Components\TextInput::make('number_suffix_ar')
                            ->label('Suffix (Arabic)'),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('heroicon-o-sparkles'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->default('#198754'),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Description (English)')
                    ->rows(2),
                Forms\Components\Textarea::make('description_bn')
                    ->label('Description (Bengali)')
                    ->rows(2),
                Forms\Components\Textarea::make('description_ar')
                    ->label('Description (Arabic)')
                    ->rows(2),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
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
                Tables\Columns\TextColumn::make('number')->label('Number'),
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
            'index' => \App\Filament\Resources\HomepageConfig\Pages\ListFeatureCards::route('/'),
        ];
    }
}
