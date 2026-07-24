<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(2)->get();
        $invoices = Invoice::take(3)->get();
        $users = User::take(2)->get();

        $payments = [
            [
                'payment_no' => 'PAY-240720001',
                'customer_id' => $customers->first()?->id,
                'invoice_id' => $invoices->first()?->id,
                'amount' => 2500.00,
                'currency' => 'SAR',
                'method' => 'bank_transfer',
                'transaction_id' => 'TXN123456789',
                'status' => 'completed',
                'paid_at' => now()->subDays(3),
                'created_by' => $users->first()?->id,
            ],
            [
                'payment_no' => 'PAY-240720002',
                'customer_id' => $customers->skip(1)->first()?->id,
                'invoice_id' => $invoices->skip(1)->first()?->id,
                'amount' => 500.00,
                'currency' => 'SAR',
                'method' => 'credit_card',
                'transaction_id' => 'TXN987654321',
                'status' => 'completed',
                'paid_at' => now()->subDays(5),
                'created_by' => $users->skip(1)->first()?->id,
            ],
            [
                'payment_no' => 'PAY-240720003',
                'customer_id' => $customers->first()?->id,
                'invoice_id' => $invoices->last()?->id,
                'amount' => 1000.00,
                'currency' => 'SAR',
                'method' => 'cash',
                'status' => 'pending',
                'created_by' => $users->first()?->id,
            ],
            [
                'payment_no' => 'PAY-240720004',
                'customer_id' => $customers->skip(1)->first()?->id,
                'invoice_id' => $invoices->first()?->id,
                'amount' => 2000.00,
                'currency' => 'SAR',
                'method' => 'online',
                'transaction_id' => 'TXN456789123',
                'status' => 'completed',
                'paid_at' => now()->subDays(1),
                'created_by' => $users->skip(1)->first()?->id,
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::updateOrCreate(
                ['payment_no' => $paymentData['payment_no']],
                $paymentData
            );
        }

        $this->command->info('PaymentSeeder: Created ' . count($payments) . ' sample payments.');
    }
}
