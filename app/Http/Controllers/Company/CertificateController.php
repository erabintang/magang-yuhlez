<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\File;
use App\Models\ProgramIntern;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * List certificates for company's programs
     */
    public function index(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $query = Certificate::with(['program', 'intern', 'file'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $certificates = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.company.certificates.index', compact('certificates'));
    }

    /**
     * Show certificate detail
     */
    public function show(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $certificate = Certificate::with(['program', 'intern', 'file'])
            ->where('id', $id)
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.certificates.show', compact('certificate'));
    }

    /**
     * Form untuk issue sertifikat ke multiple interns
     * Company pilih intern mana yang layak terima sertifikat + upload PDF
     */
    public function create()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        // Ambil semua program yang sudah selesai
        $programs = $company->programs()
            ->where('program_end', '<=', now())
            ->whereNull('deleted_at')
            ->with(['programInterns.intern'])
            ->get();

        return view('dashboard.company.certificates.create', compact('programs'));
    }

    /**
     * Company pilih intern + upload PDF → buat sertifikat untuk setiap intern yang dipilih
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:internship_programs,id',
            'intern_ids' => 'required|array|min:1',
            'intern_ids.*' => 'exists:intern_profiles,id',
            'file_id' => 'required|exists:files,id', // chunked upload file ID
        ]);

        // Pastikan program milik company ini
        $program = $company->programs()
            ->where('id', $validated['program_id'])
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Ambil file record dari chunked upload
        $fileRecord = File::where('id', $validated['file_id'])->firstOrFail();

        $issuedCount = 0;

        // Buat sertifikat untuk setiap intern yang dipilih
        foreach ($validated['intern_ids'] as $internId) {
            // Cek apakah intern benar-benar peserta program ini
            $isParticipant = ProgramIntern::where('program_id', $program->id)
                ->where('intern_id', $internId)
                ->whereNull('deleted_at')
                ->exists();

            if (!$isParticipant) {
                continue; // Skip intern yang bukan peserta program ini
            }

            // Cek apakah sudah punya sertifikat untuk program ini
            $existing = Certificate::where('program_id', $program->id)
                ->where('intern_id', $internId)
                ->whereNull('deleted_at')
                ->exists();

            if ($existing) {
                continue; // Skip kalau sudah ada
            }

            // Generate nomor sertifikat unik
            $number = 'YUHLEZ-' . date('Y') . '-' . str_pad(Certificate::count() + $issuedCount + 1, 5, '0', STR_PAD_LEFT);

            // Buat record sertifikat
            $certificate = Certificate::create([
                'program_id' => $program->id,
                'intern_id' => $internId,
                'file_id' => $fileRecord->id,
                'certificate_number' => $number,
                'status' => 'ISSUED',
                'issued_at' => now(),
            ]);

            $issuedCount++;

            // Ambil data intern untuk notifikasi
            $intern = \App\Models\InternProfile::with('user')->find($internId);

            if ($intern) {
                // Notifikasi in-app
                Notification::create([
                    'user_id' => $intern->user_id,
                    'type' => 'CERTIFICATE_AVAILABLE',
                    'title' => 'Sertifikat Tersedia',
                    'message' => "Sertifikat untuk program '{$program->title}' telah diterbitkan. Nomor: {$number}",
                    'is_read' => false,
                ]);

                // Kirim email
                $toEmail = $intern->contact_email ?? ($intern->user->email ?? null);
                if ($toEmail) {
                    EmailService::sendCertificateIssuedEmail(
                        $toEmail,
                        $intern->name,
                        $program->title,
                        $company->name
                    );
                }
            }
        }

        return redirect()->route('company.certificates.index')
            ->with('success', "Berhasil menerbitkan {$issuedCount} sertifikat.");
    }

    /**
     * Upload certificate file untuk 1 intern saja (single issue)
     */
    public function issue(Request $request, string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $certificate = Certificate::with(['program', 'intern'])
            ->where('id', $id)
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
            ->where('status', 'ELIGIBLE')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'certificate_file' => 'required|file|mimes:pdf|max:5120', // max 5MB
            'certificate_number' => 'nullable|string|max:100',
        ]);

        // Upload file to local storage
        $file = $request->file('certificate_file');
        $bucket = 'certificates';
        $storagePath = $certificate->id . '_' . time() . '.pdf';

        \App\Services\StorageService::upload(
            $bucket,
            $storagePath,
            file_get_contents($file->getRealPath()),
            'application/pdf'
        );

        // Create file record
        $fileRecord = File::create([
            'bucket_name' => $bucket,
            'storage_path' => $storagePath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => 'application/pdf',
            'size_bytes' => $file->getSize(),
            'uploaded_by' => Auth::id(),
            'created_at' => now(),
        ]);

        // Generate certificate number
        $number = $validated['certificate_number'] ?? ('YUHLEZ-' . date('Y') . '-' . str_pad(Certificate::count() + 1, 5, '0', STR_PAD_LEFT));

        $certificate->update([
            'status' => 'ISSUED',
            'certificate_number' => $number,
            'file_id' => $fileRecord->id,
            'issued_at' => now(),
        ]);

        // Create notification for intern
        Notification::create([
            'user_id' => $certificate->intern->user_id,
            'type' => 'CERTIFICATE_AVAILABLE',
            'title' => 'Sertifikat Tersedia',
            'message' => "Sertifikat untuk program '{$certificate->program->title}' telah diterbitkan. Nomor: {$number}",
            'is_read' => false,
        ]);

        // Send email notification
        $toEmail = $certificate->intern->contact_email ?? $certificate->intern->user->email;
        if ($toEmail) {
            EmailService::sendCertificateIssuedEmail(
                $toEmail,
                $certificate->intern->name,
                $certificate->program->title,
                $company->name
            );
        }

        return back()->with('success', "Sertifikat berhasil diupload dan diterbitkan. Nomor: {$number}");
    }
}
