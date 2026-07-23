<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\TrustBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrustBadgeResource extends Resource
{
    protected static ?string $model = TrustBadge::class;
    protected static ?string $navigationIcon = 'heroicon-o-badge-check';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Trust Badges';
    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Forms\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Forms\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('image_url')
                            ->label('Image URL')
                            ->url(),
                        Forms\Components\TextInput::make('link')
                            ->label('Link URL')
                            ->url(),
                    ]),
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
