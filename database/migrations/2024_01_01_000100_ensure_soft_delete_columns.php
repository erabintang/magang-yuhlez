<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add deleted_at columns if they don't exist
        // This is safe for production - it won't modify existing data
        $tables = [
            'notifications',
            'work_gallery',
            'work_interns',
            'contributors',
            'locations',
            'kost_places',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $bl) use ($table) {
                    $bl->timestamp('deleted_at')->nullable()->index()->after('updated_at');
                });
            }
        }

        // files table has no 'updated_at', use 'created_at' as anchor
        if (Schema::hasTable('files') && !Schema::hasColumn('files', 'deleted_at')) {
            Schema::table('files', function (Blueprint $bl) {
                $bl->timestamp('deleted_at')->nullable()->index()->after('created_at');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'notifications',
            'files',
            'work_gallery',
            'work_interns',
            'contributors',
            'locations',
            'kost_places',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $bl) {
                    $bl->dropColumn('deleted_at');
                });
            }
        }
    }
};
