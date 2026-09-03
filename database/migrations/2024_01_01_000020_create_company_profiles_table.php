<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('profiles')->cascadeOnDelete();
            $table->string('slug', 255)->unique();
            $table->string('name', 255);
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('logo_file_id', 36)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->text('gmap_embed')->nullable();
            $table->string('gmail_access', 255)->nullable();
            $table->uuid('location_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
