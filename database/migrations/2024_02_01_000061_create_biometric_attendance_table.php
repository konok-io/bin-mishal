<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'biometric_attendance';
        $migrationName = '2024_02_01_000061_create_biometric_attendance_table';
        
        $tableExists = Schema::hasTable($tableName);
        $migrationRecorded = DB::table('migrations')
            ->where('migration', $migrationName)
            ->exists();
        
        if ($tableExists && $migrationRecorded) {
            return;
        }
        
        if (!$tableExists) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id')->constrained('biometric_devices')->cascadeOnDelete();
                $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
                $table->string('employee_bio_id');
                $table->timestamp('punch_time');
                $table->enum('punch_type', ['check_in', 'check_out', 'break_out', 'break_in', 'overtime_in', 'overtime_out']);
                $table->enum('verify_mode', ['finger', 'face', 'card', 'password', 'other'])->default('finger');
                $table->string('location')->nullable();
                $table->boolean('is_synced')->default(false);
                $table->timestamp('synced_at')->nullable();
                $table->timestamps();
                
                $table->index(['employee_id', 'punch_time']);
                $table->index(['device_id', 'punch_time']);
                $table->unique(['device_id', 'employee_bio_id', 'punch_time', 'punch_type'], 'biometric_attendance_unique');
            });
        }
        
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
        Schema::dropIfExists('biometric_attendance');
    }
};
