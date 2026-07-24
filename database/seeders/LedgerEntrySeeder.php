<?php

namespace Database\Seeders;

use App\Models\LedgerEntry;
use App\Models\ChartOfAccount;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LedgerEntrySeeder extends Seeder
{
    public function run(): void
    {
        $cashAccount = ChartOfAccount::where('code', '1000')->first();
        $revenueAccount = ChartOfAccount::where('code', '4000')->first();
        $expenseAccount = ChartOfAccount::where('code', '5000')->first();
        $processor = User::first();
        $branch = Branch::first();

        if (!$cashAccount || !$revenueAccount) {
            $this->command->info('LedgerEntrySeeder: ChartOfAccountSeeder must be run first!');
            return;
        }

        $entries = [
            [
                'entry_type' => 'booking_payment',
                'account_id' => $cashAccount->id,
                'transaction_type' => 'debit',
                'amount' => 5000,
                'currency' => 'SAR',
                'exchange_rate' => 1,
                'amount_base' => 5000,
                'description' => 'Payment received for flight booking #FB001',
                'entry_date' => Carbon::today()->subDays(10),
            ],
            [
                'entry_type' => 'booking_payment',
                'account_id' => $revenueAccount->id,
                'transaction_type' => 'credit',
                'amount' => 5000,
                'currency' => 'SAR',
                'exchange_rate' => 1,
                'amount_base' => 5000,
                'description' => 'Revenue recognized for flight booking #FB001',
                'entry_date' => Carbon::today()->subDays(10),
            ],
        ];

        foreach ($entries as $entryData) {
            $entryNumber = 'LED-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            LedgerEntry::updateOrCreate(
                [
                    'entry_number' => $entryNumber,
                    'account_id' => $entryData['account_id'],
                    'entry_date' => $entryData['entry_date']->format('Y-m-d'),
                ],
                [
                    'entry_number' => $entryNumber,
                    'entry_date' => $entryData['entry_date']->format('Y-m-d'),
                    'entry_type' => $entryData['entry_type'],
                    'transaction_type' => $entryData['transaction_type'],
                    'amount' => $entryData['amount'],
                    'currency' => $entryData['currency'],
                    'exchange_rate' => $entryData['exchange_rate'],
                    'amount_base' => $entryData['amount_base'],
                    'description' => $entryData['description'],
                    'branch_id' => $branch ? $branch->id : null,
                    'created_by' => $processor ? $processor->id : 1,
                    'notes' => null,
                ]
            );
        }

        $this->command->info('LedgerEntrySeeder: Created sample ledger entries!');
    }
}
