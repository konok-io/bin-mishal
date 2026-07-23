<?php

namespace App\Filament\Resources\LedgerEntryResource\Pages;

use App\Filament\Resources\LedgerEntryResource;
use App\Services\AccountingService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListLedgerEntries extends ListRecords
{
    protected static string $resource = LedgerEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('record_income')
                ->label('Record Income')
                ->icon('heroicon-o-arrow-down-left')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('entry_date')
                        ->default(now()),
                    \Filament\Forms\Components\Select::make('account_id')
                        ->label('Revenue Account')
                        ->relationship('account', 'name', fn($query) => 
                            $query->where('type', 'revenue')->active()
                        )
                        ->searchable()
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('SAR'),
                    \Filament\Forms\Components\TextInput::make('description')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('notes'),
                ])
                ->action(function (array $data) {
                    $service = app(AccountingService::class);
                    $service->recordManualIncome($data);
                    $this->notify('success', 'Income recorded successfully');
                }),
            Actions\Action::make('record_expense')
                ->label('Record Expense')
                ->icon('heroicon-o-arrow-up-right')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('entry_date')
                        ->default(now()),
                    \Filament\Forms\Components\Select::make('account_id')
                        ->label('Expense Account')
                        ->relationship('account', 'name', fn($query) => 
                            $query->where('type', 'expense')->active()
                        )
                        ->searchable()
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('SAR'),
                    \Filament\Forms\Components\TextInput::make('description')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('notes'),
                ])
                ->action(function (array $data) {
                    $service = app(AccountingService::class);
                    $service->recordManualExpense($data);
                    $this->notify('success', 'Expense recorded successfully');
                }),
            Actions\CreateAction::make()
                ->label('New Entry'),
        ];
    }

    protected function getHeaderTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All Entries')
                ->modifyQueryUsing(fn(Builder $query) => $query),
            Tab::make('revenue')
                ->label('Revenue')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('account', fn($q) => $q->where('type', 'revenue'))),
            Tab::make('expenses')
                ->label('Expenses')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('account', fn($q) => $q->where('type', 'expense'))),
            Tab::make('manual')
                ->label('Manual Entries')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('entry_type', ['manual_income', 'manual_expense'])),
        ];
    }
}
