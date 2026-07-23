<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_path')->unique();
            $table->string('new_path')->nullable();
            $table->enum('type', ['301', '302', '307', '308'])->default('301');
            $table->boolean('is_active')->default(true);
            $table->integer('hit_count')->default(0)->unsigned();
            $table->text('description')->nullable();
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'old_path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_redirects');
    }
};
