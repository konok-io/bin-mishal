<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fully idempotent - handles all cases:
     * 1. Table doesn't exist -> create it
     * 2. Table exists + migration recorded -> skip
     * 3. Table exists + migration NOT recorded -> insert fake record
     */
    public function up(): void
    {
        $tableName = 'notification_templates';
        $migrationName = '2024_02_01_000025_create_notification_templates_table';
        
        // Check if table exists
        $tableExists = Schema::hasTable($tableName);
        
        // Check if migration is already recorded
        $migrationRecorded = DB::table('migrations')
            ->where('migration', $migrationName)
            ->exists();
        
        if ($tableExists && $migrationRecorded) {
            // Case 2: Already done, skip
            return;
        }
        
        if (!$tableExists) {
            // Case 1: Create table
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('email');
                $table->string('event')->unique();
                $table->string('subject')->nullable();
                $table->string('subject_bn')->nullable();
                $table->string('subject_ar')->nullable();
                $table->longText('body')->nullable();
                $table->longText('body_bn')->nullable();
                $table->longText('body_ar')->nullable();
                $table->json('variables')->nullable();
                $table->json('channels')->default('["email"]');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['event', 'is_active']);
            });
        }
        
        // Case 3: Table exists but migration not recorded - record it
        if (!$migrationRecorded) {
            $maxBatch = DB::table('migrations')->max('batch') ?? 0;
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => $maxBatch + 1,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
