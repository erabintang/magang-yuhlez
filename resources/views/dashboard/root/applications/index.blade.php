@extends('layouts.dashboard')
@section('page-title', 'Pendaftaran')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Pendaftaran</h2>
    <div class="flex gap-2">
        <a href="{{ route('root.applications.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('status') ? 'bg-yuhlez-primary text-white' : 'bg-gray-100 text-gray-600' }}">Semua</a>
        <a href="{{ route('root.applications.index', ['status' => 'PENDING']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'PENDING' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600' }}">Pending</a>
        <a href="{{ route('root.applications.index', ['status' => 'ACCEPTED']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'ACCEPTED' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600' }}">Diterima</a>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intern</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($applications as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $app->intern->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $app->program->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $app->program->company->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($app->status === 'PENDING')
                                <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Pending</span>
                            @elseif($app->status === 'ACCEPTED')
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Diterima</span>
                            @elseif($app->status === 'REJECTED')
                                <span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $app->applied_at?->format('d M Y') }}</td>
                        <td class="px-6 py-4"><a href="{{ route('root.applications.show', $app->id) }}" class="text-yuhlez-primary hover:underline text-sm">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada pendaftaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $applications->withQueryString()->links() }}</div>
@endsection
