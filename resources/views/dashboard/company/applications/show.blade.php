@extends('layouts.dashboard')
@section('page-title', 'Detail Pendaftar')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('company.applications.index') }}" class="text-sm text-yuhlez-primary hover:underline">&larr; Kembali ke Daftar Pendaftar</a>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">Detail Pendaftar</h2>
        </div>
        @if($application->status === 'PENDING')
            <div class="flex gap-2">
                <form action="{{ route('company.applications.accept', $application->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Yakin ingin menerima pendaftar ini?')"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Terima</button>
                </form>
                <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Tolak</button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Intern Info --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Informasi Intern</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Nama</p>
                    <p class="font-medium">{{ $application->intern->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="font-medium">{{ $application->intern->contact_email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">WhatsApp</p>
                    <p class="font-medium">{{ $application->intern->whatsapp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Alamat</p>
                    <p class="font-medium">{{ $application->intern->address ?? '-' }}</p>
                </div>
                @if($application->intern->cv_file_id)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">CV</p>
                        <a href="{{ route('files.download', $application->intern->cv_file_id) }}" target="_blank"
                            class="inline-flex items-center px-3 py-1.5 bg-yuhlez-light text-yuhlez-primary rounded-lg hover:bg-blue-100 text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Lihat CV
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Application Info --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Informasi Pendaftaran</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    @if($application->status === 'PENDING')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                    @elseif($application->status === 'ACCEPTED')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Diterima</span>
                    @elseif($application->status === 'REJECTED')
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Ditolak</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500">Program</p>
                    <p class="font-medium">{{ $application->program->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Posisi</p>
                    <p class="font-medium">{{ $application->position->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Mendaftar</p>
                    <p class="font-medium">{{ $application->applied_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
                @if($application->reviewed_at)
                    <div>
                        <p class="text-xs text-gray-500">Direview</p>
                        <p class="font-medium">{{ $application->reviewed_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
                @if($application->rejection_reason)
                    <div>
                        <p class="text-xs text-gray-500">Alasan Penolakan</p>
                        <p class="font-medium text-red-600">{{ $application->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Cover Letter --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Surat Lamaran</h3>
            @if($application->cover_letter)
                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $application->cover_letter }}</p>
            @else
                <p class="text-gray-400 italic">Tidak ada surat lamaran.</p>
            @endif
        </div>
    </div>

    {{-- History --}}
    @if($application->histories->count())
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Riwayat Status</h3>
            <div class="space-y-3">
                @foreach($application->histories->sortByDesc('created_at') as $history)
                    <div class="flex items-start gap-3 pb-3 border-b last:border-0">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm">
                                <span class="font-medium">{{ $history->old_status ?? 'Baru' }}</span>
                                &rarr;
                                <span class="font-medium">{{ $history->new_status }}</span>
                            </p>
                            @if($history->reason)
                                <p class="text-sm text-gray-500">{{ $history->reason }}</p>
                            @endif
                            <p class="text-xs text-gray-400">{{ $history->created_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Pendaftaran</h3>
        <form action="{{ route('company.applications.reject', $application->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Tulis alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection
