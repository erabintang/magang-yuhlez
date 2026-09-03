@extends('layouts.dashboard')
@section('page-title', 'Buat Akun Baru')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <a href="{{ route('root.accounts.index') }}" class="text-sm text-yuhlez-primary hover:underline">&larr; Kembali</a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Buat Akun Baru</h2>
        </div>
        <form action="{{ route('root.accounts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                    <option value="COMPANY">Company</option>
                    <option value="INTERN">Intern</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password (opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika ingin login Google saja"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary font-medium">Buat Akun</button>
            </div>
        </form>
    </div>
</div>
@endsection
