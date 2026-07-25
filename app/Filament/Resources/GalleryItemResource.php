<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends BaseResource
{
    protected static ?string $model = GalleryItem::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Basic Info')
                    ->schema([
                        Schemas\Components\Select::make('type')
                            ->label('Type')
                            ->options(GalleryItem::TYPES)
                            ->required()
                            ->default('photo')
                            ->reactive(),
                        Schemas\Components\TextInput::make('category')
                            ->label('Category')
                            ->placeholder('e.g., Events, Office, Team'),
                        Schemas\Components\Toggle::make('is_featured')
                            ->label('Featured'),
                        Schemas\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Schemas\Components\Section::make('Photo')
                    ->schema([
                        Schemas\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('gallery')
                            ->visibility('public'),
                    ])->visible(fn($get) => $get('type') === 'photo'),

                Schemas\Components\Section::make('Video')
                    ->schema([
                        Schemas\Components\TextInput::make('video_url')
                            ->label('YouTube/Vimeo URL')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=...'),
                        Schemas\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail Image')
                            ->image()
                            ->directory('gallery/thumbnails')
                            ->visibility('public'),
                    ])->visible(fn($get) => $get('type') === 'video'),

                Schemas\Components\Section::make('Titles (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Titles')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.en')
                                            ->label('Title')
                                            ->required(),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.bn')
                                            ->label('Title'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.ar')
                                            ->label('Title'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Description (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Descriptions')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.en')
                                            ->label('Description'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.bn')
                                            ->label('Description'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.ar')
                                            ->label('Description'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Settings')
                    ->schema([
                        Schemas\Components\TextInput::make('order')
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
