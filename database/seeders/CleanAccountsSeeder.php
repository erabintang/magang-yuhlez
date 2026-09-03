<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Soft-delete SEMUA akun non-ROOT - membuat aplikasi fresh seperti baru deploy.
 * Pakai DB::table() karena banyak tabel tidak punya kolom updated_at.
 */
class CleanAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        $profiles = Profile::where('role', '!=', 'ROOT')
            ->whereNull('deleted_at')
            ->get();

        $deleted = 0;

        foreach ($profiles as $profile) {
            $userId = $profile->id;

            // 1. CompanyProfile
            $company = DB::table('company_profiles')
                ->where('user_id', $userId)->whereNull('deleted_at')->first();
            if ($company) {
                // Programs → positions, applications, program_interns, banners
                $programIds = DB::table('internship_programs')
                    ->where('company_id', $company->id)->whereNull('deleted_at')
                    ->pluck('id')->toArray();
                if ($programIds) {
                    DB::table('internship_applications')
                        ->whereIn('program_id', $programIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now]);
                    DB::table('program_interns')
                        ->whereIn('program_id', $programIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now, 'removed_at' => $now]);
                    DB::table('internship_programs')
                        ->whereIn('id', $programIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now]);
                }

                // Works → gallery, work_interns
                $workIds = DB::table('works')
                    ->where('company_id', $company->id)->whereNull('deleted_at')
                    ->pluck('id')->toArray();
                if ($workIds) {
                    DB::table('work_gallery')
                        ->whereIn('work_id', $workIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now]);
                    DB::table('work_interns')
                        ->whereIn('work_id', $workIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now, 'removed_at' => $now]);
                    DB::table('works')
                        ->whereIn('id', $workIds)->whereNull('deleted_at')
                        ->update(['deleted_at' => $now]);
                }

                DB::table('company_profiles')
                    ->where('id', $company->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now]);
            }

            // 2. InternProfile
            $intern = DB::table('intern_profiles')
                ->where('user_id', $userId)->whereNull('deleted_at')->first();
            if ($intern) {
                DB::table('internship_applications')
                    ->where('intern_id', $intern->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now]);
                DB::table('program_interns')
                    ->where('intern_id', $intern->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now, 'removed_at' => $now]);
                DB::table('work_interns')
                    ->where('intern_id', $intern->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now, 'removed_at' => $now]);
                DB::table('certificates')
                    ->where('intern_id', $intern->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now]);
                DB::table('intern_profiles')
                    ->where('id', $intern->id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now]);
            }

            // 3. Notifications (no updated_at column)
            DB::table('notifications')
                ->where('user_id', $userId)->whereNull('deleted_at')
                ->update(['deleted_at' => $now]);

            // 5. Profile
            $profile->update(['deleted_at' => $now]);
            $deleted++;
        }

        $this->command->info("✅ {$deleted} akun non-ROOT berhasil di-soft-delete. Hanya admin yang tersisa.");
    }
}
