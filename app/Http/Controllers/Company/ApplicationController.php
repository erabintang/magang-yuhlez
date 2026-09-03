<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\ProgramIntern;
use App\Models\ApplicationStatusHistory;
use App\Models\Notification;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EmailService;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $query = InternshipApplication::with(['intern', 'program', 'position'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderByDesc('applied_at')->paginate(20);

        return view('dashboard.company.applications.index', compact('applications'));
    }

    public function show(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $application = InternshipApplication::with(['intern', 'program', 'position', 'histories'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.applications.show', compact('application'));
    }

    public function accept(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $user = Auth::user();

        $application = InternshipApplication::with(['intern', 'program', 'position'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        if ($application->status !== 'PENDING') {
            return back()->with('error', 'Pendaftaran sudah diproses.');
        }

        // Update status
        $application->update([
            'status' => 'ACCEPTED',
            'reviewed_at' => now(),
            'reviewed_by' => $user->id,
        ]);

        // Create history
        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => 'PENDING',
            'new_status' => 'ACCEPTED',
            'changed_by' => $user->id,
        ]);

        // Create program intern record
        ProgramIntern::firstOrCreate(
            [
                'program_id' => $application->program_id,
                'intern_id' => $application->intern_id,
            ],
            [
                'application_id' => $application->id,
                'joined_at' => now(),
            ]
        );

        // Create notification
        Notification::create([
            'user_id' => $application->intern->user_id,
            'type' => 'APPLICATION_ACCEPTED',
            'title' => 'Pendaftaran Diterima',
            'message' => "Selamat! Pendaftaran kamu untuk program '{$application->program->title}' diterima.",
            'is_read' => false,
        ]);

        // Send email
        $toEmail = $application->intern->contact_email ?? $application->intern->user->email;
        if ($toEmail) {
            EmailService::sendApplicationStatusEmail(
                $toEmail,
                $application->intern->name,
                $application->program->title,
                'ACCEPTED',
                null,
                $company->name
            );
        }

        return back()->with('success', 'Pendaftaran berhasil diterima.');
    }

    public function reject(Request $request, string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $user = Auth::user();

        $application = InternshipApplication::with(['intern', 'program', 'position'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        if ($application->status !== 'PENDING') {
            return back()->with('error', 'Pendaftaran sudah diproses.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        // Update status
        $application->update([
            'status' => 'REJECTED',
            'reviewed_at' => now(),
            'reviewed_by' => $user->id,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Create history
        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => 'PENDING',
            'new_status' => 'REJECTED',
            'reason' => $validated['rejection_reason'],
            'changed_by' => $user->id,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $application->intern->user_id,
            'type' => 'APPLICATION_REJECTED',
            'title' => 'Pendaftaran Ditolak',
            'message' => "Pendaftaran kamu untuk program '{$application->program->title}' ditolak.",
            'is_read' => false,
        ]);

        // Send email
        $toEmail = $application->intern->contact_email ?? $application->intern->user->email;
        if ($toEmail) {
            EmailService::sendApplicationStatusEmail(
                $toEmail,
                $application->intern->name,
                $application->program->title,
                'REJECTED',
                $validated['rejection_reason'],
                $company->name
            );
        }

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }
}
