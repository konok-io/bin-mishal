<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('designation')->nullable();
            $table->string('designation_bn')->nullable();
            $table->string('designation_ar')->nullable();
            $table->string('company')->nullable();
            $table->string('company_bn')->nullable();
            $table->string('company_ar')->nullable();
            $table->text('quote');
            $table->text('quote_bn')->nullable();
            $table->text('quote_ar')->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->string('avatar')->nullable();
            $table->string('service_type')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
