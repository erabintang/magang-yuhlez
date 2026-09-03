<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('registration_start')->nullable();
            $table->timestamp('registration_end')->nullable();
            $table->timestamp('program_start')->nullable();
            $table->timestamp('program_end')->nullable();
            $table->string('banner_file_id', 36)->nullable();
            $table->uuid('location_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_programs');
    }
};
