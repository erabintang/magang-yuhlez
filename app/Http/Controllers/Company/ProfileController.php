<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\Notification;
use App\Models\Certificate;
use App\Models\ProgramIntern;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $company = Auth::user()->companyProfile;

        if (!$company) {
            return redirect()->route('company.dashboard')
                ->with('error', 'Profil perusahaan belum dibuat.');
        }

        return view('dashboard.company.profile.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'gmap_embed' => 'nullable|string',
            'gmail_access' => 'required|string|max:255',
            'logo_file_id' => 'nullable|string|max:36',
        ]);

        $company->update($validated);

        // Create notification + email
        Notification::create([
            'user_id' => Auth::id(),
            'type' => 'PROFILE_UPDATE',
            'title' => 'Profil Diperbarui',
            'message' => 'Profil perusahaan berhasil diperbarui.',
            'is_read' => false,
        ]);

        $toEmail = $company->contact_email ?? Auth::user()->email;
        if ($toEmail) {
            EmailService::sendProfileUpdatedEmail($toEmail, $company->name);
        }

        return redirect()->route('company.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function notifications()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->count();

        return view('dashboard.company.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead(string $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllNotificationsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function certificates()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $certificates = Certificate::with(['program', 'intern', 'file'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard.company.certificates.index', compact('certificates'));
    }

    public function interns()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $programs = $company->programs()->with('programInterns.intern')
            ->whereNull('deleted_at')
            ->latest()
            ->limit(100)
            ->get();

        $participants = [];
        foreach ($programs as $program) {
            $activeInterns = $program->programInterns->whereNull('removed_at');
            if ($activeInterns->isNotEmpty()) {
                $participants[] = ['program' => $program, 'interns' => $activeInterns];
            }
        }

        return view('dashboard.company.interns.index', compact('participants'));
    }

    public function removeIntern(string $programId, string $internId)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $programIntern = ProgramIntern::where('program_id', $programId)
            ->where('intern_id', $internId)
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->firstOrFail();

        $programIntern->update(['removed_at' => now()]);
        $programIntern->delete();

        return back()->with('success', 'Peserta berhasil dikeluarkan dari program.');
    }
}
