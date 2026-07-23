<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add if not exists
            if (!Schema::hasColumn('users', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name_bn');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'whatsapp')) {
                $table->string('whatsapp', 20)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'iqama_no')) {
                $table->string('iqama_no', 20)->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('users', 'iqama_expiry')) {
                $table->date('iqama_expiry')->nullable()->after('iqama_no');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('iqama_expiry');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'preferred_language')) {
                $table->string('preferred_language', 10)->default('en')->after('city');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('customer')->after('preferred_language');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['name_bn', 'name_ar', 'phone', 'whatsapp',
                'iqama_no', 'iqama_expiry', 'address', 'city',
                'preferred_language', 'role', 'is_active', 'last_login_at'];
            
            $existingColumns = array_filter($columns, fn($col) => Schema::hasColumn('users', $col));
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
