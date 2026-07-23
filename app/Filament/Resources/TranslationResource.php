<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

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
                            ->columnSpanFull(),
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
                    ->limit(40),
                Tables\Columns\TextColumn::make('value_en')
                    ->label('English')
                    ->limit(40),
                Tables\Columns\TextColumn::make('value_ar')
                    ->label('Arabic')
                    ->limit(40),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete' => 'success',
                        'needs_review' => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn() => Translation::query()->distinct()->pluck('group', 'group'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'missing_bn' => 'Missing Bengali',
                        'missing_en' => 'Missing English',
                        'missing_ar' => 'Missing Arabic',
                        'needs_review' => 'Needs Review',
                    ]),
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
            ->headerActions([
                Tables\Actions\Action::make('sync')
                    ->label('Sync from Code')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn() => \Illuminate\Support\Facades\Artisan::call('translations:sync')),
            ]);
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
}
