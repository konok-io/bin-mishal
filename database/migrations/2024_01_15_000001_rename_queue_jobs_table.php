<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename Laravel's queue 'jobs' table to 'queue_jobs' to avoid conflict with career 'jobs' table.
     */
    public function up(): void
    {
        // First, rename the Laravel queue jobs table to avoid conflict with the career jobs table
        Schema::rename('jobs', 'queue_jobs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('queue_jobs', 'jobs');
    }
};
