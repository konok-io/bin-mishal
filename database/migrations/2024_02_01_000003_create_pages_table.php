<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            
            // Basic info
            $table->json('title')->comment('{"bn":"...", "en":"...", "ar":"..."}');
            $table->json('slug')->comment('{"bn":"...", "en":"...", "ar":"..."} - localized slugs');
            
            // Page hierarchy and type
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('set null');
            $table->enum('template', [
                'default',    // Standard page
                'full_width', // No sidebar, full width
                'sidebar',    // With sidebar
                'landing',    // Landing page (no header/footer variations)
                'contact',    // Contact page with form
                'listing',    // Category listing page
            ])->default('default');
            
            // Flags
            $table->boolean('is_homepage')->default(false)->index();
            $table->boolean('is_system')->default(false)->comment('System pages cannot be deleted');
            $table->boolean('show_header')->default(true);
            $table->boolean('show_footer')->default(true);
            $table->boolean('show_breadcrumb')->default(true);
            
            // Hero section
            $table->enum('hero_type', ['none', 'image', 'slider', 'video', 'gradient'])->default('none');
            $table->string('hero_image')->nullable();
            $table->json('hero_title')->nullable()->comment('Override page title in hero');
            $table->json('hero_subtitle')->nullable();
            $table->string('hero_video_url')->nullable();
            
            // SEO
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->string('schema_type')->nullable()->comment('Schema.org type');
            
            // Layout
            $table->string('layout')->default('public')->comment('Blade layout to use');
            $table->string('custom_css')->nullable();
            $table->string('custom_js')->nullable();
            
            // Publishing
            $table->integer('order')->default(0);
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Authorship
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('parent_id');
            $table->index('template');
            $table->index('status');
            $table->index(['status', 'published_at']);
            
            // Unique constraint on slug per locale (enforced at application level)
            // DB-level: we need a composite unique on slug_values for each locale
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
