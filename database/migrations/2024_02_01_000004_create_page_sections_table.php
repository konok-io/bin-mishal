<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            
            // Section identification
            $table->string('section_type', 100)->comment('Key from section type registry');
            $table->string('name')->comment('Admin reference label');
            
            // Content (translatable text fields)
            $table->json('content')->nullable()->comment('All text content, translatable');
            
            // Settings (layout, colors, spacing, etc.)
            $table->json('settings')->nullable()->comment('Layout configuration');
            
            // Data source (for dynamic sections)
            $table->json('data_source')->nullable()->comment('{
                "model": "UmrahPackage",
                "filter": {"is_featured": true},
                "limit": 8,
                "order": "order:asc",
                "columns": ["title", "price", "featured_image"]
            }');
            
            // Visibility rules
            $table->json('visibility')->nullable()->comment('{
                "locales": ["bn", "en", "ar"],
                "devices": ["desktop", "mobile"],
                "date_from": "2024-01-01",
                "date_to": "2024-12-31",
                "auth_required": false
            }');
            
            // Ordering
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['page_id', 'order']);
            $table->index('section_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
