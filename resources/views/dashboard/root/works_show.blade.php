@extends('layouts.dashboard')

@section('page-title', $work->title . ' - Karya - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('root.works.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Karya</a>
    <div class="mt-2 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">{{ $work->title }}</h1>
            @if($work->short_description)
                <p class="mt-1 text-zinc-600">{{ $work->short_description }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <span class="shrink-0 rounded-full {{ $work->work_type === 'PUBLIC_WORK' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} px-3 py-1 text-xs font-medium">
                {{ $work->work_type === 'PUBLIC_WORK' ? 'Kreator' : 'Program' }}
            </span>
            @if($work->is_published)
                <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Published</span>
            @else
                <span class="shrink-0 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-500">Draft</span>
            @endif
            <form action="{{ route('root.works.destroy', $work->slug) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-xl border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Hapus</button>
            </form>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-3">Deskripsi</h2>
            @if($work->description)
                <div class="prose prose-sm max-w-none text-zinc-600">{!! $work->description !!}</div>
            @else
                <p class="text-zinc-400 italic">Belum ada deskripsi</p>
            @endif
        </div>

        @if($work->gallery->count() > 0)
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Galeri ({{ $work->gallery->count() }})</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($work->gallery as $gallery)
                    <div class="aspect-square overflow-hidden rounded-xl bg-zinc-100">
                        @if($gallery->file)
                            <img src="{{ route('files.download', $gallery->file_id) }}" alt="Gallery" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-zinc-200 to-zinc-300 flex items-center justify-center">
                                <span class="text-zinc-400 text-xs">Foto {{ $loop->index + 1 }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($work->interns->count() > 0)
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Intern Peserta ({{ $work->interns->count() }})</h2>
            <div class="space-y-2">
                @foreach($work->interns as $wi)
                    @if($wi->intern)
                        <div class="flex items-center gap-3 rounded-xl bg-zinc-50 px-4 py-3">
                            <div class="w-8 h-8 bg-zinc-200 rounded-full flex items-center justify-center">
                                <span class="text-zinc-500 text-xs font-medium">{{ substr($wi->intern->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $wi->intern->name }}</p>
                                <p class="text-xs text-zinc-500">Bergabung {{ $wi->added_at?->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Info Karya</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Kategori</dt>
                    <dd class="text-zinc-900">{{ $work->category ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Tahun</dt>
                    <dd class="text-zinc-900">{{ $work->year ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Perusahaan</dt>
                    <dd class="text-zinc-900">{{ $work->company->name ?? '-' }}</dd>
                </div>

                <div class="flex justify-between">
                    <dt class="text-zinc-500">Dibuat</dt>
                    <dd class="text-zinc-900">{{ $work->created_at?->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
