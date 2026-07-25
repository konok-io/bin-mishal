<?php

namespace App\Filament\Resources\ChartOfAccountResource\Pages;

use App\Filament\Resources\ChartOfAccountResource;
use App\Services\AccountingService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListChartOfAccounts extends ListRecords
{
    protected static string $resource = ChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Account'),
            Actions\Action::make('initialize_system_accounts')
                ->label('Initialize System Accounts')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning')
                ->action(function () {
                    $service = app(AccountingService::class);
                    $count = $service->initializeSystemAccounts();
                    $this->notify('success', "Initialized {$count} system accounts");
                }),
        ];
    }

    protected function getHeaderTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All')
                ->modifyQueryUsing(fn(Builder $query) => $query),
            Tab::make('assets')
                ->label('Assets')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'asset')),
            Tab::make('liabilities')
                ->label('Liabilities')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'liability')),
            Tab::make('equity')
                ->label('Equity')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'equity')),
            Tab::make('revenue')
                ->label('Revenue')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'revenue')),
            Tab::make('expenses')
                ->label('Expenses')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type', 'expense')),
        ];
    }
}
