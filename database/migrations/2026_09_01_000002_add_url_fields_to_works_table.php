<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('source_code_url', 500)->nullable()->after('year');
            $table->string('deploy_url', 500)->nullable()->after('source_code_url');
        });
    }

    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['source_code_url', 'deploy_url']);
        });
    }
};
