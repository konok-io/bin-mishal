<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('icon')->nullable();
            $table->integer('number')->default(0);
            $table->string('number_suffix')->nullable();
            $table->string('number_suffix_bn')->nullable();
            $table->string('number_suffix_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('color')->default('#198754');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_cards');
    }
};
