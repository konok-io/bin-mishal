<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS;

use App\Filament\Resources\CMS\MenuResource\Pages;
use App\Filament\Resources\CMS\MenuResource\RelationManagers;
use App\Models\CMS\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Menus';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Menu Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Menu::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('location')
                            ->label('Location')
                            ->required()
                            ->options(Menu::LOCATIONS)
                            ->searchable(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('location')
                    ->label('Location')
                    ->colors(['primary']),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options(Menu::LOCATIONS),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('location');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
