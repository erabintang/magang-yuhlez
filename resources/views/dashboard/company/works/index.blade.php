@extends('layouts.dashboard')

@section('page-title', 'Karya - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Karya</h1>
        <p class="mt-1 text-sm text-zinc-500">Kelola karya perusahaan Anda</p>
    </div>
    <a href="{{ route('company.works.create') }}" class="rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300">+ Buat Karya</a>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($works as $work)
        <a href="{{ route('company.works.show', $work->slug) }}" class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
            @if($work->gallery->first())
                <div class="mb-3 aspect-video overflow-hidden rounded-xl bg-zinc-100">
                    <div class="w-full h-full bg-gradient-to-br from-zinc-200 to-zinc-300 flex items-center justify-center">
                        <span class="text-zinc-400 text-xs">{{ $work->gallery->count() }} foto</span>
                    </div>
                </div>
            @else
                <div class="mb-3 aspect-video rounded-xl bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950 flex items-center justify-center">
                    <span class="rounded-lg bg-yellow-400 px-3 py-1 text-xs font-bold uppercase tracking-wide text-zinc-950">{{ $work->category ?? 'Karya' }}</span>
                </div>
            @endif

            <div class="flex items-start justify-between gap-2">
                <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $work->title }}</h3>
                @if($work->is_published)
                    <span class="shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Published</span>
                @else
                    <span class="shrink-0 rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Draft</span>
                @endif
            </div>

            @if($work->short_description)
                <p class="mt-1.5 line-clamp-2 text-sm text-zinc-600">{{ $work->short_description }}</p>
            @endif

            <div class="mt-3 flex items-center justify-between text-xs text-zinc-400">
                <span>{{ $work->gallery->count() }} galeri</span>
                <span>{{ $work->interns->count() }} intern</span>
                @if($work->year)
                    <span>{{ $work->year }}</span>
                @endif
            </div>
        </a>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="mt-4 text-zinc-500">Belum ada karya</p>
            <a href="{{ route('company.works.create') }}" class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-yellow-600 hover:text-yellow-500">Buat karya pertama →</a>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $works->links() }}</div>
@endsection
