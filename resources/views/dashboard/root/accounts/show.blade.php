@extends('layouts.dashboard')
@section('page-title', 'Detail Akun')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('root.accounts.index') }}" class="text-sm text-yuhlez-primary hover:underline">&larr; Kembali</a>
    <div class="bg-white rounded-xl shadow-sm mt-4 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $account->name }}</h2>
                <p class="text-gray-600">{{ $account->email }}</p>
                <span class="mt-2 inline-block px-2 py-0.5 text-xs font-medium bg-yuhlez-light text-yuhlez-primary rounded">{{ $account->role }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('root.accounts.edit', $account->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Edit</a>
                <form action="{{ route('root.accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Hapus</button>
                </form>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t space-y-3 text-sm">
            <div><span class="text-gray-500">ID:</span> <span class="font-mono">{{ $account->id }}</span></div>
            <div><span class="text-gray-500">Dibuat:</span> {{ $account->created_at?->format('d M Y H:i') }}</div>
            <div><span class="text-gray-500">Diperbarui:</span> {{ $account->updated_at?->format('d M Y H:i') }}</div>
            @if($account->companyProfile)
                <div><span class="text-gray-500">Perusahaan:</span> {{ $account->companyProfile->name }}</div>
            @endif
            @if($account->internProfile)
                <div><span class="text-gray-500">Intern:</span> {{ $account->internProfile->name }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
