<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\InternshipProgram;
use App\Models\InternshipPosition;
use App\Models\ApplicationStatusHistory;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $applications = InternshipApplication::with(['program', 'position'])
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->orderByDesc('applied_at')
            ->paginate(20);

        return view('dashboard.intern.applications.index', compact('applications'));
    }

    public function show(string $id)
    {
        $intern = Auth::user()->internProfile;

        $application = InternshipApplication::with(['program.company', 'position', 'histories'])
            ->where('intern_id', $intern->id)
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.intern.applications.show', compact('application'));
    }

    public function store(Request $request)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        // Cek profil lengkap sebelum bisa daftar
        if (!$this->isProfileComplete($intern)) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Profil harus lengkap sebelum mendaftar magang.\n\nYang harus dilengkapi: Nama, Deskripsi, Foto Profil, No WA, Email, Alamat, Gmail Akses, dan CV.');
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:internship_programs,id',
            'position_id' => 'required|exists:internship_positions,id',
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        $program = InternshipProgram::whereNull('deleted_at')->findOrFail($validated['program_id']);
        $position = InternshipPosition::whereNull('deleted_at')->findOrFail($validated['position_id']);

        // Check registration window
        $now = now();
        if ($now < $program->registration_start || $now > $program->registration_end) {
            return back()->with('error', 'Pendaftaran untuk program ini sedang ditutup.');
        }

        // Check position belongs to program
        if ($position->program_id !== $program->id) {
            return back()->with('error', 'Posisi tidak valid untuk program ini.');
        }

        // Check duplicate application
        $existing = InternshipApplication::where('intern_id', $intern->id)
            ->where('program_id', $program->id)
            ->whereNull('deleted_at')
            ->whereIn('status', ['PENDING', 'ACCEPTED'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah memiliki pendaftaran aktif untuk program ini.');
        }

        // Check quota
        if ($position->quota !== null) {
            $acceptedCount = InternshipApplication::where('position_id', $position->id)
                ->where('status', 'ACCEPTED')
                ->whereNull('deleted_at')
                ->count();

            if ($acceptedCount >= $position->quota) {
                return back()->with('error', 'Kuota posisi ini sudah penuh.');
            }
        }

        $application = InternshipApplication::create([
            'program_id' => $program->id,
            'position_id' => $position->id,
            'intern_id' => $intern->id,
            'status' => 'PENDING',
            'cover_letter' => $validated['cover_letter'],
            'applied_at' => now(),
        ]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => null,
            'new_status' => 'PENDING',
            'changed_by' => Auth::id(),
        ]);

        // Notify company + email
        $companyUser = $program->company->user;
        if ($companyUser) {
            Notification::create([
                'user_id' => $companyUser->id,
                'type' => 'APPLICATION_RECEIVED',
                'title' => 'Pendaftar Baru',
                'message' => "{$intern->name} mendaftar untuk posisi {$position->name} di program {$program->title}.",
                'is_read' => false,
            ]);

            $companyEmail = $program->company->contact_email ?? ($companyUser->email ?? null);
            if ($companyEmail) {
                EmailService::sendApplicationSubmittedEmail(
                    $companyEmail,
                    $program->company->name,
                    $intern->name,
                    $program->title,
                    $position->name
                );
            }
        }

        return redirect()->route('intern.applications.index')
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function cancel(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $application = InternshipApplication::where('intern_id', $intern->id)
            ->where('id', $id)
            ->where('status', 'PENDING')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $application->update(['status' => 'CANCELLED']);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => 'PENDING',
            'new_status' => 'CANCELLED',
            'changed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    /**
     * Cek apakah profil intern sudah lengkap
     * Required: name, short_description, whatsapp, contact_email, address, gmail_access, cv_file_id
     */
    protected function isProfileComplete($intern): bool
    {
        return !empty($intern->name)
            && !empty($intern->short_description)
            && !empty($intern->whatsapp)
            && !empty($intern->contact_email)
            && !empty($intern->address)
            && !empty($intern->gmail_access)
            && !empty($intern->cv_file_id);
    }
}
