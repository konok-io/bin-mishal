<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained()->onDelete('cascade');
            
            // Content
            $table->json('title')->nullable()->comment('{"bn":"...", "en":"...", "ar":"..."}');
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            
            // Media
            $table->string('icon')->nullable()->comment('Heroicon or SVG');
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            
            // Links
            $table->json('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_target')->default('_self');
            
            // Additional data
            $table->json('extra')->nullable()->comment('Any additional structured data');
            
            // Ordering
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['page_section_id', 'order']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_section_items');
    }
};
