<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('iata_code', 2)->unique();
            $table->string('icao_code', 3)->nullable()->unique();
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('iata_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
