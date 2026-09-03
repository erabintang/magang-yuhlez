<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show login page with email/password form + Google OAuth button.
     * Per spec: primary login is Google OAuth, but ROOT accounts need
     * email/password login since they may not have Google accounts.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle email/password login for accounts that have a password_hash
     * (typically ROOT/admin accounts). Other roles use Google OAuth.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $profile = \App\Models\Profile::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        if (!$profile || !$profile->password_hash || !Hash::check($request->password, $profile->password_hash)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        Auth::login($profile, true);

        return $this->redirectByRole($profile);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    protected function redirectByRole($profile)
    {
        return match($profile->role) {
            'ROOT' => redirect()->route('root.dashboard'),
            'COMPANY' => redirect()->route('company.dashboard'),
            'INTERN' => redirect()->route('intern.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
