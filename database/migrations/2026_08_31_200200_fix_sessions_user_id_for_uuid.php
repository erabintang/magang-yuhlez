<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix sessions.user_id to support UUID primary keys.
     *
     * The default Laravel migration creates sessions.user_id as bigint unsigned
     * via foreignId(), but the Profile model (auth model) uses UUID (char(36)).
     * This mismatch causes the login redirect loop because MySQL cannot store
     * UUID strings in a bigint column, so the session never links to a user.
     */
    public function up(): void
    {
        // MySQL/MariaDB: drop and recreate the column as varchar(36)
        DB::statement('ALTER TABLE `sessions` MODIFY COLUMN `user_id` varchar(36) NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `sessions` MODIFY COLUMN `user_id` bigint unsigned NULL DEFAULT NULL');
    }
};
