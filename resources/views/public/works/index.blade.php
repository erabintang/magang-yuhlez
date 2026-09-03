@extends('layouts.app')
@section('title', 'Karya - YUHLEZ')

@section('body')
<section class="bg-zinc-950 py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-400">Karya</p>
        <h1 class="mt-3 text-4xl font-bold text-white">Galeri Karya</h1>
        <p class="mt-3 text-zinc-400">Karya hasil program magang perusahaan.</p>
    </div>
</section>

<section class="py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        {{-- Filters --}}
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('public.works') }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ !request('type') ? 'bg-zinc-900 text-yellow-400' : 'border border-zinc-300 text-zinc-600 hover:bg-zinc-100' }}">Semua</a>
            <a href="{{ route('public.works', ['type' => 'PROGRAM_WORK']) }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request('type') === 'PROGRAM_WORK' ? 'bg-zinc-900 text-yellow-400' : 'border border-zinc-300 text-zinc-600 hover:bg-zinc-100' }}">Karya Program</a>
        </div>

        @if($works->count() > 0)
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($works as $work)
                    <a href="{{ route('public.work.show', $work->slug) }}" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                        @if($work->poster)
                            <div class="h-40 bg-zinc-100 rounded-xl mb-4 overflow-hidden">
                                <img src="{{ route('files.download', $work->poster_file_id) }}" alt="{{ $work->title }}" class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @else
                            <div class="h-40 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950 rounded-xl mb-4 flex items-center justify-center">
                                <span class="text-zinc-600 text-sm">No Image</span>
                            </div>
                        @endif
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $work->title }}</h3>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium bg-sky-50 text-sky-800">
                                Program
                            </span>
                        </div>
                        @if($work->category)
                            <span class="mt-1 inline-block rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-600">{{ $work->category }}</span>
                        @endif
                        @if($work->short_description)
                            <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ $work->short_description }}</p>
                        @endif
                        <div class="mt-auto pt-3 flex items-center gap-2 text-xs text-zinc-500">
                            <span>{{ $work->company->name ?? '-' }}</span>
                            @if($work->year)
                                <span>·</span>
                                <span>{{ $work->year }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $works->withQueryString()->links() }}</div>
        @else
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center">
                <p class="text-zinc-500">Belum ada karya yang dipublikasikan.</p>
            </div>
        @endif
    </div>
</section>
@endsection
