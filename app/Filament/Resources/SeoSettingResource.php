<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SeoSettingResource\Pages;
use App\Models\SeoSetting;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class SeoSettingResource extends BaseResource
{
    protected static ?string $model = SeoSetting::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationLabel(): string
    {
        return 'SEO Settings';
    }

    public static function getNavigationGroup(): string
    {
        return 'CMS';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Page Configuration')
                    ->schema([
                        Schemas\Components\Select::make('page')
                            ->label('Page')
                            ->options(SeoSetting::PAGES)
                            ->required(),
                        Schemas\Components\Select::make('locale')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'bn' => 'Bengali',
                                'ar' => 'Arabic',
                            ])
                            ->default('en'),
                    ])->columns(2),

                Schemas\Components\Section::make('Meta Tags')
                    ->schema([
                        Schemas\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(70),
                        Schemas\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160),
                        Schemas\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords'),
                    ])->columns(1),

                Schemas\Components\Section::make('Open Graph')
                    ->schema([
                        Schemas\Components\TextInput::make('og_title')
                            ->label('OG Title'),
                        Schemas\Components\Textarea::make('og_description')
                            ->label('OG Description'),
                        Schemas\Components\FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image(),
                    ])->columns(1),

                Schemas\Components\Section::make('Advanced')
                    ->schema([
                        Schemas\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL'),
                        Schemas\Components\Select::make('robots')
                            ->label('Robots')
                            ->options([
                                SeoSetting::ROBOTS_INDEX_FOLLOW => 'Index, Follow',
                                SeoSetting::ROBOTS_NOINDEX_FOLLOW => 'Noindex, Follow',
                                SeoSetting::ROBOTS_INDEX_NOFOLLOW => 'Index, Nofollow',
                                SeoSetting::ROBOTS_NOINDEX_NOFOLLOW => 'Noindex, Nofollow',
                            ]),
                        Schemas\Components\Textarea::make('schema_markup')
                            ->label('Schema Markup (JSON-LD)')
                            ->rows(5),
                    ])->columns(2),

                Schemas\Components\Section::make('Status')
                    ->schema([
                        Schemas\Components\Toggle::make('is_active')
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
