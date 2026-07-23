<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NoticeResource\Pages;
use App\Models\Notice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Notices';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options(Notice::TYPES)
                            ->required()
                            ->default('info'),
                    ])->columns(1),

                Forms\Components\Section::make('Notice Content (Multilingual)')
                    ->schema([
                        Forms\Components\Tabs::make('Content')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('content.en')
                                            ->label('Notice Text')
                                            ->required()
                                            ->placeholder('Enter the scrolling notice text...'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('content.bn')
                                            ->label('Notice Text'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('content.ar')
                                            ->label('Notice Text'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Link (Optional)')
                    ->schema([
                        Forms\Components\TextInput::make('link_url')
                            ->label('Link URL')
                            ->url()
                            ->placeholder('https://'),
                        Forms\Components\Tabs::make('Link Text')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->schema([
                                        Forms\Components\TextInput::make('link_text.en')
                                            ->label('Link Text'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('বাংলা')
                                    ->schema([
                                        Forms\Components\TextInput::make('link_text.bn')
                                            ->label('Link Text'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('العربية')
                                    ->schema([
                                        Forms\Components\TextInput::make('link_text.ar')
                                            ->label('Link Text'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Scheduling')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Start Date')
                            ->nullable(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('End Date')
                            ->nullable()
                            ->afterOrEqual('start_date'),
                        Forms\Components\TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher priority notices appear first'),
                    ])->columns(3),

                Forms\Components\Section::make('Visibility')
                    ->schema([
                        Forms\Components\CheckboxList::make('visibility')
                            ->label('Show on Languages')
                            ->options([
                                'en' => 'English',
                                'bn' => 'বাংলা (Bengali)',
                                'ar' => 'العربية (Arabic)',
                            ])
                            ->columns(3),
                    ]),

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
                Tables\Columns\TextColumn::make('content')
                    ->label('Notice')
                    ->getStateUsing(fn($record) => $record->translated_content)
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'info' => 'info',
                        'warning' => 'warning',
                        'danger' => 'urgent',
                        'success' => 'success',
                    ]),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Starts')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Ends')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->defaultSort('priority', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Notice::TYPES),
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'edit' => Pages\EditNotice::route('/{record}/edit'),
        ];
    }
}
