<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\InternProfile;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show INTERN registration form.
     */
    public function showInternForm()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return $this->redirectByRole(\Illuminate\Support\Facades\Auth::user());
        }

        return view('auth.register-intern');
    }

    /**
     * Show COMPANY registration form.
     */
    public function showCompanyForm()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return $this->redirectByRole(\Illuminate\Support\Facades\Auth::user());
        }

        return view('auth.register-company');
    }

    /**
     * Handle INTERN registration.
     */
    public function registerIntern(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create profile with role INTERN
        $profile = Profile::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'INTERN',
            'password_hash' => Hash::make($validated['password']),
        ]);

        // Create intern profile
        InternProfile::create([
            'id' => Str::uuid(),
            'user_id' => $profile->id,
            'name' => $validated['name'],
            'slug' => $this->generateSlug(InternProfile::class, $validated['name']),
            'contact_email' => $validated['email'],
            'gmail_access' => $validated['email'],
        ]);

        // Auto login
        \Illuminate\Support\Facades\Auth::login($profile, true);

        return redirect()->route('intern.dashboard')
            ->with('success', 'Akun intern berhasil dibuat. Selamat datang di YUHLEZ!');
    }

    /**
     * Handle COMPANY registration.
     */
    public function registerCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create profile with role COMPANY
        $profile = Profile::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'COMPANY',
            'password_hash' => Hash::make($validated['password']),
        ]);

        // Create company profile
        CompanyProfile::create([
            'id' => Str::uuid(),
            'user_id' => $profile->id,
            'slug' => $this->generateSlug(CompanyProfile::class, $validated['name']),
            'name' => $validated['name'],
            'contact_email' => $validated['email'],
            'gmail_access' => $validated['email'],
        ]);

        // Auto login
        \Illuminate\Support\Facades\Auth::login($profile, true);

        return redirect()->route('company.dashboard')
            ->with('success', 'Akun perusahaan berhasil dibuat. Selamat datang di YUHLEZ!');
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
