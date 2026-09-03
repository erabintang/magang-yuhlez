@extends('layouts.dashboard')
@section('page-title', 'Intern')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Daftar Intern</h2>
<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CV</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($interns as $intern)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $intern->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $intern->contact_email ?? $intern->user->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $intern->whatsapp ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($intern->cv_file_id)
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Ada</span>
                            @else
                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded">Belum</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada intern.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $interns->links() }}</div>
@endsection
