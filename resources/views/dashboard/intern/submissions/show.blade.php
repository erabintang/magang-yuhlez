@extends('layouts.dashboard')

@section('title', 'Detail Karya - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('intern.submissions.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Karya Saya</a>
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
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-zinc-900">Detail Karya</h2>
            <dl class="mt-4 space-y-3">
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Judul</dt>
                    <dd class="text-sm text-zinc-900">{{ $submission->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Karya</dt>
                    <dd class="text-sm text-zinc-900">{{ $submission->work->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500">Perusahaan</dt>
                    <dd class="text-sm text-zinc-900">{{ $submission->work->company->name ?? '-' }}</dd>
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

        {{-- Review Note --}}
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
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900">Informasi</h3>
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
    </div>
</div>
@endsection
