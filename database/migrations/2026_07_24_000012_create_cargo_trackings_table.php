<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargo_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargo')->onDelete('cascade');
            $table->string('status');
            $table->string('status_bn')->nullable();
            $table->string('status_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('timestamp');
            $table->boolean('notify_customer')->default(false);
            $table->timestamps();

            $table->index(['cargo_id', 'timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_trackings');
    }
};
