<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('biometric_devices')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_bio_id'); // ID from biometric device
            $table->timestamp('punch_time');
            $table->enum('punch_type', ['check_in', 'check_out', 'break_out', 'break_in', 'overtime_in', 'overtime_out']);
            $table->enum('verify_mode', ['finger', 'face', 'card', 'password', 'other'])->default('finger');
            $table->string('location')->nullable();
            $table->boolean('is_synced')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'punch_time']);
            $table->index(['device_id', 'punch_time']);
            $table->unique(['device_id', 'employee_bio_id', 'punch_time', 'punch_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_attendance');
    }
};
