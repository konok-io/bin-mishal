<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Media Library';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('File Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Display Name')
                            ->required(),
                        Forms\Components\TextInput::make('alt')
                            ->label('Alt Text')
                            ->helperText('For SEO and accessibility'),
                        Forms\Components\TextInput::make('title')
                            ->label('Title'),
                        Forms\Components\TextInput::make('folder')
                            ->label('Folder/Category')
                            ->selectOptionFromQuery(fn() => Media::getFolders())
                            ->default('general'),
                        Forms\Components\TagsInput::make('tags')
                            ->label('Tags'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('caption')
                            ->label('Caption'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description'),
                    ]),
                
                Forms\Components\Section::make('Settings')
                    ->schema([
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
                Tables\Columns\ImageColumn::make('url')
                    ->label('Preview')
                    ->square()
                    ->size(60),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('folder')
                    ->label('Folder')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('file_type')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('formatted_size')
                    ->label('Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Downloads')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('folder')
                    ->label('Folder')
                    ->options(Media::getFolders()),
                Tables\Filters\SelectFilter::make('file_type')
                    ->label('Type')
                    ->options(Media::getFileTypes()),
                Tables\Filters\Filter::make('active_only')
                    ->query(fn($query) => $query->where('is_active', true))
                    ->label('Active Only'),
                Tables\Filters\Filter::make('unused')
                    ->query(fn($query) => $query->where('download_count', 0))
                    ->label('Unused (0 downloads)'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('copy_url')
                    ->label('Copy URL')
                    ->icon('heroicon-o-link')
                    ->action(function (Media $record) {
                        $url = $record->url;
                        // Copy to clipboard via JS will be handled by filament
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Media $record) {
                        // Delete file from storage
                        if (Storage::exists($record->file_name)) {
                            Storage::delete($record->file_name);
                        }
                        // Delete thumbnail if exists
                        if ($record->isImage() && Storage::exists("thumbnails/{$record->file_name}")) {
                            Storage::delete("thumbnails/{$record->file_name}");
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if (Storage::exists($record->file_name)) {
                                    Storage::delete($record->file_name);
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make('move_folder')
                        ->label('Move to Folder')
                        ->icon('heroicon-o-folder')
                        ->form([
                            Forms\Components\Select::make('folder')
                                ->label('Select Folder')
                                ->options(Media::getFolders())
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->update(['folder' => $data['folder']]);
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'view' => Pages\ViewMedia::route('/{record}'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
