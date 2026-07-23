<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->unique();
            $table->json('booking_types')->nullable();
            $table->json('settings')->nullable();
            $table->json('form_fields')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('min_quantity')->default(1);
            $table->integer('max_quantity')->default(10);
            $table->string('currency', 10)->default('SAR');
            $table->string('pricing_model')->default('fixed');
            $table->boolean('requires_confirmation')->default(true);
            $table->boolean('allow_cancellation')->default(true);
            $table->integer('cancellation_deadline_days')->default(7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_configurations');
    }
};
