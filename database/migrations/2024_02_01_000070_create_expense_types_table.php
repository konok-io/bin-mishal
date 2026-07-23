<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['travel', 'food', 'transport', 'equipment', 'communication', 'other']);
            $table->enum('payment_type', ['reimbursable', 'deductible', 'both']);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->boolean('requires_receipt')->default(true);
            $table->boolean('requires_approval')->default(true);
            $table->integer('approval_level')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_types');
    }
};
