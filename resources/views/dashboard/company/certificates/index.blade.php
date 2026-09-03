@extends('layouts.dashboard')
@section('title', 'Sertifikat - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Sertifikat</h1>
        <p class="mt-1 text-sm text-zinc-500">Terbitkan sertifikat untuk peserta program magang Anda.</p>
    </div>
    <a href="{{ route('company.certificates.create') }}" class="shrink-0 rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">
        + Upload Sertifikat
    </a>
</div>

{{-- Filter --}}
<div class="flex gap-2 mb-4">
    <a href="{{ route('company.certificates.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Semua</a>
    <a href="{{ route('company.certificates.index', ['status' => 'ELIGIBLE']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'ELIGIBLE' ? 'bg-yellow-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Siap Terbitkan</a>
    <a href="{{ route('company.certificates.index', ['status' => 'ISSUED']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'ISSUED' ? 'bg-green-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Diterbitkan</a>
</div>

<div class="space-y-3">
    @forelse($certificates as $cert)
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="font-semibold text-zinc-900">{{ $cert->intern->name ?? 'Peserta' }}</p>
                    <p class="text-sm text-zinc-500">{{ $cert->program->title ?? 'Program' }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-zinc-400">
                        @if($cert->certificate_number) <span class="rounded-full bg-zinc-100 px-2 py-0.5 font-medium text-zinc-600">No. {{ $cert->certificate_number }}</span> @endif
                        @if($cert->status === 'ISSUED') <span class="rounded-full bg-green-100 px-2 py-0.5 font-medium text-green-700">Diterbitkan</span>
                        @elseif($cert->status === 'ELIGIBLE') <span class="rounded-full bg-yellow-100 px-2 py-0.5 font-medium text-yellow-700">Siap Diterbitkan</span>
                        @else <span class="rounded-full bg-zinc-100 px-2 py-0.5 font-medium text-zinc-500">{{ $cert->status }}</span> @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('company.certificates.show', $cert->id) }}" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Detail</a>
                    @if($cert->status === 'ELIGIBLE')
                        <form action="{{ route('company.certificates.issue', $cert->id) }}" method="POST" onsubmit="return confirm('Terbitkan sertifikat untuk {{ $cert->intern->name }}?')">
                            @csrf
                            <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">🎓 Terbitkan</button>
                        </form>
                    @elseif($cert->status === 'ISSUED' && $cert->file)
                        <a href="{{ route('files.download', $cert->file_id) }}" class="rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800">Download</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
            <p class="text-zinc-500">Belum ada sertifikat</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $certificates->links() }}</div>
@endsection