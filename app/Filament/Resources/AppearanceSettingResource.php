<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AppearanceSettingResource\Pages;
use App\Models\AppearanceSetting;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class AppearanceSettingResource extends BaseResource
{
    protected static ?string $model = AppearanceSetting::class;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationLabel(): string
    {
        return 'Appearance Settings';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-paint-brush';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): string
    {
        return 'CMS';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Setting Details')
                    ->schema([
                        Schemas\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->unique(AppearanceSetting::class, 'key', ignoreRecord: true),
                        Schemas\Components\Select::make('section')
                            ->label('Section')
                            ->options(AppearanceSetting::SECTIONS)
                            ->required(),
                        Schemas\Components\TextInput::make('label')
                            ->label('Label')
                            ->required(),
                        Schemas\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'text' => 'Text',
                                'color' => 'Color',
                                'number' => 'Number',
                                'boolean' => 'Boolean/Toggle',
                                'textarea' => 'Textarea',
                                'select' => 'Select',
                                'file' => 'File/Image',
                            ])
                            ->default('text'),
                    ])->columns(2),

                Schemas\Components\Section::make('Value')
                    ->schema([
                        Schemas\Components\TextInput::make('value')
                            ->label('Value')
                            ->visible(fn($get) => in_array($get('type'), ['text', 'number', 'color'])),
                        Schemas\Components\Toggle::make('value')
                            ->label('Value')
                            ->visible(fn($get) => $get('type') === 'boolean'),
                        Schemas\Components\Textarea::make('value')
                            ->label('Value')
                            ->visible(fn($get) => in_array($get('type'), ['textarea'])),
                        Schemas\Components\Select::make('value')
                            ->label('Value')
                            ->options([
                                'primary' => 'Primary',
                                'secondary' => 'Secondary',
                                'success' => 'Success',
                                'danger' => 'Danger',
                                'warning' => 'Warning',
                                'info' => 'Info',
                            ])
                            ->visible(fn($get) => $get('type') === 'select'),
                        Schemas\Components\FileUpload::make('value')
                            ->label('File')
                            ->visible(fn($get) => $get('type') === 'file'),
                    ]),

                Schemas\Components\Section::make('Documentation')
                    ->schema([
                        Schemas\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->fontWeight('bold'),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('section')
                    ->label('Section')
                    ->colors([
                        'primary' => 'colors',
                        'success' => 'typography',
                        'info' => 'layout',
                        'warning' => 'buttons',
                        'danger' => 'cards',
                        'gray' => 'header',
                        'secondary' => 'footer',
                        'primary' => 'custom_css',
                        'success' => 'custom_js',
                    ]),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->defaultSort('section')
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->options(AppearanceSetting::SECTIONS),
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
            'index' => Pages\ListAppearanceSettings::route('/'),
            'create' => Pages\CreateAppearanceSetting::route('/create'),
            'edit' => Pages\EditAppearanceSetting::route('/{record}/edit'),
        ];
    }
}
