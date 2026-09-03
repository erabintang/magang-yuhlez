<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternProfileIsComplete
{
    /**
     * Prevent intern from performing actions that require a complete profile.
     * Required fields: name, short_description, whatsapp, contact_email,
     *                  address, gmail_access, cv_file_id, profile_photo_file_id
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'INTERN') {
            return $next($request);
        }

        $intern = $user->internProfile;

        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Profil intern belum dibuat. Silakan lengkapi profil terlebih dahulu.');
        }

        if (!$this->isProfileComplete($intern)) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Profil harus lengkap sebelum mendaftar magang. Silakan lengkapi: Nama, Deskripsi, Foto Profil, No WA, Email, Alamat, Gmail Akses, dan CV.');
        }

        return $next($request);
    }

    /**
     * Check if all required intern profile fields are filled.
     */
    protected function isProfileComplete($intern): bool
    {
        return filled($intern->name)
            && filled($intern->short_description)
            && filled($intern->whatsapp)
            && filled($intern->contact_email)
            && filled($intern->address)
            && filled($intern->gmail_access)
            && filled($intern->cv_file_id)
            && filled($intern->profile_photo_file_id);
    }
}
