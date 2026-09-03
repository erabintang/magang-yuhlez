<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@yuhlez.com';
        $password = '12345678';

        $existing = Profile::where('email', $email)->whereNull('deleted_at')->first();

        if ($existing) {
            // Update password if exists
            $existing->update([
                'password_hash' => Hash::make($password),
            ]);
            $this->command->info("Admin account updated: {$email}");
            return;
        }

        Profile::create([
            'id' => Str::uuid(),
            'name' => 'Admin YUHLEZ',
            'email' => $email,
            'role' => 'ROOT',
            'password_hash' => Hash::make($password),
        ]);

        $this->command->info("Admin account created: {$email} / {$password}");
    }
}
