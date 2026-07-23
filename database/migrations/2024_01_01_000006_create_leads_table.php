<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('service_interest')->nullable();
            $table->string('source')->nullable();
            
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost'])
                  ->default('new');
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('follow_up_date')->nullable();
            $table->integer('conversion_probability')->nullable();
            
            $table->text('lost_reason')->nullable();
            $table->foreignId('converted_customer_id')->nullable()->constrained('customers')->onDelete('set null');
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('assigned_to');
            $table->index('follow_up_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
