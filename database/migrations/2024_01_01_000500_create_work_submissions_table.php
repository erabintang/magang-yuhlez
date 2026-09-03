<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // WORK_SUBMISSIONS - Intern mengirim karya ke Company
        // =============================================
        Schema::create('work_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_id');
            $table->uuid('intern_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('tech_stack')->nullable(); // Tech yang digunakan
            $table->string('status', 20)->default('PENDING'); // PENDING, ACCEPTED, REJECTED
            $table->text('review_note')->nullable();
            $table->uuid('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('work_id');
            $table->index('intern_id');
            $table->index('status');
        });

        // =============================================
        // WORK_SUBMISSION_FILES - File yang di-upload (ZIP, gambar, dll)
        // =============================================
        Schema::create('work_submission_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id');
            $table->uuid('file_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('submission_id');
        });

        // Foreign Keys
        Schema::table('work_submissions', function (Blueprint $table) {
            $table->foreign('work_id')
                ->references('id')->on('works')
                ->cascadeOnDelete();
            $table->foreign('intern_id')
                ->references('id')->on('intern_profiles')
                ->cascadeOnDelete();
            $table->foreign('reviewed_by')
                ->references('id')->on('profiles')
                ->nullOnDelete();
        });

        Schema::table('work_submission_files', function (Blueprint $table) {
            $table->foreign('submission_id')
                ->references('id')->on('work_submissions')
                ->cascadeOnDelete();
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_submission_files');
        Schema::dropIfExists('work_submissions');
    }
};
