<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargo_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cargo_cities')->cascadeOnDelete();
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->integer('min_delivery_days')->default(1);
            $table->integer('max_delivery_days')->default(3);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_zones');
    }
};
