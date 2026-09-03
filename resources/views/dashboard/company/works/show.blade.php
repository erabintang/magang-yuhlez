@extends('layouts.dashboard')

@section('page-title', $work->title . ' - Karya - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('company.works.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Karya</a>
    <div class="mt-2 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">{{ $work->title }}</h1>
            @if($work->short_description)
                <p class="mt-1 text-zinc-600">{{ $work->short_description }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('company.works.edit', $work->slug) }}" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
            <form action="{{ route('company.works.destroy', $work->slug) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-xl border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Hapus</button>
            </form>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Description --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-3">Deskripsi</h2>
            @if($work->description)
                <div class="prose prose-sm max-w-none text-zinc-600">{!! $work->description ?? '' !!}</div>
            @else
                <p class="text-zinc-400 italic">Belum ada deskripsi</p>
            @endif
        </div>

        {{-- Gallery --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-zinc-900">Galeri ({{ $work->gallery->count() }})</h2>
            </div>

            @if($work->gallery->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($work->gallery as $gallery)
                        <div class="group relative aspect-square overflow-hidden rounded-xl bg-zinc-100">
                            @if($gallery->file)
                                <img src="{{ route('files.download', $gallery->file_id) }}" alt="Gallery"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-zinc-200 to-zinc-300 flex items-center justify-center">
                                    <span class="text-zinc-400 text-xs">Foto {{ $loop->index + 1 }}</span>
                                </div>
                            @endif
                            <form action="{{ route('company.works.gallery.remove', [$work->slug, $gallery->id]) }}" method="POST"
                                class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Hapus foto ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-500 p-1.5 text-white shadow hover:bg-red-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-zinc-400 text-sm">Belum ada foto di galeri</p>
            @endif
        </div>

        {{-- Interns --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Intern Peserta ({{ $work->interns->count() }})</h2>
            @if($work->interns->count() > 0)
                <div class="space-y-2">
                    @foreach($work->interns as $wi)
                        @if($wi->intern)
                            <div class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-zinc-200 rounded-full flex items-center justify-center">
                                        <span class="text-zinc-500 text-xs font-medium">{{ substr($wi->intern->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900">{{ $wi->intern->name }}</p>
                                        <p class="text-xs text-zinc-500">Bergabung {{ $wi->added_at?->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('company.works.interns.remove', [$work->slug, $wi->intern_id]) }}" method="POST"
                                    onsubmit="return confirm('Hapus intern dari karya ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-zinc-400 text-sm">Belum ada intern peserta</p>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Info Karya</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Status</dt>
                    <dd>
                        @if($work->is_published)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Published</span>
                        @else
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Draft</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Kategori</dt>
                    <dd class="text-zinc-900">{{ $work->category ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Tahun</dt>
                    <dd class="text-zinc-900">{{ $work->year ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Dibuat</dt>
                    <dd class="text-zinc-900">{{ $work->created_at?->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Add Gallery via Upload --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Tambah Foto</h3>
            <form action="{{ route('company.works.gallery.add', $work->slug) }}" method="POST" id="gallery-form">
                @csrf
                <input type="hidden" name="file_id" id="gallery_file_id">
                <div class="space-y-3">
                    <input type="file" accept="image/*" data-chunked-upload data-bucket="yuhlez"
                        data-progress="gallery-progress" data-hidden-input="#gallery_file_id"
                        class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                    <div id="gallery-progress" class="hidden">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="progress-fill bg-yuhlez-primary h-1.5 rounded-full transition-all" style="width:0%"></div>
                        </div>
                        <p class="progress-text text-xs text-gray-500 mt-1">Mengupload...</p>
                    </div>
                    <button type="submit" id="gallery-submit-btn" disabled
                        class="w-full rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 disabled:opacity-40 disabled:cursor-not-allowed">
                        Tambahkan
                    </button>
                </div>
            </form>
        </div>

        {{-- Add Intern --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Tambah Intern</h3>
            <form action="{{ route('company.works.interns.add', $work->slug) }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <select name="intern_id" class="w-full rounded-xl border border-zinc-300 px-3 py-2 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                        <option value="">Pilih intern</option>
                        @foreach($interns as $intern)
                            <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/chunked-upload.js') }}"></script>
<script>
// Enable gallery submit button after file is uploaded
document.getElementById('gallery_file_id').addEventListener('change', function() {
    document.getElementById('gallery-submit-btn').disabled = !this.value;
});
</script>
@endsection
