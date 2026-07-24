<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $migrationName = '2024_02_01_000062_add_biometric_id_to_employees_table';
        
        $migrationRecorded = DB::table('migrations')
            ->where('migration', $migrationName)
            ->exists();
        
        if ($migrationRecorded) {
            return;
        }
        
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'biometric_id')) {
                $table->string('biometric_id')->nullable()->after('user_id');
                $table->index('biometric_id');
            }
        });
        
        $maxBatch = DB::table('migrations')->max('batch') ?? 0;
        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => $maxBatch + 1,
        ]);
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['biometric_id']);
            $table->dropColumn('biometric_id');
        });
    }
};
