<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('label_bn')->nullable();
            $table->string('label_ar')->nullable();
            $table->integer('value')->default(0);
            $table->string('suffix')->nullable();
            $table->string('suffix_bn')->nullable();
            $table->string('suffix_ar')->nullable();
            $table->string('prefix')->nullable();
            $table->string('prefix_bn')->nullable();
            $table->string('prefix_ar')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#198754');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
