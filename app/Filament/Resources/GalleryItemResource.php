<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Gallery';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Info')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options(GalleryItem::TYPES)
                            ->required()
                            ->default('photo')
                            ->reactive(),
                        Forms\Components\TextInput::make('category')
                            ->label('Category')
                            ->placeholder('e.g., Events, Office, Team'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('gallery')
                            ->visibility('public'),
                    ])->visible(fn($get) => $get('type') === 'photo'),

                Forms\Components\Section::make('Video')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')
                            ->label('YouTube/Vimeo URL')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=...'),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail Image')
                            ->image()
                            ->directory('gallery/thumbnails')
                            ->visibility('public'),
                    ])->visible(fn($get) => $get('type') === 'video'),

                Forms\Components\Section::make('Titles (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Titles')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.en')
                                            ->label('Title')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.bn')
                                            ->label('Title'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.ar')
                                            ->label('Title'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Description (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Descriptions')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.en')
                                            ->label('Description'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.bn')
                                            ->label('Description'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.ar')
                                            ->label('Description'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
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
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->visible(fn($record) => $record?->type === 'photo'),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->visible(fn($record) => $record?->type === 'video'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn($record) => $record->translated_title)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'photo',
                        'danger' => 'video',
                    ]),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(GalleryItem::TYPES),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('status'),
            ])
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
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
