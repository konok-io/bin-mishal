<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LedgerEntryResource\Pages;
use App\Models\LedgerEntry;
use App\Services\AccountingService;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;

class LedgerEntryResource extends BaseResource
{
    protected static ?string $model = LedgerEntry::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'finance']) || $user->can('accounting.manage'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Entry Details')
                    ->schema([
                        Schemas\Components\DatePicker::make('entry_date')
                            ->required()
                            ->default(now()),
                        Schemas\Components\Select::make('entry_type')
                            ->options(LedgerEntry::ENTRY_TYPES)
                            ->required(),
                    ])->columns(2),

                Schemas\Components\Section::make('Account & Amount')
                    ->schema([
                        Schemas\Components\Select::make('account_id')
                            ->relationship('account', 'name', fn($query) => $query->active())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Schemas\Components\Select::make('transaction_type')
                            ->options(LedgerEntry::TRANSACTION_TYPES)
                            ->required(),
                        Schemas\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('SAR'),
                    ])->columns(3),

                Schemas\Components\Section::make('Details')
                    ->schema([
                        Schemas\Components\TextInput::make('description')
                            ->required()
                            ->maxLength(500),
                        Schemas\Components\Textarea::make('notes')
                            ->rows(2),
                    ]),

                Schemas\Components\Section::make('Reference (Optional)')
                    ->schema([
                        Schemas\Components\Select::make('reference_type')
                            ->options([
                                'booking' => 'Booking',
                                'cargo' => 'Cargo',
                                'visa' => 'Visa',
                                'payroll' => 'Payroll',
                                'expense' => 'Expense',
                            ])
                            ->reactive(),
                        Schemas\Components\TextInput::make('reference_id')
                            ->numeric()
                            ->visible(fn($get) => $get('reference_type') !== null),
                        Schemas\Components\Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.code')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('account.name')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('transaction_type')
                    ->colors([
                        'success' => 'credit',
                        'danger' => 'debit',
                    ]),
                Tables\Columns\TextColumn::make('amount')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
            ])
            ->defaultSort('entry_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('entry_type')
                    ->options(LedgerEntry::ENTRY_TYPES),
                Tables\Filters\SelectFilter::make('account_id')
                    ->relationship('account', 'name'),
                Tables\Filters\SelectFilter::make('transaction_type')
                    ->options(LedgerEntry::TRANSACTION_TYPES),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Schemas\Components\DatePicker::make('from'),
                        Schemas\Components\DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('entry_date', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('entry_date', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Entry Information')
                    ->schema([
                        TextEntry::make('entry_number'),
                        TextEntry::make('entry_date')
                            ->date(),
                        TextEntry::make('entry_type')
                            ->badge(),
                    ])->columns(3),

                Section::make('Account & Transaction')
                    ->schema([
                        TextEntry::make('account.code'),
                        TextEntry::make('account.name'),
                        TextEntry::make('transaction_type')
                            ->badge(),
                        TextEntry::make('amount')
                            ->money('SAR'),
                    ])->columns(2),

                Section::make('Details')
                    ->schema([
                        TextEntry::make('description'),
                        TextEntry::make('notes'),
                    ]),

                Section::make('Reference')
                    ->schema([
                        TextEntry::make('reference_type'),
                        TextEntry::make('reference_id'),
                        TextEntry::make('branch.name'),
                    ])->columns(3),

                Section::make('Audit')
                    ->schema([
                        TextEntry::make('creator.name'),
                        TextEntry::make('created_at')
                            ->datetime(),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLedgerEntries::route('/'),
            'view' => Pages\ViewLedgerEntry::route('/{record}'),
            'create' => Pages\CreateLedgerEntry::route('/create'),
        ];
    }
}
