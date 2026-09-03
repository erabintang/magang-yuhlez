@extends('layouts.dashboard')

@section('page-title', 'Edit Karya')

@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('intern.works.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-700 mb-4">
        ← Kembali
    </a>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-zinc-900 mb-1">Edit Karya</h2>
        <p class="text-sm text-zinc-500 mb-6">Perbarui informasi karya "{{ $work->title }}".</p>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('intern.works.update', $work->slug) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $work->title) }}" required
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $work->short_description) }}" maxlength="500"
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="5"
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">{{ old('description', $work->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Kategori</label>
                        <input type="text" name="category" value="{{ old('category', $work->category) }}"
                            class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Tahun</label>
                        <input type="number" name="year" value="{{ old('year', $work->year) }}" min="2020" max="{{ date('Y') + 1 }}"
                            class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">URL Source Code</label>
                    <input type="url" name="source_code_url" value="{{ old('source_code_url', $work->source_code_url) }}"
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">URL Live Demo</label>
                    <input type="url" name="deploy_url" value="{{ old('deploy_url', $work->deploy_url) }}"
                        class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-yellow-400 text-zinc-900 text-sm font-semibold rounded-lg hover:bg-yellow-300 transition">Simpan Perubahan</button>
                <a href="{{ route('intern.works.index') }}" class="px-5 py-2 bg-zinc-100 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-200 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
