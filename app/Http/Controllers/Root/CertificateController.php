<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Notification;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['program', 'program.company', 'intern', 'file'])
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $certificates = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.root.certificates.index', compact('certificates'));
    }

    public function show(string $id)
    {
        $certificate = Certificate::with(['program', 'program.company', 'intern', 'file'])
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.certificates.show', compact('certificate'));
    }

    // NOTE: Issue sertifikat dilakukan oleh COMPANY, bukan ROOT
    // ROOT hanya bisa melihat/mengawasi sertifikat
}
