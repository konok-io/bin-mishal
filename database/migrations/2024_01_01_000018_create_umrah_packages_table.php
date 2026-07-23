<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umrah_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique();
            
            $table->json('description')->nullable();
            
            $table->integer('duration_days')->nullable();
            $table->integer('duration_nights')->nullable();
            
            $table->string('makkah_hotel')->nullable();
            $table->integer('makkah_hotel_stars')->default(3);
            $table->integer('makkah_distance_meters')->nullable();
            $table->integer('makkah_nights')->default(3);
            
            $table->string('madinah_hotel')->nullable();
            $table->integer('madinah_hotel_stars')->default(3);
            $table->integer('madinah_distance_meters')->nullable();
            $table->integer('madinah_nights')->default(3);
            
            $table->string('transport_type')->nullable();
            $table->enum('meal_plan', ['none', 'breakfast', 'half_board', 'full_board'])->default('breakfast');
            
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('itinerary')->nullable();
            
            $table->decimal('price_quad', 12, 2)->nullable();
            $table->decimal('price_triple', 12, 2)->nullable();
            $table->decimal('price_double', 12, 2)->nullable();
            $table->decimal('price_single', 12, 2)->nullable();
            $table->decimal('child_price', 12, 2)->nullable();
            $table->decimal('infant_price', 12, 2)->nullable();
            $table->string('currency')->default('SAR');
            
            $table->json('departure_dates')->nullable();
            $table->integer('available_seats')->nullable();
            $table->integer('booked_seats')->default(0);
            
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umrah_packages');
    }
};
