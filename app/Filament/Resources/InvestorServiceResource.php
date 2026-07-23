<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvestorServiceResource\Pages;
use App\Models\InvestorService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvestorServiceResource extends Resource
{
    protected static ?string $model = InvestorService::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Investor';
    protected static ?string $navigationLabel = 'Services';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('service_key')
                            ->label('Service Key')
                            ->required()
                            ->unique(InvestorService::class, 'service_key', ignoreRecord: true),
                        Forms\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Forms\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Forms\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                    ])->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description (English)')
                            ->rows(3),
                        Forms\Components\Textarea::make('description_bn')
                            ->label('Description (Bengali)')
                            ->rows(3),
                        Forms\Components\Textarea::make('description_ar')
                            ->label('Description (Arabic)')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (FontAwesome class)')
                            ->placeholder('fas fa-chart-line'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color'),
                        Forms\Components\TextInput::make('processing_time')
                            ->label('Processing Time'),
                    ])->columns(3),

                Forms\Components\Section::make('Required Documents')
                    ->schema([
                        Forms\Components\KeyValue::make('required_documents')
                            ->label('Documents Checklist')
                            ->keyLabel('Document Name')
                            ->valueLabel('Description'),
                    ]),

                Forms\Components\Section::make('Fee Structure')
                    ->schema([
                        Forms\Components\KeyValue::make('fee_structure')
                            ->label('Fee Structure')
                            ->keyLabel('Fee Type')
                            ->valueLabel('Amount'),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_key')
                    ->label('Key')
                    ->badge(),
                Tables\Columns\TextColumn::make('processing_time')
                    ->label('Processing Time'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications'),
            ])
            ->defaultSort('sort_order')
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
            'index' => Pages\ListInvestorServices::route('/'),
            'create' => Pages\CreateInvestorService::route('/create'),
            'edit' => Pages\EditInvestorService::route('/{record}/edit'),
        ];
    }
}
