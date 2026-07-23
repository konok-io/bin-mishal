<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number', 50)->unique();
            $table->date('entry_date');
            $table->enum('entry_type', [
                'booking_payment', 'cargo_payment', 'visa_payment', 
                'payroll', 'expense_reimbursement', 'expense_deduction',
                'manual_income', 'manual_expense', 'refund', 'adjustment'
            ]);
            $table->foreignId('account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->enum('transaction_type', ['debit', 'credit']);
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('SAR');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->decimal('amount_base', 14, 2); // Amount in base currency (SAR)
            $table->string('description');
            $table->string('reference_type')->nullable(); // booking, payroll, expense_claim, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->text('notes')->nullable();
            
            $table->index(['entry_date']);
            $table->index(['account_id', 'entry_date']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['branch_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
