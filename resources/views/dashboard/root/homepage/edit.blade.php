@extends('layouts.dashboard')
@section('page-title', 'Edit ' . $section->title . ' - Beranda')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('root.homepage.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $section->title ?? $section->section_key }}</h1>
</div>

@switch($section->section_key)
    @case('hero')
        @include('dashboard.root.homepage._form-hero')
        @break
    @case('about')
        @include('dashboard.root.homepage._form-about')
        @break
    @case('team')
        @include('dashboard.root.homepage._form-team')
        @break
    @case('services')
        @include('dashboard.root.homepage._form-services')
        @break
    @case('process')
        @include('dashboard.root.homepage._form-process')
        @break
    @case('contributors')
        @include('dashboard.root.homepage._form-contributors')
        @break
    @case('cta')
        @include('dashboard.root.homepage._form-cta')
        @break
    @default
        <p class="text-zinc-500">Section tidak dikenali.</p>
@endswitch
@endsection
