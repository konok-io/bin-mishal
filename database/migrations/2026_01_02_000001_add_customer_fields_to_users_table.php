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
            // Name fields for multilingual
            $table->string('name_bn')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_bn');
            
            // Contact information
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('whatsapp', 20)->nullable()->after('phone');
            
            // Saudi Arabia specific
            $table->string('iqama_no', 20)->nullable()->after('whatsapp');
            $table->date('iqama_expiry')->nullable()->after('iqama_number');
            
            // Address
            $table->text('address')->nullable()->after('iqama_expiry');
            $table->string('city', 100)->nullable()->after('address');
            
            // Preferences
            $table->string('preferred_language', 10)->default('en')->after('city');
            $table->string('role', 50)->default('customer')->after('preferred_language');
            
            // Status
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name_bn', 'name_ar', 'phone', 'whatsapp',
                'iqama_number', 'iqama_expiry', 'address', 'city',
                'preferred_language', 'role', 'is_active', 'last_login_at'
            ]);
        });
    }
};
