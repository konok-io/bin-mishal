<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expense_type_id')->constrained()->cascadeOnDelete();
            $table->string('claim_number')->unique();
            $table->date('expense_date');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');
            $table->enum('payment_type', ['reimbursable', 'deductible']);
            $table->enum('status', [
                'draft', 
                'submitted', 
                'manager_review', 
                'hr_review', 
                'approved', 
                'rejected', 
                'paid',
                'applied_to_payroll'
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('applied_to_payroll_id')->nullable()->constrained('payrolls')->nullOnDelete();
            $table->date('applied_to_payroll_date')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'status']);
            $table->index(['status', 'expense_date']);
            $table->index(['applied_to_payroll_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};
