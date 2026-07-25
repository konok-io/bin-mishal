<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SocialLinkResource\Pages;
use App\Models\SocialLink;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class SocialLinkResource extends BaseResource
{
    protected static ?string $model = SocialLink::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Platform')
                    ->schema([
                        Schemas\Components\Select::make('platform')
                            ->label('Platform')
                            ->options(collect(SocialLink::PLATFORMS)->map(fn($p, $k) => $k)->flip()->toArray())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($set, $state) => self::updateFromPlatform($set, $state)),
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon (FontAwesome)')
                            ->placeholder('fab fa-facebook')
                            ->helperText('FontAwesome icon class'),
                        Schemas\Components\ColorPicker::make('color')
                            ->label('Brand Color'),
                    ])->columns(3),

                Schemas\Components\Section::make('Display Names (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Names')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('name.en')
                                            ->label('Name'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('name.bn')
                                            ->label('Name'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('name.ar')
                                            ->label('Name'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Link')
                    ->schema([
                        Schemas\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->placeholder('https://'),
                    ]),

                Schemas\Components\Section::make('Settings')
                    ->schema([
                        Schemas\Components\TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Schemas\Components\Toggle::make('is_visible')
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
                Tables\Actions\Action::make('toggleVisibility')
                    ->label('')
                    ->icon(fn($record) => $record->is_visible ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn($record) => $record->is_visible ? 'success' : 'gray')
                    ->action(function (SocialLink $record) {
                        $record->is_visible = !$record->is_visible;
                        $record->save();
                    })
                    ->tooltip(fn($record) => $record->is_visible ? 'Hide from website' : 'Show on website'),
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
