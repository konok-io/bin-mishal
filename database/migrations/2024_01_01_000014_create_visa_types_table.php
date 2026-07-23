<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            
            $table->string('country')->default('Saudi Arabia');
            $table->enum('category', [
                'exit_reentry', 'final_exit', 'family_visit',
                'work', 'umrah', 'tourist', 'transit'
            ])->default('tourist');
            
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->text('description_ar')->nullable();
            
            $table->integer('processing_days')->nullable();
            $table->decimal('government_fee', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('total_fee', 10, 2)->default(0);
            
            $table->json('required_documents')->nullable();
            $table->json('eligibility_criteria')->nullable();
            
            $table->string('icon')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_types');
    }
};
