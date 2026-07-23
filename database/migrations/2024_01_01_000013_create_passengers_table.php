<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_no')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('passport_issue_country')->nullable();
            $table->string('passport_scan')->nullable();
            $table->string('visa_scan')->nullable();
            $table->string('seat_preference')->nullable();
            $table->string('meal_preference')->nullable();
            
            $table->enum('passenger_type', ['adult', 'child', 'infant'])->default('adult');
            
            $table->timestamps();
            
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
