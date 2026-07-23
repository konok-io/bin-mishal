<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_id')->unique(); // Device serial number
            $table->string('name');
            $table->string('brand')->default('zkteco'); // zkteco, hikvision, essl, realtime
            $table->string('model')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('port')->default(4370);
            $table->string('comm_key')->nullable(); // For ZKTeco encrypted devices
            $table->enum('sync_method', ['webhook', 'polling', 'manual', 'csv'])->default('webhook');
            $table->string('webhook_url')->nullable();
            $table->string('api_key')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'offline'])->default('active');
            $table->timestamp('last_sync_at')->nullable();
            $table->integer('sync_interval')->default(5); // minutes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_devices');
    }
};
