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
        Schema::create('cargo_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->string('code', 20);
            $table->decimal('length', 8, 2)->default(0);
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('height', 8, 2)->default(0);
            $table->decimal('max_weight', 10, 2)->default(0);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(15);
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
        Schema::dropIfExists('cargo_packages');
    }
};
