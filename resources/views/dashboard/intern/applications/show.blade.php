@extends('layouts.dashboard')
@section('page-title', 'Detail Pendaftaran - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
<a href="{{ route('intern.applications.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke pendaftaran saya</a>

<div class="mt-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                @if($application->status === 'PENDING') <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Pending</span>
                @elseif($application->status === 'ACCEPTED') <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterima</span>
                @elseif($application->status === 'REJECTED') <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                @elseif($application->status === 'CANCELLED') <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Dibatalkan</span>
                @endif
            </div>
            <h1 class="mt-2 text-2xl font-bold text-zinc-900">{{ $application->position->name ?? 'Posisi' }}</h1>
            <p class="text-sm text-zinc-500">{{ $application->program->company->name ?? '-' }} · {{ $application->program->title ?? 'Program' }}</p>
            <p class="mt-1 text-xs text-zinc-400">Didaftarkan {{ $application->applied_at?->format('d M Y') }}</p>
        </div>
    </div>
    @if($application->cover_letter)
        <div class="mt-5"><h2 class="text-sm font-semibold text-zinc-900">Surat Lamaran</h2><p class="mt-1 whitespace-pre-line text-sm text-zinc-600">{{ $application->cover_letter }}</p></div>
    @endif
    @if($application->status === 'REJECTED' && $application->rejection_reason)
        <div class="mt-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700"><span class="font-medium">Alasan penolakan: </span>{{ $application->rejection_reason }}</div>
    @endif
</div>

<div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-zinc-900">Riwayat Status</h2>
    @if($application->histories->count() > 0)
        <ol class="mt-6 space-y-0">
            @foreach($application->histories as $i => $history)
                <li class="relative flex gap-4 pb-8 last:pb-0">
                    @if(!$loop->last) <span class="absolute left-[5px] top-5 h-full w-px bg-zinc-200"></span> @endif
                    <span class="relative mt-1.5 h-[11px] w-[11px] shrink-0 rounded-full bg-yellow-400 ring-4 ring-yellow-100"></span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-zinc-900">
                            @if($history->old_status) {{ $history->old_status }} @else Pendaftaran dibuat @endif → {{ $history->new_status }}
                        </p>
                        <p class="mt-0.5 text-xs text-zinc-400">{{ $history->created_at?->format('d M Y H:i') }}</p>
                        @if($history->reason) <p class="mt-1 text-sm text-zinc-600">{{ $history->reason }}</p> @endif
                    </div>
                </li>
            @endforeach
        </ol>
    @else
        <p class="mt-4 text-zinc-400 text-sm">Belum ada riwayat</p>
    @endif
</div>
@endsection