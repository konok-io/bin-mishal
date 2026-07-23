<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->string('pnr')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('flight_quote_id')->nullable()->constrained('flight_quotes')->onDelete('set null');
            
            $table->enum('booking_type', ['ticket', 'umrah', 'visa', 'package'])->default('ticket');
            
            $table->integer('passenger_count')->default(1);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2);
            
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])
                  ->default('unpaid');
            
            $table->enum('booking_status', ['pending', 'confirmed', 'issued', 'cancelled', 'refunded'])
                  ->default('pending');
            
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('issue_date')->nullable();
            $table->string('ticket_file')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('booking_no');
            $table->index('pnr');
            $table->index('payment_status');
            $table->index('booking_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
