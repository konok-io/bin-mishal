<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseClaimResource\Pages;
use App\Models\ExpenseClaim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\BadgeEntry;
use Illuminate\Database\Eloquent\Builder;

class ExpenseClaimResource extends Resource
{
    protected static ?string $model = ExpenseClaim::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'HR';
    protected static ?string $navigationLabel = 'Expense Claims';
    protected static ?int $navigationSort = 19;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('expense.manage'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Employee Information')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name', fn(Builder $query) => 
                                $query->where('status', 'active')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Expense Details')
                    ->schema([
                        Forms\Components\Select::make('expense_type_id')
                            ->relationship('expenseType', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('expense_date')
                            ->required()
                            ->maxDate(now()),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('SAR'),
                    ])->columns(3),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Review')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(ExpenseClaim::STATUSES)
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(2),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(2),
                    ])->visible(fn($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('claim_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expenseType.name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_type')
                    ->colors(fn($state) => match($state) {
                        'reimbursable' => 'success',
                        'deductible' => 'danger',
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(ExpenseClaim::STATUS_COLORS),
                Tables\Columns\TextColumn::make('reviewed_by.name')
                    ->toggleable()
                    ->label('Reviewed By'),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ExpenseClaim::STATUSES),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options(['reimbursable' => 'Reimbursable', 'deductible' => 'Deductible']),
                Tables\Filters\SelectFilter::make('expense_type_id')
                    ->relationship('expenseType', 'name'),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->relationship('employee', 'name'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('expense_date', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('expense_date', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(ExpenseClaim $record) => static::approveClaim($record))
                    ->visible(fn(ExpenseClaim $record) => $record->canBeApproved()),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->label('Rejection Reason'),
                    ])
                    ->action(fn(ExpenseClaim $record, array $data) => static::rejectClaim($record, $data))
                    ->visible(fn(ExpenseClaim $record) => $record->canBeRejected()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn(array $records) => static::bulkApprove($records))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('bulk_reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn(array $records) => static::bulkReject($records))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Claim Information')
                    ->schema([
                        TextEntry::make('claim_number'),
                        BadgeEntry::make('status')
                            ->colors(ExpenseClaim::STATUS_COLORS),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])->columns(3),

                Section::make('Employee & Expense')
                    ->schema([
                        TextEntry::make('employee.name'),
                        TextEntry::make('expenseType.name'),
                        TextEntry::make('expense_date')
                            ->date(),
                        TextEntry::make('amount')
                            ->money('SAR'),
                        BadgeEntry::make('payment_type'),
                    ])->columns(3),

                Section::make('Details')
                    ->schema([
                        TextEntry::make('description'),
                    ]),

                Section::make('Review Information')
                    ->schema([
                        TextEntry::make('reviewed_by.name'),
                        TextEntry::make('reviewed_at')
                            ->dateTime(),
                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason'),
                        TextEntry::make('admin_notes'),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenseClaims::route('/'),
            'view' => Pages\ViewExpenseClaim::route('/{record}'),
            'edit' => Pages\EditExpenseClaim::route('/{record}/edit'),
        ];
    }

    public static function approveClaim(ExpenseClaim $record): array
    {
        $service = app(\App\Services\ExpenseService::class);
        $user = auth()->user();
        
        if ($service->approveClaim($record, $user)) {
            return ['success' => true, 'message' => 'Claim approved successfully'];
        }
        
        return ['success' => false, 'message' => 'Cannot approve this claim'];
    }

    public static function rejectClaim(ExpenseClaim $record, array $data): array
    {
        $service = app(\App\Services\ExpenseService::class);
        $user = auth()->user();
        
        if ($service->rejectClaim($record, $user, $data['reason'])) {
            return ['success' => true, 'message' => 'Claim rejected'];
        }
        
        return ['success' => false, 'message' => 'Cannot reject this claim'];
    }

    public static function bulkApprove(array $records): array
    {
        $service = app(\App\Services\ExpenseService::class);
        $user = auth()->user();
        
        $results = $service->bulkApprove($records, $user);
        
        return [
            'success' => true,
            'message' => "Approved " . count($results['success']) . " claims",
        ];
    }

    public static function bulkReject(array $records): array
    {
        return ['success' => false, 'message' => 'Bulk reject not implemented yet'];
    }
}
