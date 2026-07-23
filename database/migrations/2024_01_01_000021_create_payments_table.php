<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            $table->string('invoice_id')->nullable();
            $table->string('booking_id')->nullable();
            
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('SAR');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            
            $table->enum('method', ['bank_transfer', 'credit_card', 'debit_card', 'cash', 'check', 'sadad', 'mada', 'apple_pay', 'wallet'])
                  ->default('bank_transfer');
            
            $table->string('transaction_id')->nullable();
            $table->string('gateway_response')->nullable();
            
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])
                  ->default('pending');
            
            $table->string('receipt_file')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            $table->index('payment_no');
            $table->index('status');
            $table->index('customer_id');
            $table->index('method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
