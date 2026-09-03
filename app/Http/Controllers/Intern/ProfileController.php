<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\InternProfile;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $intern = Auth::user()->internProfile;

        if (!$intern) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Profil intern belum dibuat.');
        }

        return view('dashboard.intern.profile.edit', compact('intern'));
    }

    public function update(Request $request)
    {
        $intern = Auth::user()->internProfile;
        $user = Auth::user();

        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Profil intern belum dibuat.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'address' => 'nullable|string|max:500',
            'gmail_access' => 'nullable|string|max:255',
            'profile_photo_file_id' => 'nullable|string|max:36',
            'cv_file_id' => 'nullable|string|max:36',
        ]);

        $intern->update($validated);

        // Create notification
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'PROFILE_UPDATE',
            'title' => 'Profil Diperbarui',
            'message' => 'Profil berhasil diperbarui.',
            'is_read' => false,
        ]);

        // Send email notification
        $toEmail = $validated['contact_email'] ?? $user->email;
        if ($toEmail) {
            EmailService::sendProfileUpdatedEmail($toEmail, $validated['name']);
        }

        return redirect()->route('intern.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
