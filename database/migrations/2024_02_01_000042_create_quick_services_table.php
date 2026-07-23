<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quick_services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('description_bn')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quick_services');
    }
};
