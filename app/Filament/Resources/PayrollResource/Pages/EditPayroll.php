<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayroll extends EditRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-calculate gross salary if not set
        if (!isset($data['gross_salary'])) {
            $data['gross_salary'] = 
                ($data['basic_salary'] ?? 0) +
                ($data['housing_allowance'] ?? 0) +
                ($data['transport_allowance'] ?? 0) +
                ($data['food_allowance'] ?? 0) +
                ($data['other_allowance'] ?? 0) +
                ($data['overtime_amount'] ?? 0) +
                ($data['bonus'] ?? 0);
        }

        // Auto-calculate total deductions if not set
        if (!isset($data['total_deductions'])) {
            $data['total_deductions'] = 
                ($data['gosi_deduction'] ?? 0) +
                ($data['health_insurance'] ?? 0) +
                ($data['absence_deduction'] ?? 0) +
                ($data['loan_deduction'] ?? 0) +
                ($data['other_deduction'] ?? 0);
        }

        // Auto-calculate net salary
        $data['net_salary'] = ($data['gross_salary'] ?? 0) - ($data['total_deductions'] ?? 0);

        return $data;
    }
}
