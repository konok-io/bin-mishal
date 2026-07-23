<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SeoSettingResource\Pages;
use App\Models\SeoSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoSettingResource extends Resource
{
    protected static ?string $model = SeoSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'SEO Manager';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Configuration')
                    ->schema([
                        Forms\Components\Select::make('page')
                            ->label('Page')
                            ->options(SeoSetting::PAGES)
                            ->required(),
                        Forms\Components\Select::make('locale')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'bn' => 'Bengali',
                                'ar' => 'Arabic',
                            ])
                            ->default('en'),
                    ])->columns(2),

                Forms\Components\Section::make('Meta Tags')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(70),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords'),
                    ])->columns(1),

                Forms\Components\Section::make('Open Graph')
                    ->schema([
                        Forms\Components\TextInput::make('og_title')
                            ->label('OG Title'),
                        Forms\Components\Textarea::make('og_description')
                            ->label('OG Description'),
                        Forms\Components\FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image(),
                    ])->columns(1),

                Forms\Components\Section::make('Advanced')
                    ->schema([
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL'),
                        Forms\Components\Select::make('robots')
                            ->label('Robots')
                            ->options([
                                SeoSetting::ROBOTS_INDEX_FOLLOW => 'Index, Follow',
                                SeoSetting::ROBOTS_NOINDEX_FOLLOW => 'Noindex, Follow',
                                SeoSetting::ROBOTS_INDEX_NOFOLLOW => 'Index, Nofollow',
                                SeoSetting::ROBOTS_NOINDEX_NOFOLLOW => 'Noindex, Nofollow',
                            ]),
                        Forms\Components\Textarea::make('schema_markup')
                            ->label('Schema Markup (JSON-LD)')
                            ->rows(5),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
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
                Tables\Columns\BadgeColumn::make('page')
                    ->label('Page')
                    ->formatStateUsing(fn($state) => SeoSetting::PAGES[$state] ?? $state),
                Tables\Columns\BadgeColumn::make('locale')
                    ->label('Lang'),
                Tables\Columns\TextColumn::make('meta_title')
                    ->label('Title')
                    ->limit(40),
                Tables\Columns\TextColumn::make('meta_description')
                    ->label('Description')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            'index' => Pages\ListSeoSettings::route('/'),
            'create' => Pages\CreateSeoSetting::route('/create'),
            'edit' => Pages\EditSeoSetting::route('/{record}/edit'),
        ];
    }
}
