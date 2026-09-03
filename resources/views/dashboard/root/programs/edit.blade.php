@extends('layouts.dashboard')
@section('page-title', 'Edit Program - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6"><a href="{{ route('root.programs.show', $program->slug) }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a><h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $program->title }}</h1></div>
<p class="text-sm text-zinc-500 mb-4">Program hanya bisa diedit oleh perusahaan terkait.</p>
@endsection