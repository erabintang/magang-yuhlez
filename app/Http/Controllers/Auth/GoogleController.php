<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\InternProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth.
     * Supports ?intent=intern or ?intent=company to determine role on callback.
     */
    public function redirect(Request $request)
    {
        // Store intent in session for callback.
        // Intent can be 'intern', 'company', or null (login page — login only, no new account).
        $intent = $request->query('intent');
        if ($intent && !in_array($intent, ['intern', 'company'])) {
            $intent = null;
        }
        session(['google_oauth_intent' => $intent]);

        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver
            ->scopes(['openid', 'email', 'profile'])
            ->with([
                'access_type' => 'online',
                'prompt' => 'select_account',
                'redirect_uri' => config('services.google.redirect'),
            ])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Session state was lost during the OAuth redirect.
            // Redirect back to Google with the same intent so the user can retry seamlessly.
            \Illuminate\Support\Facades\Log::warning('Google OAuth InvalidStateException — re-initiating redirect', [
                'session_id' => session()->getId(),
                'session_driver' => config('session.driver'),
            ]);

            $intent = session('google_oauth_intent', 'intern');
            return redirect()->route('google.redirect', ['intent' => $intent]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth callback failed', [
                'error' => $e->getMessage(),
                'exception_class' => get_class($e),
                'redirect_uri_used' => config('services.google.redirect'),
                'request_host' => request()->getSchemeAndHttpHost(),
                'previous_url' => session()->get('url.intended'),
            ]);

            $intent = session('google_oauth_intent', 'intern');

            // If user already picked an account and the error is generic (e.g. access_denied),
            // show a message. Otherwise redirect to Google again.
            if (str_contains($e->getMessage(), 'access_denied')) {
                return redirect()->route('login')
                    ->with('error', 'Anda membatalkan login Google.');
            }

            return redirect()->route('google.redirect', ['intent' => $intent]);
        }

        $email = strtolower(trim($googleUser->getEmail()));
        $name = $googleUser->getName() ?? $email;
        $intent = session('google_oauth_intent') ?? 'intern';

        if (empty($email)) {
            return redirect()->route('login')
                ->with('error', 'Gagal mendapatkan email dari Google. Silakan coba lagi.');
        }

        // Find existing profile by email
        $profile = Profile::where('email', $email)->whereNull('deleted_at')->first();

        if ($profile) {
            // Account exists - login and redirect based on existing role
            // Do NOT change the role silently
            Auth::login($profile, true);
            session()->forget('google_oauth_intent');

            return match($profile->role) {
                'ROOT' => redirect()->route('root.dashboard'),
                'COMPANY' => redirect()->route('company.dashboard'),
                'INTERN' => redirect()->route('intern.dashboard'),
                default => redirect()->route('home'),
            };
        }

        // No existing account
        if (!$intent) {
            // Login page — account doesn't exist, redirect to registration
            session()->forget('google_oauth_intent');
            return redirect()->route('register.choice')
                ->with('error', 'Akun belum terdaftar. Silakan daftar terlebih dahulu.');
        }

        // Create based on intent (from registration pages only)
        if ($intent === 'company') {
            $profile = Profile::create([
                'id' => Str::uuid(),
                'name' => $name,
                'email' => $email,
                'role' => 'COMPANY',
                'password_hash' => null,
            ]);

            \App\Models\CompanyProfile::create([
                'id' => Str::uuid(),
                'user_id' => $profile->id,
                'slug' => $this->generateSlug(\App\Models\CompanyProfile::class, $name),
                'name' => $name,
                'contact_email' => $email,
                'gmail_access' => $email,
            ]);

            Auth::login($profile, true);
            session()->forget('google_oauth_intent');

            return redirect()->route('company.dashboard')
                ->with('success', 'Akun perusahaan berhasil dibuat. Silakan lengkapi profil perusahaan Anda.');
        }

        // Default: create as INTERN
        $profile = Profile::create([
            'id' => Str::uuid(),
            'name' => $name,
            'email' => $email,
            'role' => 'INTERN',
            'password_hash' => null,
        ]);

        InternProfile::create([
            'id' => Str::uuid(),
            'user_id' => $profile->id,
            'name' => $name,
            'slug' => $this->generateSlug(InternProfile::class, $name),
            'contact_email' => $email,
            'gmail_access' => $email,
        ]);

        Auth::login($profile, true);
        session()->forget('google_oauth_intent');

        return redirect()->route('intern.dashboard')
            ->with('success', 'Akun intern berhasil dibuat. Selamat datang di YUHLEZ!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    protected function generateSlug(string $modelClass, string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }


}
