<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('internship_positions', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('program_id');
            $t->string('name', 255);
            $t->text('description')->nullable();
            $t->integer('quota')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('internship_applications', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('program_id');
            $t->uuid('position_id');
            $t->uuid('intern_id');
            $t->string('status', 20)->default('PENDING');
            $t->text('cover_letter')->nullable();
            $t->text('rejection_reason')->nullable();
            $t->timestamp('applied_at')->nullable();
            $t->timestamp('reviewed_at')->nullable();
            $t->uuid('reviewed_by')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('application_status_histories', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('application_id');
            $t->string('old_status', 50)->nullable();
            $t->string('new_status', 50);
            $t->text('reason')->nullable();
            $t->uuid('changed_by')->nullable();
            $t->timestamps();
        });
        Schema::create('program_interns', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('program_id');
            $t->uuid('intern_id');
            $t->uuid('application_id')->nullable();
            $t->timestamp('joined_at')->nullable();
            $t->timestamp('removed_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('program_banners', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('program_id');
            $t->uuid('file_id');
            $t->integer('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('certificates', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('program_id');
            $t->uuid('intern_id');
            $t->uuid('file_id')->nullable();
            $t->string('certificate_number', 100)->nullable();
            $t->timestamp('issued_at')->nullable();
            $t->string('status', 20)->default('NOT_ELIGIBLE');
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('notifications', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('user_id');
            $t->string('type', 50);
            $t->string('title', 255);
            $t->text('message');
            $t->boolean('is_read')->default(false);
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('works', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('company_id')->nullable();
            $t->uuid('creator_id')->nullable();
            $t->string('work_type', 20);
            $t->string('slug', 255)->unique();
            $t->string('title', 255);
            $t->string('short_description', 500)->nullable();
            $t->text('description')->nullable();
            $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->uuid('poster_file_id')->nullable();
            $t->uuid('media_file_id')->nullable();
            $t->string('category', 100)->nullable();
            $t->integer('year')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('work_gallery', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('work_id');
            $t->uuid('file_id');
            $t->integer('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('work_interns', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('work_id');
            $t->uuid('intern_id');
            $t->timestamp('added_at')->nullable();
            $t->timestamp('removed_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('contributors', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('slug', 255)->unique();
            $t->string('name', 255);
            $t->string('logo_url', 1000)->nullable();
            $t->uuid('logo_file_id')->nullable();
            $t->text('description')->nullable();
            $t->string('category', 100)->nullable();
            $t->string('website', 500)->nullable();
            $t->string('instagram_url', 500)->nullable();
            $t->boolean('is_active')->default(true);
            $t->integer('sort_order')->default(0);
            $t->string('period', 50)->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('locations', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('slug', 255)->unique();
            $t->string('name', 255);
            $t->text('description')->nullable();
            $t->string('address', 500)->nullable();
            $t->float('latitude')->nullable();
            $t->float('longitude')->nullable();
            $t->string('gmaps_url', 500)->nullable();
            $t->text('gmaps_embed')->nullable();
            $t->integer('radius_m')->default(5000);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('kost_places', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('name', 255);
            $t->string('address', 500)->nullable();
            $t->float('latitude')->nullable();
            $t->float('longitude')->nullable();
            $t->integer('price_per_month')->nullable();
            $t->string('facilities', 500)->nullable();
            $t->string('contact', 200)->nullable();
            $t->string('maps_url', 500)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

    }
    public function down(): void {
        Schema::dropIfExists('kost_places');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('contributors');
        Schema::dropIfExists('work_interns');
        Schema::dropIfExists('work_gallery');
        Schema::dropIfExists('works');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('program_banners');
        Schema::dropIfExists('program_interns');
        Schema::dropIfExists('application_status_histories');
        Schema::dropIfExists('internship_applications');
        Schema::dropIfExists('internship_positions');
    }
};
