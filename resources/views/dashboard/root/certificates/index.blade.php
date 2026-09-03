@extends('layouts.dashboard')
@section('page-title', 'Sertifikat')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Sertifikat</h2>
<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intern</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cert->intern->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $cert->program->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $cert->certificate_number ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($cert->status === 'ISSUED')
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Diterbitkan</span>
                            @elseif($cert->status === 'ELIGIBLE')
                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">Eligible</span>
                            @else
                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Belum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('root.certificates.show', $cert->id) }}" class="text-yuhlez-primary hover:underline text-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Tidak ada sertifikat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $certificates->links() }}</div>
@endsection
