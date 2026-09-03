<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'read_at')) {
            Schema::table('notifications', function (Blueprint $bl) {
                $bl->timestamp('read_at')->nullable()->after('is_read');
            });
        }
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'title')) {
            Schema::table('notifications', function (Blueprint $bl) {
                $bl->string('title')->nullable()->after('type');
            });
        }
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'message')) {
            Schema::table('notifications', function (Blueprint $bl) {
                $bl->text('message')->nullable()->after('title');
            });
        }
    }

    public function down(): void
    {
        //
    }
};
