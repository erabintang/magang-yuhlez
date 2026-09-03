<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\ApplicationStatusHistory;
use App\Models\ProgramIntern;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = InternshipApplication::with(['intern', 'program', 'position'])
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderByDesc('applied_at')->paginate(20);

        return view('dashboard.root.applications.index', compact('applications'));
    }

    public function show(string $id)
    {
        $application = InternshipApplication::with(['intern', 'program', 'position', 'histories'])
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.applications.show', compact('application'));
    }

    public function accept(string $id)
    {
        $user = Auth::user();

        $application = InternshipApplication::with(['intern', 'program', 'position'])
            ->where('id', $id)
            ->where('status', 'PENDING')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $application->update([
            'status' => 'ACCEPTED',
            'reviewed_at' => now(),
            'reviewed_by' => $user->id,
        ]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => 'PENDING',
            'new_status' => 'ACCEPTED',
            'changed_by' => $user->id,
        ]);

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

        Notification::create([
            'user_id' => $application->intern->user_id,
            'type' => 'APPLICATION_ACCEPTED',
            'title' => 'Pendaftaran Diterima',
            'message' => "Selamat! Pendaftaran kamu untuk program '{$application->program->title}' diterima.",
            'is_read' => false,
        ]);

        $toEmail = $application->intern->contact_email ?? $application->intern->user->email;
        if ($toEmail) {
            EmailService::sendApplicationStatusEmail(
                $toEmail,
                $application->intern->name,
                $application->program->title,
                'ACCEPTED',
                null,
                $application->program->company->name
            );
        }

        return back()->with('success', 'Pendaftaran berhasil diterima.');
    }

    public function reject(Request $request, string $id)
    {
        $user = Auth::user();

        $application = InternshipApplication::with(['intern', 'program', 'position'])
            ->where('id', $id)
            ->where('status', 'PENDING')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application->update([
            'status' => 'REJECTED',
            'reviewed_at' => now(),
            'reviewed_by' => $user->id,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'old_status' => 'PENDING',
            'new_status' => 'REJECTED',
            'reason' => $validated['rejection_reason'],
            'changed_by' => $user->id,
        ]);

        Notification::create([
            'user_id' => $application->intern->user_id,
            'type' => 'APPLICATION_REJECTED',
            'title' => 'Pendaftaran Ditolak',
            'message' => "Pendaftaran kamu untuk program '{$application->program->title}' ditolak.",
            'is_read' => false,
        ]);

        $toEmail = $application->intern->contact_email ?? $application->intern->user->email;
        if ($toEmail) {
            EmailService::sendApplicationStatusEmail(
                $toEmail,
                $application->intern->name,
                $application->program->title,
                'REJECTED',
                $validated['rejection_reason'],
                $application->program->company->name
            );
        }

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }
}
