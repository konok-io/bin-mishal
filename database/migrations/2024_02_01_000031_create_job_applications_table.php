<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('phone_country_code')->default('+966');
            $table->longText('cover_letter')->nullable();
            $table->string('cv_path');
            $table->string('status')->default('received');
            $table->timestamp('applied_at')->useCurrent();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->longText('interview_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->longText('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['job_id', 'status']);
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
