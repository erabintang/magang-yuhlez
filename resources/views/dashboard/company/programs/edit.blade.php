@extends('layouts.dashboard')
@section('page-title', 'Edit Program - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('company.programs.show', $program->slug) }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $program->title }}</h1>
</div>
<form action="{{ route('company.programs.update', $program->slug) }}" method="POST" class="max-w-2xl">
    @csrf @method('PUT')
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Judul Program *</label>
            <input type="text" name="title" value="{{ old('title', $program->title) }}" required
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Singkat</label>
            <input type="text" name="short_description" value="{{ old('short_description', $program->short_description) }}" maxlength="500"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Lengkap</label>
            <input type="hidden" name="description" id="program-edit-description-hidden" value="{{ old('description', $program->description) }}">
            <div id="program-edit-description-editor" data-wysiwyg="program-edit-description-hidden" data-placeholder="Jelaskan detail program..."></div>
        </div>
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">1</span>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">2</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">3</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">4</span>
                <span class="text-xs text-zinc-500 ml-1">Urutan: Buka Daftar → Tutup Daftar → Mulai Program → Selesai Program</span>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Buka Pendaftaran *</label>
                <input type="datetime-local" name="registration_start" value="{{ old('registration_start', $program->registration_start?->format('Y-m-d\TH:i')) }}" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Tutup Pendaftaran *</label>
                <input type="datetime-local" name="registration_end" value="{{ old('registration_end', $program->registration_end?->format('Y-m-d\TH:i')) }}" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Mulai Program *</label>
                <input type="datetime-local" name="program_start" value="{{ old('program_start', $program->program_start?->format('Y-m-d\TH:i')) }}" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Selesai Program *</label>
                <input type="datetime-local" name="program_end" value="{{ old('program_end', $program->program_end?->format('Y-m-d\TH:i')) }}" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
    </div>
    <div class="mt-8 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('company.programs.show', $program->slug) }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
@endsection
