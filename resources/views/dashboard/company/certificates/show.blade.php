@extends('layouts.dashboard')
@section('page-title', 'Detail Sertifikat')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <a href="{{ route('company.certificates.index') }}" class="text-sm text-yellow-600 hover:underline">&larr; Kembali ke Daftar Sertifikat</a>
        <h2 class="text-2xl font-bold text-zinc-900 mt-1">Detail Sertifikat</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Certificate Info --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Informasi Sertifikat</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-500">Status</p>
                    @if($certificate->status === 'ELIGIBLE')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Siap Diupload</span>
                    @elseif($certificate->status === 'ISSUED')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Diterbitkan</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">{{ $certificate->status }}</span>
                    @endif
                </div>
                @if($certificate->certificate_number)
                <div>
                    <p class="text-xs text-zinc-500">Nomor Sertifikat</p>
                    <p class="font-medium">{{ $certificate->certificate_number }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-zinc-500">Program</p>
                    <p class="font-medium">{{ $certificate->program->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Tanggal Diterbitkan</p>
                    <p class="font-medium">{{ $certificate->issued_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Intern Info --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Informasi Intern</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-500">Nama</p>
                    <p class="font-medium">{{ $certificate->intern->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Email</p>
                    <p class="font-medium">{{ $certificate->intern->contact_email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">WhatsApp</p>
                    <p class="font-medium">{{ $certificate->intern->whatsapp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Form (jika ELIGIBLE) --}}
    @if($certificate->status === 'ELIGIBLE')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-2">Upload Sertifikat</h3>
            <p class="text-sm text-zinc-500 mb-4">Upload file PDF sertifikat yang sudah Anda buat untuk intern ini.</p>
            <form action="{{ route('company.certificates.issue', $certificate->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Upload sertifikat ini?')">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Nomor Sertifikat (opsional)</label>
                        <input type="text" name="certificate_number" placeholder="Contoh: YUHLEZ-2026-00001"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">File Sertifikat (PDF, maks 5MB) <span class="text-red-500">*</span></label>
                        <input type="file" name="certificate_file" accept=".pdf" required
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-400 file:px-4 file:py-1 file:text-sm file:font-semibold file:text-zinc-950 hover:file:bg-yellow-500">
                        @error('certificate_file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold">
                        📤 Upload & Terbitkan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($certificate->status === 'ISSUED' && $certificate->file)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">File Sertifikat</h3>
            <a href="{{ route('files.download', $certificate->file_id) }}" target="_blank"
                class="inline-flex items-center px-4 py-2 bg-yellow-400 text-zinc-900 rounded-xl hover:bg-yellow-500 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Sertifikat
            </a>
        </div>
    @endif
</div>
@endsection