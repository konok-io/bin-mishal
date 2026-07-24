<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_no')->unique();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address')->nullable();
            $table->unsignedBigInteger('origin_city_id');
            $table->unsignedBigInteger('destination_city_id');
            $table->unsignedBigInteger('cargo_type_id');
            $table->decimal('weight', 10, 2)->default(0);
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->text('special_instructions')->nullable();
            $table->date('expected_delivery')->nullable();
            $table->date('delivered_at')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('tracking_no');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
