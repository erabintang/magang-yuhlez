<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('section_key', 50)->unique(); // e.g. 'hero', 'team', 'services'
            $table->string('title', 255)->nullable();
            $table->json('content'); // flexible JSON for section-specific data
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
