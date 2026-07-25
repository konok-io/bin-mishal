<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appearance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('section');
            $table->string('label');
            $table->string('type')->default('text');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appearance_settings');
    }
};
