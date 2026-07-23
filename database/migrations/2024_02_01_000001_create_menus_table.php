<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // admin reference
            $table->string('slug')->unique(); // programmatic key
            $table->enum('location', [
                'header',
                'footer_col1',
                'footer_col2',
                'footer_col3',
                'footer_bottom',
                'mobile',
                'top_bar',
                'mega_services',
                'mega_about',
            ])->comment('Where this menu appears');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('location');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
