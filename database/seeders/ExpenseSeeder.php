<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Travel Expenses',
                'slug' => 'travel',
                'description' => 'Flights, accommodation, and other travel-related expenses',
                'category' => 'travel',
                'payment_type' => 'reimbursable',
                'max_amount' => 5000.00,
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Food & Meals',
                'slug' => 'food',
                'description' => 'Business meals and food expenses during work travel',
                'category' => 'food',
                'payment_type' => 'reimbursable',
                'max_amount' => 500.00,
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Transport & Fuel',
                'slug' => 'transport',
                'description' => 'Local transport, taxi, fuel, and parking expenses',
                'category' => 'transport',
                'payment_type' => 'reimbursable',
                'max_amount' => 1000.00,
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Office Supplies',
                'slug' => 'supplies',
                'description' => 'Stationery, printer ink, and other office supplies',
                'category' => 'equipment',
                'payment_type' => 'reimbursable',
                'max_amount' => 500.00,
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Equipment Purchase',
                'slug' => 'equipment',
                'description' => 'Computers, phones, and other equipment for work',
                'category' => 'equipment',
                'payment_type' => 'reimbursable',
                'max_amount' => 5000.00,
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 2,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Phone & Internet',
                'slug' => 'communication',
                'description' => 'Mobile bills, internet charges, and communication expenses',
                'category' => 'communication',
                'payment_type' => 'both',
                'max_amount' => 300.00,
                'requires_receipt' => false,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Cash Advance Return',
                'slug' => 'cash-return',
                'description' => 'Unreturned cash advances to be deducted from salary',
                'category' => 'other',
                'payment_type' => 'deductible',
                'requires_receipt' => false,
                'requires_approval' => true,
                'approval_level' => 2,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Company Asset Damage',
                'slug' => 'damage',
                'description' => 'Deduction for damaged company property',
                'category' => 'other',
                'payment_type' => 'deductible',
                'requires_receipt' => false,
                'requires_approval' => true,
                'approval_level' => 2,
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Uniform/Library Fine',
                'slug' => 'fine',
                'description' => 'Uniform fees, library fines, or other deductions',
                'category' => 'other',
                'payment_type' => 'deductible',
                'requires_receipt' => false,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Other Expenses',
                'slug' => 'other',
                'description' => 'Miscellaneous work-related expenses',
                'category' => 'other',
                'payment_type' => 'both',
                'requires_receipt' => true,
                'requires_approval' => true,
                'approval_level' => 1,
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($types as $typeData) {
            ExpenseType::updateOrCreate(
                ['slug' => $typeData['slug']],
                $typeData
            );
        }

        $this->command->info('Expense types seeded successfully!');
    }
}
