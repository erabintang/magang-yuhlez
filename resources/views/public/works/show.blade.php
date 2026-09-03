@extends('layouts.app')
@section('title', $work->title . ' - YUHLEZ')

@section('body')
<section class="py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <a href="{{ route('public.works') }}" class="text-sm text-zinc-600 hover:text-zinc-900 transition-colors">&larr; Kembali ke galeri karya</a>

        <div class="mt-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $work->work_type === 'PUBLIC_WORK' ? 'bg-yellow-50 text-yellow-800' : 'bg-sky-50 text-sky-800' }}">
                    {{ $work->work_type === 'PUBLIC_WORK' ? 'Karya Kreator' : 'Karya Program' }}
                </span>
                @if($work->category)
                    <span class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">{{ $work->category }}</span>
                @endif
                @if($work->year)
                    <span class="text-sm text-zinc-400">Tahun {{ $work->year }}</span>
                @endif
            </div>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">{{ $work->title }}</h1>

            @if($work->company)
                <p class="mt-1 font-medium text-zinc-500">
                    oleh
                    <a href="{{ route('public.company.show', $work->company->slug) }}" class="hover:text-zinc-900">{{ $work->company->name }}</a>
                </p>
            @endif
        </div>

        @if($work->short_description)
            <p class="mt-4 max-w-3xl font-medium text-zinc-600">{{ $work->short_description }}</p>
        @endif

        @if($work->description)
            <div class="mt-3 max-w-3xl leading-relaxed text-zinc-600 prose">{!! $work->description ?? '' !!}</div>
        @endif

        {{-- Gallery --}}
        @if($work->gallery->count() > 0)
            <section class="mt-10">
                <h2 class="text-lg font-semibold text-zinc-900">Galeri</h2>
                <p class="mt-1 text-sm text-zinc-500">{{ $work->gallery->count() }} foto</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($work->gallery as $gallery)
                        <div class="rounded-2xl border border-zinc-200 overflow-hidden bg-zinc-100 aspect-video">
                            @if($gallery->file)
                                <img src="{{ route('files.download', $gallery->file_id) }}" alt="Gallery" class="w-full h-full object-cover" loading="lazy">
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Interns --}}
        @if($work->work_type === 'PROGRAM_WORK' && $work->interns->count() > 0)
            <section class="mt-10">
                <h2 class="text-lg font-semibold text-zinc-900">Peserta karya</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($work->interns as $wi)
                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-sm font-medium text-zinc-700">{{ $wi->intern->name ?? 'Peserta' }}</span>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="pt-8">
            <a href="{{ route('public.works') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900">&larr; Kembali ke galeri karya</a>
        </div>
    </div>
</section>
@endsection
