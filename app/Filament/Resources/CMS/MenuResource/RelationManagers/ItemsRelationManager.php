<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS\MenuResource\RelationManagers;

use App\Models\CMS\MenuItem;
use App\Models\CMS\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Tabs::make('Titles')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.en')
                                            ->label('Title (English)')
                                            ->required(),
                                        Forms\Components\TextInput::make('description.en')
                                            ->label('Description (English)'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.bn')
                                            ->label('Title (Bengali)'),
                                        Forms\Components\TextInput::make('description.bn')
                                            ->label('Description (Bengali)'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.ar')
                                            ->label('Title (Arabic)'),
                                        Forms\Components\TextInput::make('description.ar')
                                            ->label('Description (Arabic)'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Link')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Link Type')
                            ->required()
                            ->options(MenuItem::TYPES)
                            ->default('custom')
                            ->reactive(),
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->visible(fn($get) => in_array($get('type'), ['custom', 'internal', 'external']))
                            ->placeholder('/about'),
                        Forms\Components\TextInput::make('route_name')
                            ->label('Route Name')
                            ->visible(fn($get) => $get('type') === 'route')
                            ->placeholder('home'),
                        Forms\Components\Select::make('page_id')
                            ->label('Page')
                            ->visible(fn($get) => $get('type') === 'page')
                            ->options(Page::query()->pluck('title->en', 'id')),
                        Forms\Components\Select::make('target')
                            ->label('Open In')
                            ->options(['_self' => 'Same Window', '_blank' => 'New Window'])
                            ->default('_self'),
                    ])->columns(2),

                Forms\Components\Section::make('Display')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (Heroicon)')
                            ->placeholder('heroicons-o-user'),
                        Forms\Components\TextInput::make('css_class')
                            ->label('CSS Class'),
                        Forms\Components\Select::make('badge_text')
                            ->label('Badge')
                            ->options([
                                'new' => 'New (নতুন)',
                                'hot' => 'Hot',
                                'sale' => 'Sale',
                            ])
                            ->nullable(),
                        Forms\Components\Toggle::make('is_mega')
                            ->label('Mega Menu Item'),
                        Forms\Components\TextInput::make('mega_column')
                            ->label('Mega Column')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Ordering')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Select::make('parent_id')
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
