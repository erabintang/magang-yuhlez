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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('program_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->string('priority', 20)->default('NORMAL'); // LOW, NORMAL, HIGH, URGENT
            $table->string('status', 20)->default('ACTIVE'); // ACTIVE, CLOSED
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('program_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
