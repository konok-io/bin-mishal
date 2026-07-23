<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns to users table
            $table->string('name_bn')->nullable()->after('name');
            $table->string('name_ar')->nullable()->after('name_bn');
            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('whatsapp')->nullable()->after('phone');
            
            $table->enum('user_type', ['customer', 'employee', 'admin', 'super_admin'])
                  ->default('customer')->after('password');
            
            $table->string('nationality')->nullable()->after('user_type');
            $table->string('passport_no')->nullable()->after('nationality');
            $table->string('iqama_no')->nullable()->after('passport_no');
            $table->date('iqama_expiry')->nullable()->after('iqama_no');
            
            $table->string('city')->nullable()->after('iqama_expiry');
            $table->text('address')->nullable()->after('city');
            
            $table->enum('preferred_language', ['bn', 'en', 'ar'])
                  ->default('bn')->after('address');
            
            $table->string('avatar')->nullable()->after('preferred_language');
            $table->enum('status', ['active', 'inactive', 'blocked'])
                  ->default('active')->after('avatar');
            
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->string('otp_code')->nullable()->after('phone_verified_at');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            
            $table->string('referred_by')->nullable()->after('otp_expires_at');
            $table->string('referral_code')->nullable()->unique()->after('referred_by');
            
            $table->timestamp('last_login_at')->nullable()->after('referral_code');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            
            $table->softDeletes();
            
            // Add indexes
            $table->index('user_type');
            $table->index('status');
            $table->index('nationality');
            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name_bn', 'name_ar', 'phone', 'whatsapp', 'user_type',
                'nationality', 'passport_no', 'iqama_no', 'iqama_expiry',
                'city', 'address', 'preferred_language', 'avatar', 'status',
                'phone_verified_at', 'otp_code', 'otp_expires_at',
                'referred_by', 'referral_code', 'last_login_at', 'last_login_ip'
            ]);
            
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
