<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceReviewResource\Pages;
use App\Models\ServiceReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceReviewResource extends Resource
{
    protected static ?string $model = ServiceReview::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Service Reviews';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->schema([
                        Forms\Components\Select::make('service_type')
                            ->label('Service Type')
                            ->options([
                                'umrah' => 'Umrah',
                                'visa' => 'Visa',
                                'cargo' => 'Cargo',
                                'flight' => 'Flight',
                                'investor' => 'Investor',
                                'appointment' => 'Appointment',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('service_id')
                            ->label('Service ID')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ Poor',
                                2 => '⭐⭐ Fair',
                                3 => '⭐⭐⭐ Average',
                                4 => '⭐⭐⭐⭐ Good',
                                5 => '⭐⭐⭐⭐⭐ Excellent',
                            ])
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Customer Info')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name'),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email(),
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Review Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Review Title'),
                        Forms\Components\Textarea::make('content')
                            ->label('Review Content')
                            ->rows(4),
                    ]),
                
                Forms\Components\Section::make('Moderation')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved for Display')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('service_id')
                    ->label('Service ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'umrah' => 'Umrah',
                        'visa' => 'Visa',
                        'cargo' => 'Cargo',
                        'flight' => 'Flight',
                        'investor' => 'Investor',
                        'appointment' => 'Appointment',
                    ]),
                Tables\Filters\Filter::make('pending')
                    ->query(fn($query) => $query->where('is_approved', false))
                    ->label('Pending Approval'),
                Tables\Filters\Filter::make('approved')
                    ->query(fn($query) => $query->where('is_approved', true))
                    ->label('Approved Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(ServiceReview $record) => $record->update(['is_approved' => true]))
                    ->visible(fn(ServiceReview $record) => !$record->is_approved),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['is_approved' => true])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceReviews::route('/'),
            'view' => Pages\ViewServiceReview::route('/{record}'),
            'edit' => Pages\EditServiceReview::route('/{record}/edit'),
        ];
    }
}
