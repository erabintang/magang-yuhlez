<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $certificates = Certificate::with(['program', 'program.company', 'file'])
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->orderByDesc('issued_at')
            ->paginate(20);

        // Get other interns who share the same certificate file (for shared certificates)
        $certificatesWithPeers = $certificates->getCollection()->map(function ($cert) {
            if ($cert->file_id) {
                // Find all certificates that share the same file_id
                $peerInternIds = Certificate::where('file_id', $cert->file_id)
                    ->where('id', '!=', $cert->id)
                    ->whereNull('deleted_at')
                    ->pluck('intern_id')
                    ->toArray();

                if (!empty($peerInternIds)) {
                    $peers = \App\Models\InternProfile::whereIn('id', $peerInternIds)
                        ->pluck('name')
                        ->toArray();
                    $cert->peers = $peers;
                } else {
                    $cert->peers = [];
                }
            } else {
                $cert->peers = [];
            }
            return $cert;
        });

        $certificates->setCollection($certificatesWithPeers);

        return view('dashboard.intern.certificates.index', compact('certificates'));
    }

    public function download(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $certificate = Certificate::with(['program', 'file'])
            ->where('intern_id', $intern->id)
            ->where('id', $id)
            ->where('status', 'ISSUED')
            ->whereNull('deleted_at')
            ->firstOrFail();

        if (!$certificate->file) {
            return back()->with('error', 'File sertifikat tidak tersedia.');
        }

        // Generate URL from local storage
        $signedUrl = \App\Services\StorageService::createSignedUrl(
            $certificate->file->bucket_name,
            $certificate->file->storage_path,
            3600
        );

        return redirect($signedUrl);
    }

    /**
     * Export certificate as PDF using DomPDF.
     */
    public function pdf(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $certificate = Certificate::with(['program.company', 'program.programInterns'])
            ->where('intern_id', $intern->id)
            ->where('id', $id)
            ->where('status', 'ISSUED')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'sertifikat-' . Str::slug($certificate->program->title ?? 'magang') . '.pdf';

        return $pdf->download($filename);
    }
}
