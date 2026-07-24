<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 1],
            ['code' => '1010', 'name' => 'Bank - Saudi National Bank', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 2],
            ['code' => '1020', 'name' => 'Bank - Al Rajhi', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 3],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 4],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit', 'sort_order' => 1],
            ['code' => '2010', 'name' => 'Salaries Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit', 'sort_order' => 2],
            ['code' => '2020', 'name' => 'VAT Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit', 'sort_order' => 3],
            ['code' => '3000', 'name' => 'Owner Capital', 'type' => 'equity', 'category' => 'owner_equity', 'normal_balance' => 'credit', 'sort_order' => 1],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'category' => 'owner_equity', 'normal_balance' => 'credit', 'sort_order' => 2],
            ['code' => '4000', 'name' => 'Flight Booking Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit', 'sort_order' => 1],
            ['code' => '4010', 'name' => 'Umrah Package Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit', 'sort_order' => 2],
            ['code' => '4020', 'name' => 'Visa Service Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit', 'sort_order' => 3],
            ['code' => '5000', 'name' => 'Salaries & Wages', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit', 'sort_order' => 1],
            ['code' => '5010', 'name' => 'Employee Benefits', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit', 'sort_order' => 2],
            ['code' => '5020', 'name' => 'Rent & Utilities', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit', 'sort_order' => 3],
            ['code' => '5030', 'name' => 'Marketing & Advertising', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit', 'sort_order' => 4],
        ];

        foreach ($accounts as $accountData) {
            ChartOfAccount::updateOrCreate(
                ['code' => $accountData['code']],
                [
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'category' => $accountData['category'],
                    'normal_balance' => $accountData['normal_balance'],
                    'is_active' => true,
                    'is_system' => true,
                    'sort_order' => $accountData['sort_order'],
                ]
            );
        }

        $this->command->info('ChartOfAccountSeeder: Created ' . count($accounts) . ' chart of accounts!');
    }
}
