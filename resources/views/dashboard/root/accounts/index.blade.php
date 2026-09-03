@extends('layouts.dashboard')
@section('page-title', 'Manajemen Akun')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Manajemen Akun</h2>
    <a href="{{ route('root.accounts.create') }}" class="px-4 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">+ Buat Akun</a>
</div>
<div class="flex gap-2 mb-4">
    <a href="{{ route('root.accounts.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('role') ? 'bg-yuhlez-primary text-white' : 'bg-gray-100 text-gray-600' }}">Semua</a>
    <a href="{{ route('root.accounts.index', ['role' => 'COMPANY']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('role') === 'COMPANY' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600' }}">Company</a>
    <a href="{{ route('root.accounts.index', ['role' => 'INTERN']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('role') === 'INTERN' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600' }}">Intern</a>
</div>
<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($accounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $account->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $account->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($account->role === 'ROOT')
                                <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded">ROOT</span>
                            @elseif($account->role === 'COMPANY')
                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">COMPANY</span>
                            @elseif($account->role === 'INTERN')
                                <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">INTERN</span>

                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 items-center">
                                <a href="{{ route('root.accounts.show', $account->id) }}" class="text-yuhlez-primary hover:underline text-sm">Detail</a>
                                <a href="{{ route('root.accounts.edit', $account->id) }}" class="text-gray-500 hover:underline text-sm">Edit</a>
                                <form action="{{ route('root.accounts.destroy', $account->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus akun {{ $account->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada akun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $accounts->withQueryString()->links() }}</div>
@endsection
