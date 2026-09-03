@extends('layouts.dashboard')

@section('page-title', $intern->name . ' - Intern - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('root.interns') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Intern</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">{{ $intern->name }}</h1>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Profil</h2>
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <dt class="text-zinc-500">Nama</dt>
                    <dd class="mt-1 text-zinc-900">{{ $intern->name }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Email</dt>
                    <dd class="mt-1 text-zinc-900">{{ $intern->contact_email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">WhatsApp</dt>
                    <dd class="mt-1 text-zinc-900">{{ $intern->whatsapp ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Alamat</dt>
                    <dd class="mt-1 text-zinc-900">{{ $intern->address ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Gmail Access</dt>
                    <dd class="mt-1 text-zinc-900">{{ $intern->gmail_access ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Slug</dt>
                    <dd class="mt-1 font-mono text-xs text-zinc-900">{{ $intern->slug }}</dd>
                </div>
            </dl>

            @if($intern->short_description)
                <div class="mt-4 border-t border-zinc-200 pt-4">
                    <h3 class="text-sm font-medium text-zinc-700 mb-2">Deskripsi Singkat</h3>
                    <p class="text-sm text-zinc-600">{{ $intern->short_description }}</p>
                </div>
            @endif
        </div>

        {{-- Applications --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Pendaftaran ({{ $applications->count() }})</h2>
            @if($applications->count() > 0)
                <div class="space-y-2">
                    @foreach($applications as $app)
                        <a href="{{ route('root.applications.show', $app->id) }}" class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3 hover:bg-zinc-100 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $app->program->title ?? '-' }}</p>
                                <p class="text-xs text-zinc-500">{{ $app->position->name ?? '-' }} · {{ $app->applied_at?->format('d M Y') }}</p>
                            </div>
                            @if($app->status === 'PENDING')
                                <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Pending</span>
                            @elseif($app->status === 'ACCEPTED')
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterima</span>
                            @elseif($app->status === 'REJECTED')
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                            @else
                                <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">{{ $app->status }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                @if($applications->hasPages())
                    <div class="mt-4">{{ $applications->links() }}</div>
                @endif
            @else
                <p class="text-zinc-400 text-sm">Belum ada pendaftaran</p>
            @endif
        </div>

        {{-- Certificates --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Sertifikat ({{ $certificates->count() }})</h2>
            @if($certificates->count() > 0)
                <div class="space-y-2">
                    @foreach($certificates as $cert)
                        <a href="{{ route('root.certificates.show', $cert->id) }}" class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3 hover:bg-zinc-100 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $cert->program->title ?? '-' }}</p>
                                <p class="text-xs text-zinc-500">{{ $cert->certificate_number ?? 'Belum diterbitkan' }}</p>
                            </div>
                            @if($cert->status === 'ISSUED')
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterbitkan</span>
                            @elseif($cert->status === 'ELIGIBLE')
                                <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Eligible</span>
                            @else
                                <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Belum</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                @if($certificates->hasPages())
                    <div class="mt-4">{{ $certificates->links() }}</div>
                @endif
            @else
                <p class="text-zinc-400 text-sm">Belum ada sertifikat</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">User Account</h3>
            @if($intern->user)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-zinc-100 rounded-full flex items-center justify-center">
                        <span class="text-zinc-500 text-sm font-medium">{{ substr($intern->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900">{{ $intern->user->name }}</p>
                        <p class="text-xs text-zinc-500">{{ $intern->user->email }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if($intern->cv_file_id)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="font-semibold text-zinc-900 mb-3">CV</h3>
                <a href="{{ route('files.download', $intern->cv_file_id) }}" class="block w-full rounded-xl border border-zinc-300 px-4 py-2 text-center text-sm font-medium text-zinc-700 hover:bg-zinc-50">Download CV</a>
            </div>
        @endif
    </div>
</div>
@endsection
