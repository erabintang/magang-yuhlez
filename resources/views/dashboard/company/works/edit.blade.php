@extends('layouts.dashboard')

@section('page-title', 'Edit Karya - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('company.works.show', $work->slug) }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Karya</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Edit: {{ $work->title }}</h1>
</div>

<form action="{{ route('company.works.update', $work->slug) }}" method="POST" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="space-y-5">
        <div>
            <label for="title" class="block text-sm font-medium text-zinc-700 mb-1">Judul Karya <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $work->title) }}" required
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('title') border-red-500 @enderror">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="short_description" class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Singkat</label>
            <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $work->short_description) }}" maxlength="500"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Lengkap</label>
            <input type="hidden" name="description" id="work-edit-description-hidden" value="{{ old('description', $work->description) }}">
            <div id="work-edit-description-editor" data-wysiwyg="work-edit-description-hidden" data-placeholder="Jelaskan detail karya..." class="wysiwyg-container"></div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="category" class="block text-sm font-medium text-zinc-700 mb-1">Kategori</label>
                <select name="category" id="category"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                    <option value="">Pilih kategori</option>
                    @foreach(['Web Development', 'Mobile App', 'UI/UX Design', 'Film & Video', 'Photography', 'Branding', 'Digital Marketing', 'Other'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $work->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-zinc-700 mb-1">Tahun</label>
                <input type="number" name="year" id="year" value="{{ old('year', $work->year) }}" min="2020" max="{{ date('Y') + 1 }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="source_code_url" class="block text-sm font-medium text-zinc-700 mb-1">Link Source Code</label>
                <input type="url" name="source_code_url" id="source_code_url" value="{{ old('source_code_url', $work->source_code_url) }}" placeholder="https://github.com/..."
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
            </div>
            <div>
                <label for="deploy_url" class="block text-sm font-medium text-zinc-700 mb-1">Link Deploy / Live Demo</label>
                <input type="url" name="deploy_url" id="deploy_url" value="{{ old('deploy_url', $work->deploy_url) }}" placeholder="https://..."
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $work->is_published) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400">
            <label for="is_published" class="text-sm font-medium text-zinc-700">Publikasikan</label>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('company.works.show', $work->slug) }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50">Batal</a>
    </div>
</form>
@section('scripts')
@endsection
@endsection
