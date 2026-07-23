<?php

namespace App\Filament\Resources\ExpenseClaimResource\Pages;

use App\Filament\Resources\ExpenseClaimResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExpenseClaim extends ViewRecord
{
    protected static string $resource = ExpenseClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function () {
                    $result = ExpenseClaimResource::approveClaim($this->record);
                    $this->notify($result['success'] ? 'success' : 'danger', $result['message']);
                    return redirect()->back();
                })
                ->visible(fn() => $this->record->canBeApproved()),
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->required()
                        ->label('Rejection Reason'),
                ])
                ->action(function (array $data) {
                    $result = ExpenseClaimResource::rejectClaim($this->record, $data);
                    $this->notify($result['success'] ? 'success' : 'danger', $result['message']);
                    return redirect()->back();
                })
                ->visible(fn() => $this->record->canBeRejected()),
        ];
    }
}
