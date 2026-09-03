<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        if (!$token || !$email) {
            return redirect()->route('login')
                ->with('error', 'Token reset password tidak valid.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
        }

        // Check token is not too old (1 hour)
        $tokenAge = Carbon::parse($resetRecord->created_at)->diffInMinutes(now());
        if ($tokenAge > 60) {
            \DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
            return back()->with('error', 'Token reset password sudah kedaluwarsa. Silakan minta yang baru.');
        }

        // Update password
        $profile = Profile::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        if (!$profile) {
            return back()->with('error', 'Akun tidak ditemukan.');
        }

        $profile->update([
            'password_hash' => Hash::make($request->password),
        ]);

        // Delete token
        \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Send notification email
        EmailService::sendPasswordChangedEmail(
            $profile->email,
            $profile->name
        );

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
