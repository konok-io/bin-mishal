<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('category', [
                'current_asset', 'fixed_asset', 'current_liability', 
                'long_term_liability', 'owner_equity', 'operating_revenue',
                'non_operating_revenue', 'operating_expense', 'non_operating_expense'
            ]);
            $table->enum('normal_balance', ['debit', 'credit'])->default('debit');
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System-created accounts
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['type', 'category']);
            $table->index(['parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
