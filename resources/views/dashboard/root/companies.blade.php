@extends('layouts.dashboard')
@section('page-title', 'Perusahaan')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Daftar Perusahaan</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($companies as $company)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900">{{ $company->name }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $company->short_description ?? '-' }}</p>
            <div class="mt-3 flex items-center gap-4 text-xs text-gray-400">
                <span>{{ $company->programs->count() }} program</span>
                <span>{{ $company->user->email ?? '-' }}</span>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-500">Tidak ada perusahaan.</p>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $companies->links() }}</div>
@endsection
