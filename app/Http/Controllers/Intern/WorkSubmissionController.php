<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkSubmission;
use App\Models\WorkSubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkSubmissionController extends Controller
{
    /**
     * List all submissions by this intern.
     */
    public function index()
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $submissions = WorkSubmission::with(['work.company', 'files.file'])
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        // Get works that intern participates in (for upload modal)
        $works = \App\Models\Work::whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->with(['company:id,name'])
            ->whereNull('deleted_at')
            ->where('is_published', true)
            ->get();
        $worksCount = $works->count();

        return view('dashboard.intern.submissions.index', compact('submissions', 'works', 'worksCount'));
    }

    /**
     * Show submission detail.
     */
    public function show(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $submission = WorkSubmission::with(['work.company', 'files.file', 'reviewer'])
            ->where('intern_id', $intern->id)
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.intern.submissions.show', compact('submission'));
    }

    /**
     * Form to submit work for a specific work project.
     */
    public function create(string $slug)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        // Find work that intern is participating in
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Verify intern is assigned to this work
        $isAssigned = \App\Models\WorkIntern::where('work_id', $work->id)
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak terdaftar sebagai peserta karya ini.');
        }

        return view('dashboard.intern.submissions.create', compact('work', 'intern'));
    }

    /**
     * Store submission with chunked-uploaded files.
     */
    public function store(Request $request, string $slug)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Verify intern is assigned
        $isAssigned = \App\Models\WorkIntern::where('work_id', $work->id)
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->exists();

        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak terdaftar sebagai peserta karya ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'tech_stack' => 'nullable|string|max:500',
            'file_ids' => 'required|array|min:1',
            'file_ids.*' => 'exists:files,id',
        ]);

        // Create submission
        $submission = WorkSubmission::create([
            'work_id' => $work->id,
            'intern_id' => $intern->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'tech_stack' => $validated['tech_stack'] ?? null,
            'status' => WorkSubmission::STATUS_PENDING,
        ]);

        // Attach files
        foreach ($validated['file_ids'] as $index => $fileId) {
            WorkSubmissionFile::create([
                'submission_id' => $submission->id,
                'file_id' => $fileId,
                'sort_order' => $index,
            ]);
        }

        // Notify company
        $companyUser = $work->company->user;
        if ($companyUser) {
            \App\Models\Notification::create([
                'user_id' => $companyUser->id,
                'type' => 'WORK_SUBMISSION_RECEIVED',
                'title' => 'Karya Baru dari Intern',
                'message' => "{$intern->name} mengirim karya \"{$validated['title']}\" untuk karya \"{$work->title}\".",
                'is_read' => false,
            ]);

            // Email notification
            $companyEmail = $work->company->contact_email ?? ($companyUser->email ?? null);
            if ($companyEmail) {
                \App\Services\EmailService::sendWorkSubmissionReceivedEmail(
                    $companyEmail,
                    $work->company->name,
                    $intern->name,
                    $validated['title'],
                    $work->title
                );
            }
        }

        return redirect()->route('intern.submissions.show', $submission->id)
            ->with('success', 'Karya berhasil dikirim! Menunggu review dari perusahaan.');
    }
}
