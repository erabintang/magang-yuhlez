@extends('layouts.dashboard')
@section('page-title', 'Edit Intern - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6"><a href="{{ route('root.interns') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a><h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $intern->name }}</h1></div>
<p class="text-sm text-zinc-500">Profil intern hanya bisa diedit oleh pemilik akun.</p>
@endsection