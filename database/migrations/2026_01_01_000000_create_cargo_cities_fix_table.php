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
        // Drop all cargo tables in correct order (respecting foreign keys)
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('cargo_tracking');
        Schema::dropIfExists('cargo_zones');
        Schema::dropIfExists('cargo_cities');
        Schema::dropIfExists('cargo_pricing');
        Schema::dropIfExists('cargo_packages');
        Schema::dropIfExists('cargo_types');
        Schema::dropIfExists('cargo_coupons');
        Schema::dropIfExists('cargo_settings');
        
        // Create fresh tables
        Schema::create('cargo_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('cargo_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->string('code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_saudi')->default(false);
            $table->boolean('is_bangladesh')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        Schema::create('cargo_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->integer('min_delivery_days')->default(1);
            $table->integer('max_delivery_days')->default(7);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        Schema::create('cargo_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn');
            $table->string('name_ar');
            $table->string('code', 20)->nullable();
            $table->string('dimensions')->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        
        Schema::create('cargo_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cargo_type_id')->nullable();
            $table->unsignedBigInteger('origin_city_id')->nullable();
            $table->unsignedBigInteger('destination_city_id')->nullable();
            $table->string('pricing_type')->default('weight'); // weight, package, volumetric
            $table->decimal('min_weight', 10, 2)->default(0);
            $table->decimal('max_weight', 10, 2)->default(100);
            $table->decimal('price_per_kg', 10, 2)->default(0);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('vat_percentage', 5, 2)->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->unsignedBigInteger('cargo_type_id')->nullable();
            $table->unsignedBigInteger('cargo_package_id')->nullable();
            $table->unsignedBigInteger('sender_city_id')->nullable();
            $table->unsignedBigInteger('receiver_city_id')->nullable();
            $table->unsignedBigInteger('receiver_zone_id')->nullable();
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->text('sender_address');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->text('receiver_address');
            $table->decimal('weight', 10, 2)->default(1);
            $table->integer('quantity')->default(1);
            $table->text('cargo_description')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('declared_value', 12, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid'); // unpaid, partial, paid
            $table->string('status')->default('pending'); // pending, confirmed, collected, warehouse, in_transit, customs, bd_hub, out_for_delivery, delivered, cancelled, returned
            $table->timestamp('pickup_date')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
        
        Schema::create('cargo_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cargo_id');
            $table->string('status');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
        
        Schema::create('cargo_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('discount_type')->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('cargo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_settings');
        Schema::dropIfExists('cargo_coupons');
        Schema::dropIfExists('cargo_tracking');
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('cargo_pricing');
        Schema::dropIfExists('cargo_packages');
        Schema::dropIfExists('cargo_zones');
        Schema::dropIfExists('cargo_cities');
        Schema::dropIfExists('cargo_types');
    }
};
