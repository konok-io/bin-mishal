<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Models\SocialLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Social Links';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Platform')
                    ->schema([
                        Forms\Components\Select::make('platform')
                            ->label('Platform')
                            ->options(collect(SocialLink::PLATFORMS)->map(fn($p, $k) => $k)->flip()->toArray())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($set, $state) => self::updateFromPlatform($set, $state)),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (FontAwesome)')
                            ->placeholder('fab fa-facebook')
                            ->helperText('FontAwesome icon class'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Brand Color'),
                    ])->columns(3),

                Forms\Components\Section::make('Display Names (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Names')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.en')
                                            ->label('Name'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.bn')
                                            ->label('Name'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('name.ar')
                                            ->label('Name'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Link')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->placeholder('https://'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Show on Website')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    protected static function updateFromPlatform(callable $set, ?string $platform): void
    {
        if (!$platform || !isset(SocialLink::PLATFORMS[$platform])) {
            return;
        }

        $defaults = SocialLink::PLATFORMS[$platform];
        $set('icon', $defaults['icon'] ?? null);
        $set('color', $defaults['color'] ?? null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->getStateUsing(fn($record) => $record->translated_name),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible'),
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
            'index' => Pages\ListSocialLinks::route('/'),
            'create' => Pages\CreateSocialLink::route('/create'),
            'edit' => Pages\EditSocialLink::route('/{record}/edit'),
        ];
    }
}
