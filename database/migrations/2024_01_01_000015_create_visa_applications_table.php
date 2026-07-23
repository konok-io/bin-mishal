<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('visa_type_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('applicant_name')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('iqama_no')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_id')->nullable();
            
            $table->date('travel_date')->nullable();
            $table->date('return_date')->nullable();
            $table->text('purpose')->nullable();
            
            $table->enum('status', [
                'draft', 'submitted', 'document_pending', 'under_review',
                'government_processing', 'approved', 'rejected', 'delivered'
            ])->default('draft');
            
            $table->string('current_stage')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('government_reference_no')->nullable();
            
            $table->decimal('government_fee', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            $table->date('submission_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->date('completion_date')->nullable();
            
            $table->text('rejection_reason')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('application_no');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_applications');
    }
};
