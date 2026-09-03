<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop latitude/longitude from intern_profiles - not in spec
        if (Schema::hasTable('intern_profiles') && Schema::hasColumn('intern_profiles', 'latitude')) {
            Schema::table('intern_profiles', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('intern_profiles', function (Blueprint $table) {
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
        });
    }
};
