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
        // 1. PROFILES - tambah field 'category'
        // =============================================
        if (!Schema::hasColumn('profiles', 'category')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->string('category', 100)->nullable()->after('role');
            });
        }

        // =============================================
        // 2. INTERNSHIP_PROGRAMS - hapus 'banner_file_id' dan 'location_id'
        // =============================================
        if (Schema::hasColumn('internship_programs', 'banner_file_id')) {
            if (DB::getDriverName() === 'pgsql') {
                // PostgreSQL: drop FK first, then drop column
                $foreignKeys = DB::select("SELECT tc.constraint_name FROM information_schema.table_constraints tc JOIN information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name WHERE tc.table_name = 'internship_programs' AND tc.constraint_type = 'FOREIGN KEY' AND kcu.column_name = 'banner_file_id'");
                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE internship_programs DROP CONSTRAINT IF EXISTS \"{$fk->constraint_name}\"");
                }
                Schema::table('internship_programs', function (Blueprint $table) {
                    $table->dropColumn('banner_file_id');
                });
            }
            // SQLite: skip - column remains but unused, SQLite can't drop FK-constrained columns easily
        }

        if (Schema::hasColumn('internship_programs', 'location_id')) {
            if (DB::getDriverName() === 'pgsql') {
                $foreignKeys = DB::select("SELECT tc.constraint_name FROM information_schema.table_constraints tc JOIN information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name WHERE tc.table_name = 'internship_programs' AND tc.constraint_type = 'FOREIGN KEY' AND kcu.column_name = 'location_id'");
                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE internship_programs DROP CONSTRAINT IF EXISTS \"{$fk->constraint_name}\"");
                }
                Schema::table('internship_programs', function (Blueprint $table) {
                    $table->dropColumn('location_id');
                });
            }
        }

        // =============================================
        // 3. COMPANY_PROFILES - hapus 'location_id'
        // =============================================
        if (Schema::hasColumn('company_profiles', 'location_id')) {
            if (DB::getDriverName() === 'pgsql') {
                $foreignKeys = DB::select("SELECT tc.constraint_name FROM information_schema.table_constraints tc JOIN information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name WHERE tc.table_name = 'company_profiles' AND tc.constraint_type = 'FOREIGN KEY' AND kcu.column_name = 'location_id'");
                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE company_profiles DROP CONSTRAINT IF EXISTS \"{$fk->constraint_name}\"");
                }
                Schema::table('company_profiles', function (Blueprint $table) {
                    $table->dropColumn('location_id');
                });
            }
        }

        // =============================================
        // 4. WORKS - hapus 'creator_id'
        // =============================================
        if (Schema::hasColumn('works', 'creator_id')) {
            Schema::table('works', function (Blueprint $table) {
                $table->dropColumn('creator_id');
            });
        }

        // =============================================
        // 5. HAPUS TABEL YANG TIDAK ADA DI SPEC
        // =============================================
        Schema::dropIfExists('kost_places');
        Schema::dropIfExists('contributors');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('creator_profiles');
    }

    public function down(): void
    {
        Schema::create('creator_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('slug', 255)->unique();
            $table->string('name', 255);
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('profile_photo_file_id', 36)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug', 255)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('address', 500)->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('gmaps_url', 500)->nullable();
            $table->text('gmaps_embed')->nullable();
            $table->integer('radius_m')->default(5000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug', 255)->unique();
            $table->string('name', 255);
            $table->string('logo_url', 1000)->nullable();
            $table->uuid('logo_file_id')->nullable();
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('website', 500)->nullable();
            $table->string('instagram_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('period', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kost_places', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->string('address', 500)->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->integer('price_per_month')->nullable();
            $table->string('facilities', 500)->nullable();
            $table->string('contact', 200)->nullable();
            $table->string('maps_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('works', function (Blueprint $table) {
            $table->uuid('creator_id')->nullable()->after('company_id');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->uuid('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
        });

        Schema::table('internship_programs', function (Blueprint $table) {
            $table->uuid('banner_file_id')->nullable();
            $table->foreign('banner_file_id')->references('id')->on('files')->nullOnDelete();
            $table->uuid('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
