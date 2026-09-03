<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BaseController extends Controller
{
    /**
     * Get the authenticated user's profile with role-specific profile loaded.
     */
    protected function getUserProfile()
    {
        $user = Auth::user();
        if (!$user) return null;

        return match($user->role) {
            'ROOT' => $user,
            'COMPANY' => $user->load('companyProfile'),
            'INTERN' => $user->load('internProfile'),
            default => $user,
        };
    }

    /**
     * Count unread notifications for the authenticated user.
     */
    protected function getUnreadNotificationCount(): int
    {
        if (!Auth::check()) return 0;

        return \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check if intern profile is complete enough for applications.
     */
    protected function isInternProfileComplete(\App\Models\InternProfile $intern): bool
    {
        return filled($intern->name)
            && filled($intern->whatsapp)
            && filled($intern->contact_email)
            && filled($intern->address)
            && filled($intern->gmail_access)
            && filled($intern->cv_file_id);
    }
}
