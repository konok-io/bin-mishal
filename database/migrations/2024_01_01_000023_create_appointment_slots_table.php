<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('capacity')->default(10);
            $table->integer('booked_count')->default(0);
            $table->enum('service_type', ['ticket', 'visa', 'umrah', 'consultation', 'document', 'other'])
                  ->default('other');
            $table->enum('status', ['available', 'full', 'closed'])->default('available');
            $table->timestamps();
            
            $table->index(['branch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_slots');
    }
};
