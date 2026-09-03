@extends('layouts.dashboard')
@section('page-title', 'Edit Perusahaan - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6"><a href="{{ route('root.companies') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a><h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $company->name }}</h1></div>
<p class="text-sm text-zinc-500">Profil perusahaan hanya bisa diedit oleh pemilik akun perusahaan.</p>
@endsection