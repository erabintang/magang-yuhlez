@extends('layouts.dashboard')
@section('page-title', 'Program Magang')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Program Magang</h2>
    <form action="{{ route('root.programs.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari program..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent w-64">
        <button type="submit" class="px-4 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">Cari</button>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($programs as $program)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $program->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $program->company->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $program->positions->count() }}</td>
                        <td class="px-6 py-4">
                            @if($program->registration_end >= now())
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Buka</span>
                            @else
                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Tutup</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('root.programs.show', $program->slug) }}" class="text-yuhlez-primary hover:underline text-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Tidak ada program.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $programs->withQueryString()->links() }}</div>
@endsection
