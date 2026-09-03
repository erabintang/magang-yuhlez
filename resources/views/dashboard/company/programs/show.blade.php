@extends('layouts.dashboard')
@section('page-title', $program->title)
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('company.programs.index') }}" class="text-sm text-yuhlez-primary hover:underline">&larr; Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $program->title }}</h2>
                @if($program->registration_end >= now())
                    <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded mt-1 inline-block">Pendaftaran Terbuka</span>
                @else
                    <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded mt-1 inline-block">Pendaftaran Ditutup</span>
                @endif
            </div>
            <a href="{{ route('company.programs.edit', $program->slug) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Edit</a>
        </div>

        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-2xl font-bold text-gray-900">{{ $program->positions->count() }}</p>
                <p class="text-xs text-gray-500">Posisi</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-2xl font-bold text-gray-900">{{ $program->applications->count() }}</p>
                <p class="text-xs text-gray-500">Pendaftar</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-2xl font-bold text-green-600">{{ $program->applications->where('status', 'ACCEPTED')->count() }}</p>
                <p class="text-xs text-gray-500">Diterima</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-2xl font-bold text-yellow-600">{{ $program->applications->where('status', 'PENDING')->count() }}</p>
                <p class="text-xs text-gray-500">Pending</p>
            </div>
        </div>

        <div class="mt-6 space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div><span class="text-gray-500">Pendaftaran:</span> {{ $program->registration_start->format('d M Y') }} - {{ $program->registration_end->format('d M Y') }}</div>
                <div><span class="text-gray-500">Program:</span> {{ $program->program_start->format('d M Y') }} - {{ $program->program_end->format('d M Y') }}</div>
            </div>
            @if($program->description)
                <div class="pt-4 border-t">
                    <p class="text-gray-500 mb-1">Deskripsi:</p>
                    <div class="prose prose-sm max-w-none text-zinc-600">{!! $program->description ?? '' !!}</div>
                </div>
            @endif
        </div>

        <div class="mt-4">
            <p class="text-sm font-medium text-gray-700 mb-2">Posisi:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($program->positions as $pos)
                    <span class="px-3 py-1 bg-yuhlez-light text-yuhlez-primary rounded-full text-sm">{{ $pos->name }} {{ $pos->quota ? "(kuota: {$pos->quota})" : '' }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Applications --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Pendaftar ({{ $program->applications->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($program->applications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $app->intern->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $app->position->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($app->status === 'PENDING')<span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                @elseif($app->status === 'ACCEPTED')<span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Diterima</span>
                                @elseif($app->status === 'REJECTED')<span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $app->applied_at?->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('company.applications.show', $app->id) }}" class="text-yuhlez-primary hover:underline text-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pendaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
