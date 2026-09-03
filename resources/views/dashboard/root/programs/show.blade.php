@extends('layouts.dashboard')
@section('page-title', $program->title . ' - Program - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('root.programs.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">{{ $program->title }}</h1>
    <p class="mt-1 text-sm text-zinc-500">{{ $program->company->name ?? '-' }}</p>
</div>
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-3">Informasi Program</h2>
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-zinc-500">Judul</dt><dd class="mt-1 text-zinc-900">{{ $program->title }}</dd></div>
                <div><dt class="text-zinc-500">Perusahaan</dt><dd class="mt-1 text-zinc-900">{{ $program->company->name ?? '-' }}</dd></div>
                <div><dt class="text-zinc-500">Buka</dt><dd class="mt-1 text-zinc-900">{{ $program->registration_start?->format('d M Y') }}</dd></div>
                <div><dt class="text-zinc-500">Tutup</dt><dd class="mt-1 text-zinc-900">{{ $program->registration_end?->format('d M Y') }}</dd></div>
                <div><dt class="text-zinc-500">Mulai</dt><dd class="mt-1 text-zinc-900">{{ $program->program_start?->format('d M Y') }}</dd></div>
                <div><dt class="text-zinc-500">Selesai</dt><dd class="mt-1 text-zinc-900">{{ $program->program_end?->format('d M Y') }}</dd></div>
            </dl>
            @if($program->description)
                <div class="mt-4 border-t border-zinc-200 pt-4"><h3 class="text-sm font-medium text-zinc-700 mb-2">Deskripsi</h3><div class="prose prose-sm max-w-none text-zinc-600">{!! $program->description !!}</div></div>
            @endif
        </div>
        @if($program->applications->count() > 0)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="font-semibold text-zinc-900 mb-4">Pendaftar ({{ $program->applications->count() }})</h2>
                <div class="space-y-2">
                    @foreach($program->applications as $app)
                        <div class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3">
                            <div><p class="text-sm font-medium text-zinc-900">{{ $app->intern->name ?? '-' }}</p><p class="text-xs text-zinc-500">{{ $app->position->name ?? '-' }} · {{ $app->applied_at?->format('d M Y') }}</p></div>
                            @if($app->status === 'PENDING') <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Pending</span>
                            @elseif($app->status === 'ACCEPTED') <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterima</span>
                            @elseif($app->status === 'REJECTED') <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="rounded-2xl border border-zinc-200 bg-white p-6">
        <h3 class="font-semibold text-zinc-900 mb-3">Posisi</h3>
        @foreach($program->positions as $pos)
            <div class="mb-3 rounded-xl bg-zinc-50 px-4 py-3">
                <p class="text-sm font-medium text-zinc-900">{{ $pos->name }}</p>
                @if($pos->quota) <p class="text-xs text-zinc-500">Kuota: {{ $pos->quota }}</p> @endif
            </div>
        @endforeach
    </div>
</div>
@endsection