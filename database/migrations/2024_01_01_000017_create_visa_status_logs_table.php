<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_application_id')->constrained()->onDelete('cascade');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('note')->nullable();
            $table->boolean('notified_customer')->default(false);
            $table->timestamps();
            
            $table->index('visa_application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_status_logs');
    }
};
