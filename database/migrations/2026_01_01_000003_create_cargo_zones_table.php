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
        // Skip if tables already exist (they will be created by 000000 fix migration)
        if (!Schema::hasTable('cargo_zones')) {
            Schema::create('cargo_zones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('city_id');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_zones');
    }
};
