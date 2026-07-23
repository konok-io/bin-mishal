<?php

namespace App\Filament\Resources\ExpenseClaimResource\Pages;

use App\Filament\Resources\ExpenseClaimResource;
use Filament\Resources\Pages\EditRecord;

class EditExpenseClaim extends EditRecord
{
    protected static string $resource = ExpenseClaimResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
