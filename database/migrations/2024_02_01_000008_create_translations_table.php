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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('group', 100)->index();
            $table->string('key', 255)->index();
            $table->longText('value_bn')->nullable();
            $table->longText('value_en')->nullable();
            $table->longText('value_ar')->nullable();
            $table->enum('source', ['code', 'manual', 'imported'])->default('code');
            $table->enum('status', ['complete', 'missing_bn', 'missing_en', 'missing_ar', 'needs_review'])
                  ->default('complete');
            $table->timestamp('last_seen_in_code_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Unique index on group + key
            $table->unique(['group', 'key']);
            
            // Index for finding missing translations
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
