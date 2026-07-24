<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(3)->get();
        $users = User::take(2)->get();

        $invoices = [
            [
                'invoice_no' => 'INV-240720001',
                'customer_id' => $customers->first()?->id,
                'title' => 'Flight Booking Invoice',
                'description' => 'Round trip flight tickets',
                'subtotal' => 4000.00,
                'tax_rate' => 15.00,
                'tax_amount' => 600.00,
                'discount_amount' => 100.00,
                'total' => 4500.00,
                'paid_amount' => 4500.00,
                'balance' => 0.00,
                'status' => 'paid',
                'issue_date' => now()->subDays(5),
                'due_date' => now()->addDays(25),
                'created_by' => $users->first()?->id,
            ],
            [
                'invoice_no' => 'INV-240720002',
                'customer_id' => $customers->skip(1)->first()?->id,
                'title' => 'Visa Processing Fee',
                'description' => 'Saudi Arabia visa processing',
                'subtotal' => 800.00,
                'tax_rate' => 15.00,
                'tax_amount' => 120.00,
                'discount_amount' => 0.00,
                'total' => 920.00,
                'paid_amount' => 500.00,
                'balance' => 420.00,
                'status' => 'partial',
                'issue_date' => now()->subDays(10),
                'due_date' => now()->addDays(20),
                'created_by' => $users->skip(1)->first()?->id,
            ],
            [
                'invoice_no' => 'INV-240720003',
                'customer_id' => $customers->last()?->id,
                'title' => 'Umrah Package Booking',
                'description' => 'Premium Umrah package',
                'subtotal' => 18000.00,
                'tax_rate' => 15.00,
                'tax_amount' => 2700.00,
                'discount_amount' => 500.00,
                'total' => 20200.00,
                'paid_amount' => 20200.00,
                'balance' => 0.00,
                'status' => 'paid',
                'issue_date' => now()->subDays(15),
                'due_date' => now()->subDays(5),
                'created_by' => $users->first()?->id,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::updateOrCreate(
                ['invoice_no' => $invoiceData['invoice_no']],
                $invoiceData
            );
        }

        $this->command->info('InvoiceSeeder: Created ' . count($invoices) . ' sample invoices.');
    }
}
