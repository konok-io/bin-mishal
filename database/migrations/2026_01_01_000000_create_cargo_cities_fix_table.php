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
        // Drop dependent tables first, then cargo_cities
        Schema::dropIfExists('cargo_zones');
        Schema::dropIfExists('cargo_cities');
        
        // Create fresh cargo_cities table
        Schema::create('cargo_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable()->comment('1=Saudi Arabia, 2=Bangladesh');
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->string('code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_saudi')->default(false);
            $table->boolean('is_bangladesh')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        // Recreate cargo_zones table
        Schema::create('cargo_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->integer('min_delivery_days')->default(1);
            $table->integer('max_delivery_days')->default(7);
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
        Schema::dropIfExists('cargo_cities');
    }
};
