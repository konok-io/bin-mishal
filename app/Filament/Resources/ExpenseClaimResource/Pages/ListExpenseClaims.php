<?php

namespace App\Filament\Resources\ExpenseClaimResource\Pages;

use App\Filament\Resources\ExpenseClaimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListExpenseClaims extends ListRecords
{
    protected static string $resource = ExpenseClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Claim'),
            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    // TODO: Implement CSV export
                }),
        ];
    }

    protected function getHeaderTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All')
                ->modifyQueryUsing(fn(Builder $query) => $query),
            Tab::make('pending')
                ->label('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->pending()),
            Tab::make('approved')
                ->label('Approved')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'approved')),
            Tab::make('applied')
                ->label('Applied to Payroll')
                ->modifyQueryUsing(fn(Builder $query) => $query->appliedToPayroll()),
            Tab::make('rejected')
                ->label('Rejected')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}
