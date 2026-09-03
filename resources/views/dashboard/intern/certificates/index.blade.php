@extends('layouts.dashboard')

@section('title', 'Sertifikat - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-zinc-900">Sertifikat</h1>
    <p class="mt-1 text-sm text-zinc-500">Sertifikat yang telah diterbitkan untuk Anda.</p>
</div>

<div class="space-y-4">
    @forelse($certificates as $cert)
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-semibold text-zinc-900">{{ $cert->program->title ?? '-' }}</h3>
                        @if($cert->status === 'ISSUED')
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Diterbitkan</span>
                        @elseif($cert->status === 'ELIGIBLE')
                            <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Menunggu Penerbitan</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500 rounded-full">Belum Eligible</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-zinc-500">{{ $cert->program->company->name ?? '-' }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-zinc-400">
                        @if($cert->certificate_number)
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 font-medium text-zinc-600">No. {{ $cert->certificate_number }}</span>
                        @endif
                        @if($cert->issued_at)
                            <span>Terbit: {{ $cert->issued_at->format('d M Y') }}</span>
                        @endif
                    </div>
                    @if(!empty($cert->peers))
                        <div class="mt-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3">
                            <p class="text-xs text-blue-600 font-medium mb-1">Nama lain di sertifikat yang sama:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($cert->peers as $peerName)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">{{ $peerName }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="shrink-0 flex flex-col items-end gap-2">
                    @if($cert->status === 'ISSUED' && $cert->file_id)
                        <a href="{{ route('intern.certificates.download', $cert->id) }}"
                            class="rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800 transition-colors">
                            ↓ Unduh
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-zinc-500">Belum ada sertifikat yang diterbitkan.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $certificates->links() }}</div>
@endsection
