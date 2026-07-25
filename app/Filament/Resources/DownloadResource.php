<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends BaseResource
{
    protected static ?string $model = Download::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('File')
                    ->schema([
                        Schemas\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->directory('downloads')
                            ->visibility('public')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/*'])
                            ->maxSize(20480)
                            ->storeFileNamesIn('file_name')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->file_name) {
                                    $component->state($record->file_path);
                                }
                            }),
                        Schemas\Components\TextInput::make('file_name')
                            ->label('Display Name')
                            ->placeholder('e.g., Umrah Guide 2024.pdf'),
                    ])->columns(2),

                Schemas\Components\Section::make('Category')
                    ->schema([
                        Schemas\Components\Select::make('category')
                            ->label('Category')
                            ->options(Download::CATEGORIES)
                            ->required(),
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon (optional)')
                            ->placeholder('heroicon-o-document'),
                        Schemas\Components\ColorPicker::make('color')
                            ->label('Accent Color'),
                    ])->columns(3),

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
                        Schemas\Components\Toggle::make('is_featured')
                            ->label('Featured'),
                        Schemas\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn($record) => $record->translated_title)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('Size')
                    ->getStateUsing(fn($record) => $record->formatted_file_size),
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Downloads')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Download::CATEGORIES),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => $record->file_url)
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
