<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\InvestorApplicationStatus;
use App\Filament\Resources\InvestorApplicationResource\Pages;
use App\Models\InvestorApplication;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class InvestorApplicationResource extends BaseResource
{
    protected static ?string $model = InvestorApplication::class;

    public static function getNavigationLabel(): string
    {
        return 'Investor Application';
    }

    public static function getNavigationGroup(): string
    {
        return 'CRM';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Application Info')
                    ->schema([
                        Schemas\Components\TextInput::make('application_no')
                            ->label('Application No')
                            ->disabled(),
                        Schemas\Components\Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'name')
                            ->required(),
                        Schemas\Components\Select::make('status')
                            ->label('Status')
                            ->options(collect(InvestorApplicationStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])),
                    ])->columns(3),

                Schemas\Components\Section::make('Applicant Information')
                    ->schema([
                        Schemas\Components\TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),
                        Schemas\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        Schemas\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->required(),
                        Schemas\Components\TextInput::make('company_name')
                            ->label('Company Name'),
                        Schemas\Components\TextInput::make('nationality')
                            ->label('Nationality'),
                        Schemas\Components\TextInput::make('passport_no')
                            ->label('Passport No'),
                    ])->columns(2),

                Schemas\Components\Section::make('Investment Details')
                    ->schema([
                        Schemas\Components\Select::make('investment_range')
                            ->label('Investment Range')
                            ->options([
                                'under_1m' => 'Under SAR 1 Million',
                                '1m_10m' => 'SAR 1 - 10 Million',
                                '10m_50m' => 'SAR 10 - 50 Million',
                                'over_50m' => 'Over SAR 50 Million',
                            ]),
                        Schemas\Components\TextInput::make('investment_amount')
                            ->label('Investment Amount')
                            ->numeric()
                            ->prefix('SAR'),
                    ])->columns(2),

                Schemas\Components\Section::make('Review')
                    ->schema([
                        Schemas\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->relationship('assignedTo', 'name')
                            ->searchable(),
                        Schemas\Components\Textarea::make('status_notes')
                            ->label('Status Notes')
                            ->rows(3),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application_no')
                    ->label('Application No')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Applicant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state?->label() ?? $state)
                    ->colors([
                        'gray' => 'submitted',
                        'warning' => 'under_review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(InvestorApplicationStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])),
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(InvestorApplication $record) => $record->approve())
                    ->visible(fn($record) => $record->status === InvestorApplicationStatus::UNDER_REVIEW),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->form([
                        Schemas\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(fn(InvestorApplication $record, array $data) => $record->reject($data['reason']))
                    ->visible(fn($record) => $record->status === InvestorApplicationStatus::UNDER_REVIEW),
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
            'index' => Pages\ListInvestorApplications::route('/'),
            'view' => Pages\ViewInvestorApplication::route('/{record}'),
            'edit' => Pages\EditInvestorApplication::route('/{record}/edit'),
        ];
    }
}
