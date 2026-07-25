<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class SettingResource extends BaseResource
{
    protected static ?string $model = Setting::class;
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Global Settings';
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
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
                            ->unique(Setting::class, 'key', ignoreRecord: true),
                        Schemas\Components\TextInput::make('group')
                            ->label('Group')
                            ->required(),
                        Schemas\Components\TextInput::make('label')
                            ->label('Label'),
                        Schemas\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'text' => 'Text',
                                'number' => 'Number',
                                'boolean' => 'Boolean/Toggle',
                                'textarea' => 'Textarea',
                                'json' => 'JSON',
                                'file' => 'File',
                                'select' => 'Select',
                            ])
                            ->default('text'),
                    ])->columns(2),

                Schemas\Components\Section::make('Value')
                    ->schema([
                        Schemas\Components\TextInput::make('value')
                            ->label('Value')
                            ->visible(fn($get) => in_array($get('type'), ['text', 'number'])),
                        Schemas\Components\Toggle::make('value')
                            ->label('Value')
                            ->visible(fn($get) => $get('type') === 'boolean'),
                        Schemas\Components\Textarea::make('value')
                            ->label('Value')
                            ->visible(fn($get) => in_array($get('type'), ['textarea', 'json'])),
                        Schemas\Components\FileUpload::make('value')
                            ->label('File')
                            ->visible(fn($get) => in_array($get('type'), ['file', 'select'])),
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
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'app' => 'App Settings',
                        'contact' => 'Contact Info',
                        'header' => 'Header Settings',
                        'footer' => 'Footer Settings',
                        'social' => 'Social Links',
                        'hero_home' => 'Hero Section (Home)',
                        'about' => 'About Section',
                        'services' => 'Services Section',
                        'contact_page' => 'Contact Page',
                        'business' => 'Business Settings',
                        'booking' => 'Booking Settings',
                        'invoice' => 'Invoice Settings',
                        'system' => 'System Settings',
                        'widgets' => 'Widgets',
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
