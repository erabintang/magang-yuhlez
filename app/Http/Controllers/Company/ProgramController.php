<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\InternshipProgram;
use App\Models\InternshipPosition;
use App\Models\InternProfile;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }
        $programs = InternshipProgram::with(['positions', 'applications'])
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(10);

        return view('dashboard.company.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('dashboard.company.programs.create');
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
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'program_start' => 'required|date|after:registration_end',
            'program_end' => 'required|date|after:program_start',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.description' => 'nullable|string',
            'positions.*.quota' => 'nullable|integer|min:1',
        ], [
            'registration_end.after' => 'Tanggal tutup pendaftaran harus setelah tanggal buka pendaftaran.',
            'program_start.after' => 'Tanggal mulai program harus setelah tanggal tutup pendaftaran.',
            'program_end.after' => 'Tanggal selesai program harus setelah tanggal mulai program.',
        ]);

        $slug = $this->generateSlug($validated['title']);

        $program = InternshipProgram::create([
            'company_id' => $company->id,
            'slug' => $slug,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'registration_start' => $validated['registration_start'],
            'registration_end' => $validated['registration_end'],
            'program_start' => $validated['program_start'],
            'program_end' => $validated['program_end'],
        ]);

        foreach ($validated['positions'] as $position) {
            InternshipPosition::create([
                'program_id' => $program->id,
                'name' => $position['name'],
                'description' => $position['description'] ?? null,
                'quota' => $position['quota'] ?? null,
            ]);
        }

        // Notify ALL interns about new program (use chunk to avoid memory issues)
        InternProfile::with('user')->whereNull('deleted_at')
            ->chunk(50, function ($interns) use ($validated, $company, $slug) {
                foreach ($interns as $intern) {
                    Notification::create([
                        'user_id' => $intern->user_id,
                        'type' => 'NEW_PROGRAM',
                        'title' => 'Program Magang Baru',
                        'message' => "'{$validated['title']}' dibuka oleh {$company->name}.",
                        'is_read' => false,
                    ]);

                    $toEmail = $intern->contact_email ?? ($intern->user->email ?? null);
                    if ($toEmail) {
                        EmailService::sendNewProgramEmail(
                            $toEmail,
                            $intern->name,
                            $validated['title'],
                            $company->name,
                            $slug
                        );
                    }
                }
            });

        return redirect()->route('company.programs.show', $slug)
            ->with('success', 'Program magang berhasil dibuat.');
    }

    public function show(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $program = InternshipProgram::with(['positions', 'applications.intern', 'applications.position'])
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.programs.show', compact('program'));
    }

    public function edit(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $program = InternshipProgram::with('positions')
            ->where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.programs.edit', compact('program'));
    }

    public function update(Request $request, string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $program = InternshipProgram::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'program_start' => 'required|date|after:registration_end',
            'program_end' => 'required|date|after:program_start',
        ], [
            'registration_end.after' => 'Tanggal tutup pendaftaran harus setelah tanggal buka pendaftaran.',
            'program_start.after' => 'Tanggal mulai program harus setelah tanggal tutup pendaftaran.',
            'program_end.after' => 'Tanggal selesai program harus setelah tanggal mulai program.',
        ]);

        $program->update($validated);

        // Notify enrolled interns + email
        $programInterns = $program->programInterns()->with('intern.user')->get();
        foreach ($programInterns as $pi) {
            Notification::create([
                'user_id' => $pi->intern->user_id,
                'type' => 'PROGRAM_UPDATE',
                'title' => 'Program Diperbarui',
                'message' => "Program magang '{$program->title}' baru saja diperbarui.",
                'is_read' => false,
            ]);

            $toEmail = $pi->intern->contact_email ?? ($pi->intern->user->email ?? null);
            if ($toEmail) {
                EmailService::sendProgramUpdatedEmail(
                    $toEmail,
                    $pi->intern->name,
                    $program->title,
                    $program->slug
                );
            }
        }

        return redirect()->route('company.programs.show', $slug)
            ->with('success', 'Program magang berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }
        $program = InternshipProgram::where('company_id', $company->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $program->delete();

        return redirect()->route('company.programs.index')
            ->with('success', 'Program magang berhasil dihapus.');
    }

    protected function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (InternshipProgram::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
}
