<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('activity_type', ['call', 'whatsapp', 'email', 'meeting', 'note'])
                  ->default('note');
            
            $table->text('description')->nullable();
            $table->string('outcome')->nullable();
            $table->string('next_action')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            $table->timestamps();
            
            $table->index('lead_id');
            $table->index('employee_id');
            $table->index('activity_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};
