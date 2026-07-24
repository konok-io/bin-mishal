<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biometric_attendance', function (Blueprint $table) {
            $table->date('punch_date')->nullable()->after('punch_time');
            $table->time('check_in_time')->nullable()->after('punch_date');
            $table->time('check_out_time')->nullable()->after('check_in_time');
        });
    }

    public function down(): void
    {
        Schema::table('biometric_attendance', function (Blueprint $table) {
            $table->dropColumn(['punch_date', 'check_in_time', 'check_out_time']);
        });
    }
};
