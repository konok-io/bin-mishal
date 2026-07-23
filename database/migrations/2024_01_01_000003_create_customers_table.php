<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_code')->unique();
            $table->string('company_name')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_id_no')->nullable();
            $table->string('profession')->nullable();
            $table->string('work_city')->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();
            
            $table->enum('source', ['walk_in', 'whatsapp', 'facebook', 'website', 'referral', 'agent'])
                  ->default('walk_in');
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            $table->decimal('lifetime_value', 12, 2)->default(0);
            $table->integer('total_bookings')->default(0);
            
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('customer_code');
            $table->index('source');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
