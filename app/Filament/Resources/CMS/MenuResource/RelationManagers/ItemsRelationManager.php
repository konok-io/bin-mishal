<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS\MenuResource\RelationManagers;

use App\Models\CMS\MenuItem;
use App\Models\CMS\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Content')
                    ->schema([
                        Schemas\Components\Tabs::make('Titles')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.en')
                                            ->label('Title (English)')
                                            ->required(),
                                        Schemas\Components\TextInput::make('description.en')
                                            ->label('Description (English)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.bn')
                                            ->label('Title (Bengali)'),
                                        Schemas\Components\TextInput::make('description.bn')
                                            ->label('Description (Bengali)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.ar')
                                            ->label('Title (Arabic)'),
                                        Schemas\Components\TextInput::make('description.ar')
                                            ->label('Description (Arabic)'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Link')
                    ->schema([
                        Schemas\Components\Select::make('type')
                            ->label('Link Type')
                            ->required()
                            ->options(MenuItem::TYPES)
                            ->default('custom')
                            ->reactive(),
                        Schemas\Components\TextInput::make('url')
                            ->label('URL')
                            ->visible(fn($get) => in_array($get('type'), ['custom', 'internal', 'external']))
                            ->placeholder('/about'),
                        Schemas\Components\TextInput::make('route_name')
                            ->label('Route Name')
                            ->visible(fn($get) => $get('type') === 'route')
                            ->placeholder('home'),
                        Schemas\Components\Select::make('page_id')
                            ->label('Page')
                            ->visible(fn($get) => $get('type') === 'page')
                            ->options(Page::query()->pluck('title->en', 'id')),
                        Schemas\Components\Select::make('target')
                            ->label('Open In')
                            ->options(['_self' => 'Same Window', '_blank' => 'New Window'])
                            ->default('_self'),
                    ])->columns(2),

                Schemas\Components\Section::make('Display')
                    ->schema([
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon (Heroicon)')
                            ->placeholder('heroicons-o-user'),
                        Schemas\Components\TextInput::make('css_class')
                            ->label('CSS Class'),
                        Schemas\Components\Select::make('badge_text')
                            ->label('Badge')
                            ->options([
                                'new' => 'New (নতুন)',
                                'hot' => 'Hot',
                                'sale' => 'Sale',
                            ])
                            ->nullable(),
                        Schemas\Components\Toggle::make('is_mega')
                            ->label('Mega Menu Item'),
                        Schemas\Components\TextInput::make('mega_column')
                            ->label('Mega Column')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4),
                        Schemas\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Schemas\Components\Section::make('Ordering')
                    ->schema([
                        Schemas\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),
                        Schemas\Components\Select::make('parent_id')
                            ->label('Parent Item')
                            ->options(function () {
                                $menu = $this->getOwnerRecord();
                                return $menu->items()
                                    ->whereNull('parent_id')
                                    ->pluck('title->en', 'id');
                            })
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn($record) => $record->translated_title),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type'),
                Tables\Columns\IconColumn::make('is_mega')
                    ->label('Mega')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Children')
                    ->counts('children'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ReplicateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
