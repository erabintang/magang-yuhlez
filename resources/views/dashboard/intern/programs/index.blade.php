@extends('layouts.dashboard')
@section('page-title', 'Program Magang')
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Program Magang</h2>
    <form action="{{ route('intern.programs.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari program..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent w-64">
        <button type="submit" class="px-4 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">Cari</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($programs as $program)
        <a href="{{ route('intern.programs.show', $program->slug) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
            <div class="w-full h-40 bg-gradient-to-br from-yuhlez-primary to-yuhlez-accent"></div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    @if($program->registration_end >= now())
                        <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">Buka</span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded">Tutup</span>
                    @endif
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">{{ $program->title }}</h3>
                <p class="text-sm text-gray-600">{{ $program->company->name ?? '-' }}</p>
                <p class="text-xs text-gray-400 mt-2">Tutup: {{ $program->registration_end->format('d M Y') }}</p>
            </div>
        </a>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-500">Belum ada program magang tersedia.</p>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $programs->withQueryString()->links() }}</div>
@endsection
