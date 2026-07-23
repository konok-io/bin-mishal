<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->id();
            $table->string('from_city');
            $table->string('from_city_bn')->nullable();
            $table->string('from_city_ar')->nullable();
            $table->string('from_country')->default('SA');
            $table->string('to_city');
            $table->string('to_city_bn')->nullable();
            $table->string('to_city_ar')->nullable();
            $table->string('to_country')->default('BD');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->default('SAR');
            $table->string('airline')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_routes');
    }
};
