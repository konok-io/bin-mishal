<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('folder')->default('general');
            $table->string('name');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('file_type')->default('other'); // image, video, document, other
            $table->unsignedBigInteger('file_size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamps();
            
            $table->index(['folder', 'is_active']);
            $table->index(['file_type', 'is_active']);
            $table->index(['is_active', 'created_at']);
            $table->index(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
