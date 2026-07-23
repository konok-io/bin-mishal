<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            $table->enum('trip_type', ['oneway', 'roundtrip', 'multicity'])->default('oneway');
            
            $table->foreignId('from_airport_id')->nullable()->constrained('airports')->onDelete('set null');
            $table->foreignId('to_airport_id')->nullable()->constrained('airports')->onDelete('set null');
            
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            
            $table->tinyInteger('adults')->default(1);
            $table->tinyInteger('children')->default(0);
            $table->tinyInteger('infants')->default(0);
            
            $table->enum('cabin_class', ['economy', 'premium', 'business', 'first'])->default('economy');
            
            $table->foreignId('preferred_airline_id')->nullable()->constrained('airlines')->onDelete('set null');
            
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->string('baggage_requirement')->nullable();
            $table->text('special_request')->nullable();
            
            $table->enum('status', ['pending', 'quoted', 'confirmed', 'ticketed', 'cancelled'])
                  ->default('pending');
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            $table->index('request_no');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_requests');
    }
};
