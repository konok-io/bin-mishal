<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('airline_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('flight_no')->nullable();
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arrival_datetime')->nullable();
            $table->integer('stops')->default(0);
            $table->json('layover_details')->nullable();
            $table->string('baggage_allowance')->nullable();
            
            $table->decimal('base_fare', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('service_charge', 12, 2)->default(0);
            $table->decimal('total_fare', 12, 2);
            $table->string('currency')->default('SAR');
            
            $table->date('valid_until')->nullable();
            
            $table->enum('status', ['sent', 'accepted', 'rejected', 'expired'])
                  ->default('sent');
            
            $table->timestamps();
            
            $table->index('flight_request_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_quotes');
    }
};
