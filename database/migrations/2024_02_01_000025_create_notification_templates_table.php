<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Conditional table creation to handle cases where table already exists.
     */
    public function up(): void
    {
        if (!Schema::hasTable('notification_templates')) {
            Schema::create('notification_templates', function (Blueprint $table) {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
