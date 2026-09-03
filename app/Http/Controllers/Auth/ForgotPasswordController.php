<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $profile = Profile::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        // Always show success message to prevent email enumeration
        if ($profile) {
            $token = Str::random(64);

            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $profile->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );

            $resetUrl = config('app.url') . '/reset-password?token=' . $token . '&email=' . urlencode($profile->email);

            EmailService::sendPasswordResetEmail(
                $profile->email,
                $profile->name,
                $resetUrl
            );
        }

        return back()->with('success', 'Jika email terdaftar, tautan reset password telah dikirim.');
    }
}
