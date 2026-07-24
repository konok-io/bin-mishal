<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city_tv_connects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('password');
            $table->string('ip_address')->nullable();
            $table->integer('port')->default(8000);
            $table->enum('status', ['active', 'inactive', 'error'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamp('last_sync')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_tv_connects');
    }
};
