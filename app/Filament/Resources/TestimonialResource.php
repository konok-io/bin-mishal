<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Testimonials';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Person Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Forms\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Forms\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                        Forms\Components\TextInput::make('designation')
                            ->label('Designation (English)'),
                        Forms\Components\TextInput::make('designation_bn')
                            ->label('Designation (Bengali)'),
                        Forms\Components\TextInput::make('designation_ar')
                            ->label('Designation (Arabic)'),
                        Forms\Components\TextInput::make('company')
                            ->label('Company (English)'),
                        Forms\Components\TextInput::make('company_bn')
                            ->label('Company (Bengali)'),
                        Forms\Components\TextInput::make('company_ar')
                            ->label('Company (Arabic)'),
                    ])->columns(3),

                Forms\Components\Section::make('Review')
                    ->schema([
                        Forms\Components\Textarea::make('quote')
                            ->label('Quote (English)')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('quote_bn')
                            ->label('Quote (Bengali)')
                            ->rows(3),
                        Forms\Components\Textarea::make('quote_ar')
                            ->label('Quote (Arabic)')
                            ->rows(3),
                        Forms\Components\Slider::make('rating')
                            ->label('Rating')
                            ->min(1)
                            ->max(5)
                            ->default(5),
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Photo')
                            ->image(),
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Select::make('service_type')
                            ->label('Service Type')
                            ->options([
                                'flight' => 'Flight',
                                'umrah' => 'Umrah',
                                'visa' => 'Visa',
                                'cargo' => 'Cargo',
                                'investor' => 'Investor',
                                'general' => 'General',
                            ]),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),
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
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Photo'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('designation')
                    ->label('Designation'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state)),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
