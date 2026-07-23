<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_tabs', function (Blueprint $table) {
            $table->id();
            $table->string('tab_key')->unique(); // flight, umrah, visa, cargo, appointment, investor
            $table->json('label');
            $table->json('title');
            $table->json('subtitle');
            $table->json('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->json('features')->nullable(); // Array of feature strings
            $table->json('button_text');
            $table->string('button_url')->nullable();
            $table->string('route_name')->nullable();
            $table->json('route_params')->nullable();
            $table->string('service_type')->nullable(); // Links to booking type
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_nav')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_tabs');
    }
};
