<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\BookingType;
use App\Filament\Resources\BookingConfigurationResource\Pages;
use App\Models\BookingConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingConfigurationResource extends Resource
{
    protected static ?string $model = BookingConfiguration::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Booking';
    protected static ?string $navigationLabel = 'Service Config';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Service')
                    ->schema([
                        Forms\Components\Select::make('service_type')
                            ->label('Service')
                            ->options(BookingConfiguration::SERVICES)
                            ->required()
                            ->disabledOn('edit'),
                    ])->columns(1),

                Forms\Components\Section::make('Booking Types')
                    ->description('Select which booking types this service supports')
                    ->schema([
                        Forms\Components\CheckboxList::make('booking_types')
                            ->label('Enabled Booking Types')
                            ->options(collect(BookingType::cases())->mapWithKeys(fn($type) => [$type->value => $type->label()]))
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_enabled')
                            ->label('Enable Online Booking'),
                        Forms\Components\Toggle::make('requires_confirmation')
                            ->label('Require Admin Confirmation'),
                        Forms\Components\Toggle::make('allow_cancellation')
                            ->label('Allow Cancellation'),
                        Forms\Components\TextInput::make('cancellation_deadline_days')
                            ->label('Cancellation Deadline (Days)')
                            ->numeric()
                            ->default(7),
                    ])->columns(2),

                Forms\Components\Section::make('Quantity Limits')
                    ->schema([
                        Forms\Components\TextInput::make('min_quantity')
                            ->label('Minimum Quantity')
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('max_quantity')
                            ->label('Maximum Quantity')
                            ->numeric()
                            ->default(10),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'SAR' => 'SAR (Saudi Riyal)',
                                'BDT' => 'BDT (Bangladeshi Taka)',
                            ])
                            ->default('SAR'),
                        Forms\Components\Select::make('pricing_model')
                            ->label('Pricing Model')
                            ->options(BookingConfiguration::PRICING_MODELS)
                            ->default('fixed'),
                    ])->columns(2),

                Forms\Components\Section::make('Form Fields')
                    ->description('Configure which fields to show in the booking form')
                    ->schema([
                        Forms\Components\CheckboxList::make('form_fields_enabled')
                            ->label('Visible Fields')
                            ->options([
                                'name' => 'Full Name',
                                'email' => 'Email',
                                'phone' => 'Phone',
                                'nationality' => 'Nationality',
                                'passport_no' => 'Passport Number',
                                'passport_expiry' => 'Passport Expiry',
                                'date_of_birth' => 'Date of Birth',
                                'quantity' => 'Quantity',
                                'preferred_date' => 'Preferred Date',
                                'notes' => 'Notes',
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Service')
                    ->formatStateUsing(fn($state) => BookingConfiguration::SERVICES[$state] ?? $state),
                Tables\Columns\TextColumn::make('booking_types')
                    ->label('Booking Types')
                    ->formatStateUsing(fn($state) => is_array($state) ? count($state) . ' types' : 'None'),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('Enabled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('requires_confirmation')
                    ->label('Confirmation')
                    ->boolean(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingConfigurations::route('/'),
            'edit' => Pages\EditBookingConfiguration::route('/{record}/edit'),
        ];
    }
}
