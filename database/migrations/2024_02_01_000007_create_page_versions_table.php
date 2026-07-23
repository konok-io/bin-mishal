<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('version_number');
            $table->json('snapshot')->comment('Full page snapshot at this version');
            $table->text('change_summary')->nullable();
            
            $table->timestamps();
            
            $table->index(['page_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_versions');
    }
};
