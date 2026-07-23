<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('department');
            $table->string('department_bn')->nullable();
            $table->string('department_ar')->nullable();
            $table->string('location');
            $table->string('location_bn')->nullable();
            $table->string('location_ar')->nullable();
            $table->string('country')->default('SA');
            $table->string('employment_type')->default('full_time');
            $table->string('experience_level')->default('mid');
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->boolean('salary_visible')->default(false);
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
            $table->longText('description_ar')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->longText('responsibilities_bn')->nullable();
            $table->longText('responsibilities_ar')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('requirements_bn')->nullable();
            $table->longText('requirements_ar')->nullable();
            $table->longText('benefits')->nullable();
            $table->longText('benefits_bn')->nullable();
            $table->longText('benefits_ar')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'is_featured']);
            $table->index('department');
            $table->index('employment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
