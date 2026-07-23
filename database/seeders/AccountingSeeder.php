<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Services\AccountingService;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app(AccountingService::class);
        $count = $service->initializeSystemAccounts();
        
        $this->command->info("Initialized {$count} system accounts!");
        
        // Create some manual accounts specific to travel business
        $manualAccounts = [
            ['code' => '1020', 'name' => 'Petty Cash', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 2],
            ['code' => '1110', 'name' => 'Prepaid Expenses', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit', 'sort_order' => 3],
            ['code' => '1200', 'name' => 'Equipment', 'type' => 'asset', 'category' => 'fixed_asset', 'normal_balance' => 'debit', 'sort_order' => 4],
            ['code' => '2020', 'name' => 'VAT Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit', 'sort_order' => 2],
            ['code' => '2030', 'name' => 'Employee Advances', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit', 'sort_order' => 3],
        ];

        foreach ($manualAccounts as $accountData) {
            ChartOfAccount::updateOrCreate(
                ['code' => $accountData['code']],
                [
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'category' => $accountData['category'],
                    'normal_balance' => $accountData['normal_balance'],
                    'is_system' => false,
                    'is_active' => true,
                    'sort_order' => $accountData['sort_order'],
                ]
            );
        }

        $this->command->info('Accounting seeder completed!');
    }
}
