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
        Schema::create('cargo_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_city_id')->nullable()->constrained('cargo_cities')->nullOnDelete();
            $table->foreignId('destination_city_id')->nullable()->constrained('cargo_cities')->nullOnDelete();
            $table->string('pricing_type'); // weight, package, volumetric
            $table->decimal('min_weight', 10, 2)->default(0);
            $table->decimal('max_weight', 10, 2)->default(0);
            $table->decimal('price_per_kg', 10, 2)->default(0);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_pricing');
    }
};
