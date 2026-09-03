<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\WorkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkSubmissionController extends Controller
{
    /**
     * List all submissions for this company's works.
     */
    public function index(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $query = WorkSubmission::with(['work', 'intern', 'files.file'])
            ->whereHas('work', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.company.submissions.index', compact('submissions'));
    }

    /**
     * Show submission detail with files.
     */
    public function show(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $submission = WorkSubmission::with(['work', 'intern', 'files.file', 'reviewer'])
            ->where('id', $id)
            ->whereHas('work', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.submissions.show', compact('submission'));
    }

    /**
     * Accept a submission.
     */
    public function accept(Request $request, string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $submission = WorkSubmission::with(['work', 'intern'])
            ->where('id', $id)
            ->whereHas('work', fn($q) => $q->where('company_id', $company->id))
            ->where('status', WorkSubmission::STATUS_PENDING)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'review_note' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'status' => WorkSubmission::STATUS_ACCEPTED,
            'review_note' => $validated['review_note'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notify intern
        \App\Models\Notification::create([
            'user_id' => $submission->intern->user_id,
            'type' => 'WORK_SUBMISSION_ACCEPTED',
            'title' => 'Karya Diterima',
            'message' => "Karya \"{$submission->title}\" untuk \"{$submission->work->title}\" telah diterima.",
            'is_read' => false,
        ]);

        // Email notification
        $toEmail = $submission->intern->contact_email ?? ($submission->intern->user->email ?? null);
        if ($toEmail) {
            \App\Services\EmailService::sendWorkSubmissionStatusEmail(
                $toEmail,
                $submission->intern->name,
                $submission->title,
                $submission->work->title,
                'ACCEPTED',
                $validated['review_note'] ?? null,
                $company->name
            );
        }

        return back()->with('success', 'Karya berhasil diterima!');
    }

    /**
     * Reject a submission.
     */
    public function reject(Request $request, string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $submission = WorkSubmission::with(['work', 'intern'])
            ->where('id', $id)
            ->whereHas('work', fn($q) => $q->where('company_id', $company->id))
            ->where('status', WorkSubmission::STATUS_PENDING)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'review_note' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => WorkSubmission::STATUS_REJECTED,
            'review_note' => $validated['review_note'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notify intern
        \App\Models\Notification::create([
            'user_id' => $submission->intern->user_id,
            'type' => 'WORK_SUBMISSION_REJECTED',
            'title' => 'Karya Ditolak',
            'message' => "Karya \"{$submission->title}\" untuk \"{$submission->work->title}\" ditolak. Alasan: {$validated['review_note']}",
            'is_read' => false,
        ]);

        // Email notification
        $toEmail = $submission->intern->contact_email ?? ($submission->intern->user->email ?? null);
        if ($toEmail) {
            \App\Services\EmailService::sendWorkSubmissionStatusEmail(
                $toEmail,
                $submission->intern->name,
                $submission->title,
                $submission->work->title,
                'REJECTED',
                $validated['review_note'],
                $company->name
            );
        }

        return back()->with('success', 'Karya ditolak.');
    }
}
