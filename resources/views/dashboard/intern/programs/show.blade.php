@extends('layouts.dashboard')
@section('page-title', $program->title)
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('intern.programs.index') }}" class="text-sm text-yuhlez-primary hover:underline">&larr; Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h2>
        <p class="text-gray-600 mt-1">{{ $program->company->name ?? '-' }}</p>

        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><span class="text-gray-500">Pendaftaran:</span><br>{{ $program->registration_start->format('d M Y') }} - {{ $program->registration_end->format('d M Y') }}</div>
            <div><span class="text-gray-500">Program:</span><br>{{ $program->program_start->format('d M Y') }} - {{ $program->program_end->format('d M Y') }}</div>
            <div><span class="text-gray-500">Posisi:</span><br>{{ $program->positions->count() }} tersedia</div>
            <div>
                @if($program->registration_end >= now())
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Pendaftaran Terbuka</span>
                @else
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-500 rounded">Pendaftaran Ditutup</span>
                @endif
            </div>
        </div>

        @if($program->description)
            <div class="mt-6 pt-6 border-t">
                <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                <div class="prose prose-sm max-w-none text-zinc-600">{!! $program->description !!}</div>
            </div>
        @endif

        <div class="mt-6 pt-6 border-t">
            <h3 class="font-semibold text-gray-900 mb-3">Posisi</h3>
            <div class="space-y-3">
                @foreach($program->positions as $pos)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $pos->name }}</p>
                                @if($pos->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $pos->description }}</p>
                                @endif
                            </div>
                            @if($pos->quota)
                                <span class="text-sm text-gray-500">Kuota: {{ $pos->quota }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Apply Section --}}
    @if($program->registration_end >= now())
        @if($existingApplication)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Kamu Sudah Mendaftar</h3>
                <p class="text-sm text-gray-600">Status pendaftaran kamu:
                    @if($existingApplication->status === 'PENDING')
                        <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                    @elseif($existingApplication->status === 'ACCEPTED')
                        <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Diterima</span>
                    @endif
                </p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Daftar Sekarang</h3>
                <form action="{{ route('intern.applications.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $program->id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Posisi <span class="text-red-500">*</span></label>
                        <select name="position_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($program->positions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->name }}{{ $pos->quota ? " (Kuota: {$pos->quota})" : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surat Lamaran</label>
                        <textarea name="cover_letter" rows="4" placeholder="Tulis surat lamaran (opsional)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Yakin ingin mendaftar?')" class="px-6 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary font-medium">Kirim Pendaftaran</button>
                </form>
            </div>
        @endif
    @endif
</div>
@endsection
