<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // 1. FILES - uploaded_by → profiles
        // =============================================
        if (!Schema::hasColumn('files', 'deleted_at')) {
            Schema::table('files', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable()->index()->after('created_at');
            });
        }

        Schema::table('files', function (Blueprint $table) {
            $table->foreign('uploaded_by')
                ->references('id')->on('profiles')
                ->nullOnDelete();
        });

        // =============================================
        // 2. COMPANY_PROFILES - convert varchar→uuid for logo_file_id, add FKs
        // =============================================
        // Convert varchar to uuid using explicit CAST (PostgreSQL only)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE company_profiles ALTER COLUMN logo_file_id TYPE uuid USING logo_file_id::uuid");
        }

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->foreign('logo_file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
        });

        // location_id FK - table may not exist on SQLite (dropped in later migration)
        if (DB::getDriverName() === 'pgsql' && Schema::hasTable('locations') && Schema::hasColumn('company_profiles', 'location_id')) {
            Schema::table('company_profiles', function (Blueprint $table) {
                $table->foreign('location_id')
                    ->references('id')->on('locations')
                    ->nullOnDelete();
            });
        }

        // =============================================
        // 3. INTERN_PROFILES - convert varchar→uuid for file refs, add FKs
        // =============================================
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE intern_profiles ALTER COLUMN profile_photo_file_id TYPE uuid USING profile_photo_file_id::uuid");
            DB::statement("ALTER TABLE intern_profiles ALTER COLUMN cv_file_id TYPE uuid USING cv_file_id::uuid");
        }

        Schema::table('intern_profiles', function (Blueprint $table) {
            $table->foreign('profile_photo_file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
            $table->foreign('cv_file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
        });

        // =============================================
        // 4. INTERNSHIP_PROGRAMS - convert varchar→uuid for banner_file_id, add FKs
        // =============================================
        if (Schema::hasColumn('internship_programs', 'banner_file_id') && DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE internship_programs ALTER COLUMN banner_file_id TYPE uuid USING banner_file_id::uuid");
        }

        Schema::table('internship_programs', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company_profiles')
                ->cascadeOnDelete();
        });

        if (Schema::hasColumn('internship_programs', 'banner_file_id')) {
            Schema::table('internship_programs', function (Blueprint $table) {
                $table->foreign('banner_file_id')
                    ->references('id')->on('files')
                    ->nullOnDelete();
            });
        }

        if (DB::getDriverName() === 'pgsql' && Schema::hasTable('locations') && Schema::hasColumn('internship_programs', 'location_id')) {
            Schema::table('internship_programs', function (Blueprint $table) {
                $table->foreign('location_id')
                    ->references('id')->on('locations')
                    ->nullOnDelete();
            });
        }

        // =============================================
        // 5. INTERNSHIP_POSITIONS - program_id
        // =============================================
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->foreign('program_id')
                ->references('id')->on('internship_programs')
                ->cascadeOnDelete();
        });

        // =============================================
        // 6. INTERNSHIP_APPLICATIONS - program_id, position_id, intern_id, reviewed_by
        // =============================================
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->foreign('program_id')
                ->references('id')->on('internship_programs')
                ->cascadeOnDelete();
            $table->foreign('position_id')
                ->references('id')->on('internship_positions')
                ->cascadeOnDelete();
            $table->foreign('intern_id')
                ->references('id')->on('intern_profiles')
                ->cascadeOnDelete();
            $table->foreign('reviewed_by')
                ->references('id')->on('profiles')
                ->nullOnDelete();
        });

        // =============================================
        // 7. APPLICATION_STATUS_HISTORIES - application_id, changed_by
        // =============================================
        Schema::table('application_status_histories', function (Blueprint $table) {
            $table->foreign('application_id')
                ->references('id')->on('internship_applications')
                ->cascadeOnDelete();
            $table->foreign('changed_by')
                ->references('id')->on('profiles')
                ->nullOnDelete();
        });

        // =============================================
        // 8. PROGRAM_INTERNS - program_id, intern_id, application_id
        // =============================================
        Schema::table('program_interns', function (Blueprint $table) {
            $table->foreign('program_id')
                ->references('id')->on('internship_programs')
                ->cascadeOnDelete();
            $table->foreign('intern_id')
                ->references('id')->on('intern_profiles')
                ->cascadeOnDelete();
            $table->foreign('application_id')
                ->references('id')->on('internship_applications')
                ->nullOnDelete();
        });

        // =============================================
        // 9. PROGRAM_BANNERS - program_id, file_id
        // =============================================
        Schema::table('program_banners', function (Blueprint $table) {
            $table->foreign('program_id')
                ->references('id')->on('internship_programs')
                ->cascadeOnDelete();
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->cascadeOnDelete();
        });

        // =============================================
        // 10. CERTIFICATES - program_id, intern_id, file_id
        // =============================================
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('program_id')
                ->references('id')->on('internship_programs')
                ->cascadeOnDelete();
            $table->foreign('intern_id')
                ->references('id')->on('intern_profiles')
                ->cascadeOnDelete();
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
        });

        // =============================================
        // 11. NOTIFICATIONS - user_id
        // =============================================
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('profiles')
                ->cascadeOnDelete();
        });

        // =============================================
        // 12. WORKS - company_id, poster_file_id, media_file_id
        // =============================================
        Schema::table('works', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('company_profiles')
                ->nullOnDelete();
            $table->foreign('poster_file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
            $table->foreign('media_file_id')
                ->references('id')->on('files')
                ->nullOnDelete();
        });

        // =============================================
        // 13. WORK_GALLERY - work_id, file_id
        // =============================================
        Schema::table('work_gallery', function (Blueprint $table) {
            $table->foreign('work_id')
                ->references('id')->on('works')
                ->cascadeOnDelete();
            $table->foreign('file_id')
                ->references('id')->on('files')
                ->cascadeOnDelete();
        });

        // =============================================
        // 14. WORK_INTERNS - work_id, intern_id
        // =============================================
        Schema::table('work_interns', function (Blueprint $table) {
            $table->foreign('work_id')
                ->references('id')->on('works')
                ->cascadeOnDelete();
            $table->foreign('intern_id')
                ->references('id')->on('intern_profiles')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Drop all foreign keys in reverse order
        Schema::table('work_interns', function (Blueprint $table) {
            $table->dropForeign(['work_id']);
            $table->dropForeign(['intern_id']);
        });
        Schema::table('work_gallery', function (Blueprint $table) {
            $table->dropForeign(['work_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::table('works', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['poster_file_id']);
            $table->dropForeign(['media_file_id']);
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['intern_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::table('program_banners', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::table('program_interns', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['intern_id']);
            $table->dropForeign(['application_id']);
        });
        Schema::table('application_status_histories', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['changed_by']);
        });
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['intern_id']);
            $table->dropForeign(['reviewed_by']);
        });
        Schema::table('internship_positions', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });
        Schema::table('internship_programs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            if (Schema::hasColumn('internship_programs', 'banner_file_id')) {
                $table->dropForeign(['banner_file_id']);
            }
            if (Schema::hasColumn('internship_programs', 'location_id')) {
                $table->dropForeign(['location_id']);
            }
        });
        Schema::table('intern_profiles', function (Blueprint $table) {
            $table->dropForeign(['profile_photo_file_id']);
            $table->dropForeign(['cv_file_id']);
        });
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropForeign(['logo_file_id']);
            if (Schema::hasColumn('company_profiles', 'location_id')) {
                $table->dropForeign(['location_id']);
            }
        });
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
        });

        // Revert uuid→varchar (PostgreSQL only)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE company_profiles ALTER COLUMN logo_file_id TYPE varchar(36)");
            DB::statement("ALTER TABLE intern_profiles ALTER COLUMN profile_photo_file_id TYPE varchar(36)");
            DB::statement("ALTER TABLE intern_profiles ALTER COLUMN cv_file_id TYPE varchar(36)");
            if (Schema::hasColumn('internship_programs', 'banner_file_id')) {
                DB::statement("ALTER TABLE internship_programs ALTER COLUMN banner_file_id TYPE varchar(36)");
            }
        }
    }
};
