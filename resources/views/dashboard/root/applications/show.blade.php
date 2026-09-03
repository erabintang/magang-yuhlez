@extends('layouts.dashboard')

@section('page-title', 'Detail Pendaftaran - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('root.applications.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Pendaftaran</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Detail Pendaftaran</h1>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Informasi Pendaftaran</h2>
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <dt class="text-zinc-500">Intern</dt>
                    <dd class="mt-1 text-zinc-900">{{ $application->intern->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Program</dt>
                    <dd class="mt-1 text-zinc-900">{{ $application->program->title ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Posisi</dt>
                    <dd class="mt-1 text-zinc-900">{{ $application->position->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Status</dt>
                    <dd class="mt-1">
                        @if($application->status === 'PENDING')
                            <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700">Pending</span>
                        @elseif($application->status === 'ACCEPTED')
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterima</span>
                        @elseif($application->status === 'REJECTED')
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                        @elseif($application->status === 'CANCELLED')
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Dibatalkan</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Tanggal Lamar</dt>
                    <dd class="mt-1 text-zinc-900">{{ $application->applied_at?->format('d M Y H:i') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Tanggal Review</dt>
                    <dd class="mt-1 text-zinc-900">{{ $application->reviewed_at?->format('d M Y H:i') ?? '-' }}</dd>
                </div>
            </dl>

            @if($application->cover_letter)
                <div class="mt-4 border-t border-zinc-200 pt-4">
                    <h3 class="text-sm font-medium text-zinc-700 mb-2">Surat Lamaran</h3>
                    <p class="text-sm text-zinc-600 whitespace-pre-wrap">{{ $application->cover_letter }}</p>
                </div>
            @endif

            @if($application->rejection_reason)
                <div class="mt-4 border-t border-zinc-200 pt-4">
                    <h3 class="text-sm font-medium text-red-700 mb-2">Alasan Penolakan</h3>
                    <p class="text-sm text-red-600">{{ $application->rejection_reason }}</p>
                </div>
            @endif
        </div>

        {{-- History --}}
        @if($application->histories->count() > 0)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="font-semibold text-zinc-900 mb-4">Riwayat Status</h2>
                <div class="space-y-3">
                    @foreach($application->histories as $history)
                        <div class="flex items-start gap-3">
                            <div class="mt-1 w-2 h-2 rounded-full bg-zinc-300 shrink-0"></div>
                            <div>
                                <p class="text-sm text-zinc-900">
                                    <span class="font-medium">{{ $history->old_status ?? 'Baru' }}</span>
                                    → <span class="font-medium">{{ $history->new_status }}</span>
                                </p>
                                @if($history->reason)
                                    <p class="text-xs text-zinc-500">{{ $history->reason }}</p>
                                @endif
                                <p class="text-xs text-zinc-400">{{ $history->created_at?->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Intern Info</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-zinc-100 rounded-full flex items-center justify-center">
                    <span class="text-zinc-500 text-sm font-medium">{{ substr($application->intern->name ?? '?', 0, 1) }}</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900">{{ $application->intern->name ?? '-' }}</p>
                    <p class="text-xs text-zinc-500">{{ $application->intern->contact_email ?? '-' }}</p>
                </div>
            </div>
            @if($application->intern->cv_file_id)
                <a href="{{ route('files.download', $application->intern->cv_file_id) }}" class="block w-full rounded-xl border border-zinc-300 px-4 py-2 text-center text-sm font-medium text-zinc-700 hover:bg-zinc-50">Lihat CV</a>
            @endif
        </div>
    </div>
</div>
@endsection
