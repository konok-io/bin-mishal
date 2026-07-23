<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting Details')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->unique(Setting::class, 'key', ignoreRecord: true),
                        Forms\Components\TextInput::make('group')
                            ->label('Group')
                            ->required(),
                        Forms\Components\TextInput::make('label')
                            ->label('Label'),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'text' => 'Text',
                                'number' => 'Number',
                                'boolean' => 'Boolean/Toggle',
                                'json' => 'JSON',
                                'file' => 'File',
                            ])
                            ->default('text'),
                    ])->columns(2),

                Forms\Components\Section::make('Value')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->visible(fn($get) => !in_array($get('type'), ['boolean', 'json', 'file'])),
                        Forms\Components\Toggle::make('value')
                            ->label('Value')
                            ->visible(fn($get) => $get('type') === 'boolean'),
                        Forms\Components\Textarea::make('value')
                            ->label('JSON Value')
                            ->visible(fn($get) => $get('type') === 'json'),
                        Forms\Components\FileUpload::make('value')
                            ->label('File')
                            ->visible(fn($get) => $get('type') === 'file'),
                    ]),

                Forms\Components\Section::make('Documentation')
                    ->schema([
                        Forms\Components\Textarea::make('description')
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
                        'general' => 'General',
                        'appearance' => 'Appearance',
                        'seo' => 'SEO',
                        'social' => 'Social',
                        'booking' => 'Booking',
                        'email' => 'Email',
                        'integrations' => 'Integrations',
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
