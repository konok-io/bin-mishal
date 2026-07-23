<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('related_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // umrah, visa, cargo, flight, investor, appointment, page
            $table->unsignedBigInteger('service_id');
            $table->string('related_service_type');
            $table->unsignedBigInteger('related_service_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['service_type', 'service_id', 'related_service_type', 'related_service_id'], 'unique_related');
            $table->index(['service_type', 'service_id']);
            $table->index(['related_service_type', 'related_service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_services');
    }
};
