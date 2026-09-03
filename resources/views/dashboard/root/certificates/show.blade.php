@extends('layouts.dashboard')
@section('page-title', 'Detail Sertifikat - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('root.certificates.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">&larr; Kembali ke Sertifikat</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Detail Sertifikat</h1>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Informasi Sertifikat</h2>
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <dt class="text-zinc-500">Nomor Sertifikat</dt>
                    <dd class="mt-1 font-mono text-zinc-900">{{ $certificate->certificate_number ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Status</dt>
                    <dd class="mt-1">
                        @if($certificate->status === 'ISSUED')
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterbitkan</span>
                        @elseif($certificate->status === 'ELIGIBLE')
                            <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Eligible</span>
                        @else
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">{{ $certificate->status }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Tanggal Diterbitkan</dt>
                    <dd class="mt-1 text-zinc-900">{{ $certificate->issued_at?->format('d M Y H:i') ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Informasi Intern</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-zinc-500">Nama</p>
                    <p class="font-medium text-zinc-900">{{ $certificate->intern->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500">Program</p>
                    <p class="font-medium text-zinc-900">{{ $certificate->program->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500">Perusahaan</p>
                    <p class="font-medium text-zinc-900">{{ $certificate->program->company->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Info: Hanya bisa view --}}
        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6">
            <p class="text-sm text-zinc-500">
                <strong>Catatan:</strong> Penerbitan sertifikat dilakukan oleh <strong>Perusahaan</strong>, bukan administrator.
                Administrator hanya dapat melihat dan mengawasi sertifikat yang sudah diterbitkan.
            </p>
        </div>

        @if($certificate->status === 'ISSUED' && $certificate->file)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <a href="{{ route('files.download', $certificate->file_id) }}" class="block w-full rounded-xl bg-zinc-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-zinc-800">Download PDF</a>
            </div>
        @endif
    </div>
</div>
@endsection