<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InvestorServiceResource\Pages;
use App\Models\InvestorService;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class InvestorServiceResource extends BaseResource
{
    protected static ?string $model = InvestorService::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Basic Information')
                    ->schema([
                        Schemas\Components\TextInput::make('service_key')
                            ->label('Service Key')
                            ->required()
                            ->unique(InvestorService::class, 'service_key', ignoreRecord: true),
                        Schemas\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Schemas\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                    ])->columns(2),

                Schemas\Components\Section::make('Description')
                    ->schema([
                        Schemas\Components\Textarea::make('description')
                            ->label('Description (English)')
                            ->rows(3),
                        Schemas\Components\Textarea::make('description_bn')
                            ->label('Description (Bengali)')
                            ->rows(3),
                        Schemas\Components\Textarea::make('description_ar')
                            ->label('Description (Arabic)')
                            ->rows(3),
                    ]),

                Schemas\Components\Section::make('Display Settings')
                    ->schema([
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon (FontAwesome class)')
                            ->placeholder('fas fa-chart-line'),
                        Schemas\Components\ColorPicker::make('color')
                            ->label('Color'),
                        Schemas\Components\TextInput::make('processing_time')
                            ->label('Processing Time'),
                    ])->columns(3),

                Schemas\Components\Section::make('Required Documents')
                    ->schema([
                        Schemas\Components\KeyValue::make('required_documents')
                            ->label('Documents Checklist')
                            ->keyLabel('Document Name')
                            ->valueLabel('Description'),
                    ]),

                Schemas\Components\Section::make('Fee Structure')
                    ->schema([
                        Schemas\Components\KeyValue::make('fee_structure')
                            ->label('Fee Structure')
                            ->keyLabel('Fee Type')
                            ->valueLabel('Amount'),
                    ]),

                Schemas\Components\Section::make('Status')
                    ->schema([
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Schemas\Components\TextInput::make('sort_order')
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
