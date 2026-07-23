<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageConfig;

use App\Models\QuickService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuickServiceResource extends Resource
{
    protected static ?string $model = QuickService::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Quick Services';
    protected static ?int $navigationSort = 22;

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
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('heroicon-o-sparkles'),
                        Forms\Components\TextInput::make('link')
                            ->label('Link URL'),
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
