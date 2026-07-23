<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 20)->unique();
            
            // Customer Info
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sender_name');
            $table->string('sender_phone', 20);
            $table->string('sender_email')->nullable();
            $table->text('sender_address');
            $table->string('sender_city');
            
            $table->string('receiver_name');
            $table->string('receiver_phone', 20);
            $table->string('receiver_email')->nullable();
            $table->text('receiver_address');
            $table->string('receiver_city');
            $table->foreignId('receiver_zone_id')->nullable()->constrained('cargo_zones')->nullOnDelete();
            
            // Cargo Details
            $table->foreignId('cargo_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cargo_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cargo_description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('weight', 10, 2)->default(0);
            $table->decimal('length', 8, 2)->default(0);
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('height', 8, 2)->default(0);
            $table->decimal('declared_value', 12, 2)->default(0);
            
            // Pricing
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->foreignId('coupon_id')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            
            // Delivery Info
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->integer('delivery_days')->default(3);
            $table->text('special_instructions')->nullable();
            
            // Status
            $table->enum('status', [
                'pending', 'confirmed', 'collected', 'warehouse', 
                'in_transit', 'customs', 'bd_hub', 'out_for_delivery', 
                'delivered', 'cancelled', 'returned'
            ])->default('pending');
            
            // Branch & Staff
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('driver_id')->nullable();
            $table->foreignId('assigned_to')->nullable();
            
            // Payment
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Files
            $table->string('package_image')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tracking_number']);
            $table->index(['status']);
            $table->index(['customer_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
