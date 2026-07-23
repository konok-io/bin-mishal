<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\HeroTabResource\Pages;
use App\Models\HeroTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroTabResource extends Resource
{
    protected static ?string $model = HeroTab::class;
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Hero Tabs';
    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tab Settings')
                    ->schema([
                        Forms\Components\Select::make('tab_key')
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
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->placeholder('fas fa-plane'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\Toggle::make('show_in_nav')
                            ->label('Show in Header Nav')
                            ->default(true),
                    ])->columns(4),

                Forms\Components\Section::make('Labels (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Labels')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('label.en')
                                            ->label('Tab Label')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('label.bn')
                                            ->label('Tab Label'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('label.ar')
                                            ->label('Tab Label'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Title (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Titles')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.en')
                                            ->label('Title')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.bn')
                                            ->label('Title'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('title.ar')
                                            ->label('Title'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Subtitle (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Subtitles')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('subtitle.en')
                                            ->label('Subtitle')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('subtitle.bn')
                                            ->label('Subtitle'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('subtitle.ar')
                                            ->label('Subtitle'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Description (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Descriptions')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.en')
                                            ->label('Description'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.bn')
                                            ->label('Description'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\Textarea::make('description.ar')
                                            ->label('Description'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Features (Multilingual)')
                    ->description('List of feature points shown in the hero section')
                    ->schema([
                        Forms\Components\Tabs::make('Features')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TagsInput::make('features.en')
                                            ->label('Features'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TagsInput::make('features.bn')
                                            ->label('Features'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TagsInput::make('features.ar')
                                            ->label('Features'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Hero Image')
                            ->image()
                            ->directory('hero')
                            ->visibility('public'),
                    ]),

                Forms\Components\Section::make('Button')
                    ->schema([
                        Forms\Components\Tabs::make('Button Text')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('button_text.en')
                                            ->label('Button Text')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('button_text.bn')
                                            ->label('Button Text'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('button_text.ar')
                                            ->label('Button Text'),
                                    ]),
                            ]),
                        Forms\Components\TextInput::make('button_url')
                            ->label('Button URL')
                            ->placeholder('/bn/services/umrah'),
                        Forms\Components\Select::make('route_name')
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

                Forms\Components\Section::make('Ordering')
                    ->schema([
                        Forms\Components\TextInput::make('order')
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
