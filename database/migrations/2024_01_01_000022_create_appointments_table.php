<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_no')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            
            $table->foreignId('appointment_slot_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            
            $table->enum('service_type', ['ticket', 'visa', 'umrah', 'consultation', 'document', 'other'])
                  ->default('other');
            
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->text('purpose')->nullable();
            
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'])
                  ->default('scheduled');
            
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            
            $table->timestamps();
            
            $table->index('appointment_no');
            $table->index('status');
            $table->index('preferred_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
