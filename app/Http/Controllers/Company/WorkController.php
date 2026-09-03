<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkGallery;
use App\Models\WorkIntern;
use App\Models\InternProfile;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkController extends Controller
{
    public function index()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }
        $works = Work::with(['gallery.file', 'interns.intern'])
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        return view('dashboard.company.works.index', compact('works'));
    }

    public function create()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $interns = InternProfile::whereHas('programInterns.program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->get();

        return view('dashboard.company.works.create', compact('interns'));
    }

    public function store(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'source_code_url' => 'nullable|url|max:500',
            'deploy_url' => 'nullable|url|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = $this->generateSlug($validated['title']);

        $work = Work::create([
            'company_id' => $company->id,
            'work_type' => 'PROGRAM_WORK',
            'slug' => $slug,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'year' => $validated['year'] ?? null,
            'is_published' => $validated['is_published'] ?? false,
        ]);

        // Notify interns who will be participants later (when added)
        // For now, notification is sent when intern is added via addIntern()

        return redirect()->route('company.works.show', $slug)
            ->with('success', 'Karya berhasil dibuat.');
    }

    public function show(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::with(['gallery.file', 'interns.intern'])
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $interns = InternProfile::whereHas('programInterns.program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->get();

        return view('dashboard.company.works.show', compact('work', 'interns'));
    }

    public function edit(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.works.edit', compact('work'));
    }

    public function update(Request $request, string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'source_code_url' => 'nullable|url|max:500',
            'deploy_url' => 'nullable|url|max:500',
            'is_published' => 'boolean',
        ]);

        $work->update($validated);

        return redirect()->route('company.works.show', $slug)
            ->with('success', 'Karya berhasil diperbarui.');
    }

    public function toggle(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $work->update([
            'is_published' => !$work->is_published,
            'published_at' => !$work->is_published ? now() : null,
        ]);

        $status = $work->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Karya berhasil {$status}.");
    }

    public function destroy(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $work->delete();

        return redirect()->route('company.works.index')
            ->with('success', 'Karya berhasil dihapus.');
    }

    public function addGallery(Request $request, string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $request->validate([
            'file_id' => 'required|exists:files,id',
        ]);

        $maxOrder = $work->gallery->max('sort_order') ?? 0;

        WorkGallery::create([
            'work_id' => $work->id,
            'file_id' => $request->file_id,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    public function removeGallery(string $slug, string $galleryId)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $gallery = WorkGallery::where('work_id', $work->id)
            ->where('id', $galleryId)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus dari galeri.');
    }

    public function addIntern(Request $request, string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $request->validate([
            'intern_id' => 'required|exists:intern_profiles,id',
        ]);

        $intern = InternProfile::findOrFail($request->intern_id);

        // Check if intern participates in company's program
        $hasRelation = $intern->programInterns()
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->exists();

        if (!$hasRelation) {
            return back()->with('error', 'Intern tidak memiliki hubungan dengan perusahaan ini.');
        }

        WorkIntern::firstOrCreate(
            ['work_id' => $work->id, 'intern_id' => $intern->id],
            ['added_at' => now()]
        );

        // Notify intern + email
        Notification::create([
            'user_id' => $intern->user_id,
            'type' => 'WORK_ADDED',
            'title' => 'Karya Baru',
            'message' => "Kamu ditambahkan sebagai peserta karya '{$work->title}' oleh {$company->name}.",
            'is_read' => false,
        ]);

        $toEmail = $intern->contact_email ?? ($intern->user->email ?? null);
        if ($toEmail) {
            EmailService::sendWorkCreatedEmail(
                $toEmail,
                $intern->name,
                $work->title,
                $company->name
            );
        }

        return back()->with('success', 'Intern berhasil ditambahkan ke karya.');
    }

    public function removeIntern(string $slug, string $internId)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $work = Work::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $workIntern = WorkIntern::where('work_id', $work->id)
            ->where('intern_id', $internId)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $workIntern->update(['removed_at' => now()]);
        $workIntern->delete();

        return back()->with('success', 'Intern berhasil dihapus dari karya.');
    }

    protected function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Work::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

}
