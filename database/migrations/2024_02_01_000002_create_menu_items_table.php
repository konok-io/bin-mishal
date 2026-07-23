<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            
            // Translatable content
            $table->json('title')->comment('{"bn":"...", "en":"...", "ar":"..."}');
            $table->json('description')->nullable()->comment('For mega-menu subtitles');
            
            // Navigation type
            $table->enum('type', [
                'internal',   // Manual URL within site
                'external',   // External URL
                'route',      // Named route
                'page',       // CMS page
                'category',   // Category listing
                'custom',     // Custom URL
            ])->default('custom');
            
            // Link configuration
            $table->string('url')->nullable()->comment('For internal/external types');
            $table->string('route_name')->nullable()->comment('For route type');
            $table->json('route_params')->nullable()->comment('Route parameters');
            $table->foreignId('page_id')->nullable()->comment('For page type, FK to pages table');
            
            // Display options
            $table->string('icon')->nullable()->comment('Heroicon name or SVG path');
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->string('css_class')->nullable();
            
            // Badge
            $table->json('badge_text')->nullable()->comment('{"bn":"...", "en":"...", "ar":"..."}');
            $table->string('badge_color')->nullable()->comment('CSS color class');
            
            // Mega menu
            $table->boolean('is_mega')->default(false);
            $table->tinyInteger('mega_column')->unsigned()->nullable()->comment('1-4 for mega menu columns');
            
            // Ordering and status
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['menu_id', 'parent_id', 'order']);
            $table->index('page_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
