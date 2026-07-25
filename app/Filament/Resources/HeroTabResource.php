<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\HeroTabResource\Pages;
use App\Models\HeroTab;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class HeroTabResource extends BaseResource
{
    protected static ?string $model = HeroTab::class;

    public static function getNavigationLabel(): string
    {
        return 'Hero Tab';
    }

    public static function getNavigationGroup(): string
    {
        return 'Homepage';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Tab Settings')
                    ->schema([
                        Schemas\Components\Select::make('tab_key')
                            ->label('Tab Key')
                            ->options([
                                'flight' => 'Flight',
                                'umrah' => 'Umrah',
                                'visa' => 'Visa',
                                'cargo' => 'Cargo',
                                'appointment' => 'Appointment',
                                'investor' => 'Investor',
                            ])
                            ->required()
                            ->unique(HeroTab::class, 'tab_key', ignoreRecord: true),
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->placeholder('fas fa-plane'),
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Schemas\Components\Toggle::make('show_in_nav')
                            ->label('Show in Header Nav')
                            ->default(true),
                    ])->columns(4),

                Schemas\Components\Section::make('Labels (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Labels')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('label.en')
                                            ->label('Tab Label')
                                            ->required(),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('label.bn')
                                            ->label('Tab Label'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('label.ar')
                                            ->label('Tab Label'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Title (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Titles')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.en')
                                            ->label('Title')
                                            ->required(),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.bn')
                                            ->label('Title'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title.ar')
                                            ->label('Title'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Subtitle (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Subtitles')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('subtitle.en')
                                            ->label('Subtitle')
                                            ->required(),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('subtitle.bn')
                                            ->label('Subtitle'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('subtitle.ar')
                                            ->label('Subtitle'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Description (Multilingual)')
                    ->schema([
                        Schemas\Components\Tabs::make('Descriptions')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.en')
                                            ->label('Description'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.bn')
                                            ->label('Description'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\Textarea::make('description.ar')
                                            ->label('Description'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Features (Multilingual)')
                    ->description('List of feature points shown in the hero section')
                    ->schema([
                        Schemas\Components\Tabs::make('Features')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TagsInput::make('features.en')
                                            ->label('Features'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TagsInput::make('features.bn')
                                            ->label('Features'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TagsInput::make('features.ar')
                                            ->label('Features'),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Image')
                    ->schema([
                        Schemas\Components\FileUpload::make('image')
                            ->label('Hero Image')
                            ->image()
                            ->directory('hero')
                            ->visibility('public'),
                    ]),

                Schemas\Components\Section::make('Button')
                    ->schema([
                        Schemas\Components\Tabs::make('Button Text')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Schemas\Components\TextInput::make('button_text.en')
                                            ->label('Button Text')
                                            ->required(),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Schemas\Components\TextInput::make('button_text.bn')
                                            ->label('Button Text'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Schemas\Components\TextInput::make('button_text.ar')
                                            ->label('Button Text'),
                                    ]),
                            ]),
                        Schemas\Components\TextInput::make('button_url')
                            ->label('Button URL')
                            ->placeholder('/bn/services/umrah'),
                        Schemas\Components\Select::make('route_name')
                            ->label('Or Link to Route')
                            ->options([
                                'services.umrah' => 'Umrah Packages',
                                'services.visa' => 'Visa Services',
                                'services.airticket' => 'Air Ticket',
                                'cargo' => 'Cargo',
                                'appointment' => 'Appointment',
                                'investor' => 'Investor Services',
                            ])
                            ->nullable(),
                    ]),

                Schemas\Components\Section::make('Ordering')
                    ->schema([
                        Schemas\Components\TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tab_key')
                    ->label('Tab')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->getStateUsing(fn($record) => $record->translated_label),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_in_nav')
                    ->label('In Nav')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('show_in_nav'),
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
            'index' => Pages\ListHeroTabs::route('/'),
            'create' => Pages\CreateHeroTab::route('/create'),
            'edit' => Pages\EditHeroTab::route('/{record}/edit'),
        ];
    }
}
