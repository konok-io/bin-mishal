<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('phone_country_code')->default('+966');
            $table->string('subject')->nullable();
            $table->longText('message');
            $table->string('type')->default('general');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('read_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_replied')->default(false);
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reply_note')->nullable();
            $table->longText('admin_notes')->nullable();
            $table->boolean('is_spam')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['is_read', 'is_spam']);
            $table->index(['type', 'is_read']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
