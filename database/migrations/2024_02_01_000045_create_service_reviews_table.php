<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // umrah, visa, cargo, flight, investor
            $table->unsignedBigInteger('service_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->index(['service_type', 'service_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};
