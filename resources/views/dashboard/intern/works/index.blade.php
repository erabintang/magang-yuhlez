@extends('layouts.dashboard')
@section('page-title', 'Karya - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Karya</h1>
        <p class="mt-1 text-sm text-zinc-500">Karya yang kamu buat dan ikuti</p>
    </div>
    <a href="{{ route('intern.works.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-yellow-300 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Buat Karya
    </a>
</div>

@if($ownWorks->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-zinc-900 mb-3">Karya Saya</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($ownWorks as $work)
                <div class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-zinc-900">{{ $work->title }}</h3>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium {{ $work->is_published ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $work->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    @if($work->short_description) <p class="mt-1.5 line-clamp-2 text-sm text-zinc-600">{{ $work->short_description }}</p> @endif
                    <div class="mt-auto pt-3 flex items-center gap-2">
                        <a href="{{ route('intern.works.edit', $work->slug) }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Edit</a>
                        <form action="{{ route('intern.works.destroy', $work->slug) }}" method="POST" onsubmit="return confirm('Yakin hapus karya ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700">Hapus</button>
                        </form>
                        @if($work->is_published)
                            <a href="{{ route('public.work.show', $work->slug) }}" target="_blank" class="ml-auto text-xs font-medium text-yellow-600 hover:text-yellow-700">Lihat di Landing →</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div>
    <h2 class="text-lg font-semibold text-zinc-900 mb-3">Karya yang Diikuti</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($works as $work)
            <div class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                @if($work->gallery->count() > 0 && $work->gallery->first()->file)
                    <div class="mb-3 aspect-video overflow-hidden rounded-xl bg-zinc-100">
                        <img src="{{ route('files.download', $work->gallery->first()->file_id) }}" alt="{{ $work->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="mb-3 aspect-video rounded-xl bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950 flex items-center justify-center">
                        <span class="rounded-lg bg-yellow-400 px-3 py-1 text-xs font-bold uppercase text-zinc-950">{{ $work->category ?? 'Karya' }}</span>
                    </div>
                @endif
                <h3 class="font-semibold text-zinc-900">{{ $work->title }}</h3>
                <p class="mt-0.5 text-sm font-medium text-zinc-500">{{ $work->company->name ?? '-' }}</p>
                @if($work->short_description) <p class="mt-2 line-clamp-3 text-sm text-zinc-600">{{ $work->short_description }}</p> @endif
                <p class="mt-auto pt-3 text-xs text-zinc-400">{{ $work->created_at?->format('d M Y') }}</p>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
                <p class="text-zinc-500">Belum ada karya yang kamu ikuti</p>
            </div>
        @endforelse
    </div>
</div>
@if($works->hasPages())
    <div class="mt-6">{{ $works->links() }}</div>
@endif
@endsection
