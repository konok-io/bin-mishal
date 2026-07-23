<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Translations';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Translation Key')
                    ->schema([
                        Forms\Components\TextInput::make('group')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('source')
                            ->options([
                                'code' => 'From Code',
                                'manual' => 'Manual Entry',
                                'imported' => 'Imported',
                            ])
                            ->default('code'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'complete' => 'Complete',
                                'missing_bn' => 'Missing Bengali',
                                'missing_en' => 'Missing English',
                                'missing_ar' => 'Missing Arabic',
                                'needs_review' => 'Needs Review',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Translations')
                    ->schema([
                        Forms\Components\Textarea::make('value_bn')
                            ->label('Bengali (বাংলা)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('value_en')
                            ->label('English')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('value_ar')
                            ->label('Arabic (العربية)')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['dir' => 'rtl']),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('value_bn')
                    ->label('Bengali')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->value_bn),
                Tables\Columns\TextColumn::make('value_en')
                    ->label('English')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->value_en),
                Tables\Columns\TextColumn::make('value_ar')
                    ->label('Arabic')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->value_ar)
                    ->html(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'complete',
                        'warning' => 'needs_review',
                        'danger' => fn($state) => in_array($state, ['missing_bn', 'missing_en', 'missing_ar']),
                    ]),
                Tables\Columns\TextColumn::make('last_seen_in_code_at')
                    ->label('Last Seen')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn() => Translation::query()->distinct()->pluck('group', 'group'))
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'missing_bn' => 'Missing Bengali',
                        'missing_en' => 'Missing English',
                        'missing_ar' => 'Missing Arabic',
                        'needs_review' => 'Needs Review',
                    ]),
                Tables\Filters\Filter::make('incomplete')
                    ->label('Missing Translations')
                    ->query(fn($query) => $query->where('status', '!=', 'complete')),
                Tables\Filters\Filter::make('recently_added')
                    ->label('Recently Added')
                    ->query(fn($query) => $query->where('last_seen_in_code_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\TranslationExporter::class),
                    Tables\Actions\ImportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync')
                    ->label('Sync from Code')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn() => \Illuminate\Support\Facades\Artisan::call('translations:sync')),
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn() => redirect()->route('filament.resources.translations.export')),
            ])
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TranslationStatsWidget::class,
        ];
    }
}
