@extends('layouts.dashboard')
@section('page-title', 'Edit Akun - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('root.accounts.show', $account->id) }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $account->name }}</h1>
</div>
<form action="{{ route('root.accounts.update', $account->id) }}" method="POST" class="max-w-xl">
    @csrf @method('PUT')
    <div class="space-y-5">
        <div><label class="block text-sm font-medium text-zinc-700 mb-1">Nama *</label><input type="text" name="name" value="{{ old('name', $account->name) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none"></div>
        <div><label class="block text-sm font-medium text-zinc-700 mb-1">Email *</label><input type="email" name="email" value="{{ old('email', $account->email) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none"></div>
        <div><label class="block text-sm font-medium text-zinc-700 mb-1">Password Baru (opsional)</label><input type="password" name="password" class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none" placeholder="Kosongkan jika tidak diubah"></div>
        <div><label class="block text-sm font-medium text-zinc-700 mb-1">Role</label><p class="text-sm text-zinc-500">{{ $account->role }} (tidak bisa diubah)</p></div>
    </div>
    <div class="mt-8 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan</button>
        <a href="{{ route('root.accounts.show', $account->id) }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
@endsection