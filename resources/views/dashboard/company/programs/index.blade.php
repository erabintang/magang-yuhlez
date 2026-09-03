@extends('layouts.dashboard')
@section('page-title', 'Program Magang')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Program Magang</h2>
    <a href="{{ route('company.programs.create') }}" class="px-4 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">+ Buat Program</a>
</div>
<div class="space-y-4">
    @forelse($programs as $program)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('company.programs.show', $program->slug) }}" class="text-lg font-semibold text-gray-900 hover:text-yuhlez-primary">{{ $program->title }}</a>
                        @if($program->registration_end >= now())
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">Buka</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded">Tutup</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mb-2">{{ $program->short_description }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span>{{ $program->positions->count() }} posisi</span>
                        <span>{{ $program->applications->count() }} pendaftar</span>
                        <span>{{ $program->registration_end->format('d M Y') }}</span>
                    </div>
                </div>
                <a href="{{ route('company.programs.show', $program->slug) }}" class="text-yuhlez-primary hover:underline text-sm ml-4">Detail &rarr;</a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-500">Belum ada program magang.</p>
            <a href="{{ route('company.programs.create') }}" class="mt-4 inline-block px-6 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">Buat Program Pertama</a>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $programs->links() }}</div>
@endsection
