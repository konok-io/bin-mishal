<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargo_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->string('status');
            $table->string('status_bn');
            $table->string('status_ar');
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('updated_by')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->boolean('notify_customer')->default(false);
            $table->timestamps();
            
            $table->index(['cargo_id', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_tracking');
    }
};
