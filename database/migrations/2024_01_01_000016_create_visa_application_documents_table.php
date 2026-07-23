<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_application_id')->constrained()->onDelete('cascade');
            $table->string('document_type');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            
            $table->enum('status', ['pending', 'uploaded', 'verified', 'rejected'])
                  ->default('pending');
            
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_note')->nullable();
            
            $table->timestamps();
            
            $table->index('visa_application_id');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_application_documents');
    }
};
