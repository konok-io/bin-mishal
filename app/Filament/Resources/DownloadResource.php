<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Downloads';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('File')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
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
                        Forms\Components\TextInput::make('file_name')
                            ->label('Display Name')
                            ->placeholder('e.g., Umrah Guide 2024.pdf'),
                    ])->columns(2),

                Forms\Components\Section::make('Category')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options(Download::CATEGORIES)
                            ->required(),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (optional)')
                            ->placeholder('heroicon-o-document'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Accent Color'),
                    ])->columns(3),

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
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),
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
