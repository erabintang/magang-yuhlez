@extends('layouts.dashboard')

@section('title', 'Review Karya - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('company.submissions.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Review Karya</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">{{ $submission->title }}</h1>
    <div class="flex items-center gap-2 mt-2">
        @if($submission->status === 'PENDING')
            <span class="px-3 py-1 text-sm font-medium bg-amber-100 text-amber-700 rounded-full">Menunggu Review</span>
        @elseif($submission->status === 'ACCEPTED')
            <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded-full">Diterima ✓</span>
        @else
            <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-700 rounded-full">Ditolak ✗</span>
        @endif
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Detail Info --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-zinc-900">Detail Karya</h2>
            <dl class="mt-4 space-y-3">
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Intern</dt>
                    <dd class="text-sm text-zinc-900 font-medium">{{ $submission->intern->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Karya</dt>
                    <dd class="text-sm text-zinc-900">{{ $submission->work->title }}</dd>
                </div>
                @if($submission->description)
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Deskripsi</dt>
                        <dd class="text-sm text-zinc-900">{{ $submission->description }}</dd>
                    </div>
                @endif
                @if($submission->tech_stack)
                    <div>
                        <dt class="text-sm font-medium text-zinc-500">Tech Stack</dt>
                        <dd class="text-sm text-zinc-900">{{ $submission->tech_stack }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Dikirim</dt>
                    <dd class="text-sm text-zinc-900">{{ $submission->created_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Files --}}
        @if($submission->files->count() > 0)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">File yang Dikirim</h2>
                <div class="mt-4 space-y-3">
                    @foreach($submission->files as $sf)
                        <div class="flex items-center justify-between rounded-xl border border-zinc-200 p-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-zinc-900 truncate">{{ $sf->file->original_name ?? 'File' }}</p>
                                <p class="text-xs text-zinc-400">{{ $sf->file->mime_type ?? '-' }} · {{ round(($sf->file->size_bytes ?? 0) / 1024 / 1024, 2) }} MB</p>
                            </div>
                            <a href="{{ route('files.download', $sf->file_id) }}" class="shrink-0 ml-4 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">Download</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Review Note (if already reviewed) --}}
        @if($submission->review_note)
            <div class="rounded-2xl border {{ $submission->status === 'ACCEPTED' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} p-6">
                <h2 class="text-lg font-semibold {{ $submission->status === 'ACCEPTED' ? 'text-green-900' : 'text-red-900' }}">Catatan Review</h2>
                <p class="mt-2 text-sm {{ $submission->status === 'ACCEPTED' ? 'text-green-700' : 'text-red-700' }}">{{ $submission->review_note }}</p>
                @if($submission->reviewed_at)
                    <p class="mt-2 text-xs {{ $submission->status === 'ACCEPTED' ? 'text-green-500' : 'text-red-500' }}">Direview: {{ $submission->reviewed_at->format('d M Y H:i') }}</p>
                @endif
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Status --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900">Status</h3>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Status</dt>
                    <dd class="font-medium text-zinc-900">{{ $submission->status }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">File</dt>
                    <dd class="text-zinc-900">{{ $submission->files->count() }} file</dd>
                </div>
                @if($submission->reviewer)
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Direview oleh</dt>
                        <dd class="text-zinc-900">{{ $submission->reviewer->name }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Review Actions (only for PENDING) --}}
        @if($submission->status === 'PENDING')
            {{-- Accept --}}
            <form method="POST" action="{{ route('company.submissions.accept', $submission->id) }}" class="rounded-2xl border border-green-200 bg-green-50 p-6">
                @csrf
                <h3 class="font-semibold text-green-900">✓ Terima Karya</h3>
                <div class="mt-3">
                    <textarea name="review_note" rows="2" class="w-full rounded-xl border border-green-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-400/20 outline-none"
                        placeholder="Catatan (opsional)"></textarea>
                </div>
                <button type="submit" class="mt-3 w-full rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                    Terima
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="{{ route('company.submissions.reject', $submission->id) }}" class="rounded-2xl border border-red-200 bg-red-50 p-6">
                @csrf
                <h3 class="font-semibold text-red-900">✗ Tolak Karya</h3>
                <div class="mt-3">
                    <textarea name="review_note" rows="2" required class="w-full rounded-xl border border-red-300 px-3 py-2 text-sm focus:border-red-500 focus:ring-2 focus:ring-red-400/20 outline-none"
                        placeholder="Alasan penolakan (wajib)"></textarea>
                </div>
                <button type="submit" class="mt-3 w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                    Tolak
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
