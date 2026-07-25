<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Spatie Media Library required columns to the existing media table
        Schema::table('media', function (Blueprint $table) {
            // Only add if columns don't exist
            if (!Schema::hasColumn('media', 'model_type')) {
                $table->string('model_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('media', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            }
            if (!Schema::hasColumn('media', 'collection_name')) {
                $table->string('collection_name')->nullable()->after('model_id');
            }
            if (!Schema::hasColumn('media', 'uuid')) {
                $table->uuid('uuid')->nullable()->unique()->after('collection_name');
            }
            if (!Schema::hasColumn('media', 'conversions_disk')) {
                $table->string('conversions_disk')->nullable()->after('uuid');
            }
            if (!Schema::hasColumn('media', 'responsive_images')) {
                $table->json('responsive_images')->nullable()->after('conversions_disk');
            }
            
            // Add indexes for the polymorphic relationship
            if (!Schema::hasColumn('media', 'model_type')) {
                $table->index(['model_type', 'model_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $columns = ['model_type', 'model_id', 'collection_name', 'uuid', 'conversions_disk', 'responsive_images'];
            $existingColumns = array_filter($columns, fn($col) => Schema::hasColumn('media', $col));
            foreach ($existingColumns as $column) {
                $table->dropColumn($column);
            }
        });
    }
};
