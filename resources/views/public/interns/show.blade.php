@extends('layouts.app')

@section('title', $intern->name . ' - Intern - YUHLEZ')

@section('body')
<div class="bg-zinc-950 text-white">
    <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20">
        <a href="{{ route('home') }}" class="text-sm text-zinc-400 hover:text-yellow-400">← Beranda</a>

        <div class="mt-8 flex flex-col items-center gap-6 sm:flex-row sm:items-start">
            <div class="w-24 h-24 rounded-full bg-zinc-800 flex items-center justify-center overflow-hidden border-2 border-zinc-700">
                @if($intern->photo)
                    <img src="{{ route('files.download', $intern->profile_photo_file_id) }}" alt="{{ $intern->name }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <span class="text-3xl font-bold text-zinc-400">{{ substr($intern->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="text-center sm:text-left">
                <h1 class="text-3xl font-bold">{{ $intern->name }}</h1>
                <p class="mt-1 text-zinc-400">Intern</p>
                @if($intern->short_description)
                    <p class="mt-3 max-w-xl text-zinc-300">{{ $intern->short_description }}</p>
                @endif
                <div class="mt-4 flex flex-wrap justify-center gap-3 sm:justify-start">
                    @if($intern->whatsapp)
                        <a href="https://wa.me/{{ ltrim($intern->whatsapp, '+') }}" target="_blank" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-500">WhatsApp</a>
                    @endif
                    @if($intern->contact_email)
                        <a href="mailto:{{ $intern->contact_email }}" class="rounded-lg border border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-300 hover:border-zinc-500 hover:text-white">Email</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Programs --}}
@if($programs->count() > 0)
<section class="py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <h2 class="text-xl font-bold text-zinc-900">Program Magang Diikuti</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($programs as $program)
                <a href="{{ route('public.program.show', $program->slug) }}" class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Diterima</span>
                    </div>
                    <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $program->title }}</h3>
                    <p class="mt-1 text-sm text-zinc-600">{{ $program->company->name ?? '-' }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Works --}}
@if($works->count() > 0)
<section class="bg-zinc-50 py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <h2 class="text-xl font-bold text-zinc-900">Karya Yang Diikuti</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($works as $work)
                <a href="{{ route('public.work.show', $work->slug) }}" class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $work->title }}</h3>
                        <span class="shrink-0 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-800">Program</span>
                    </div>
                    @if($work->short_description)
                        <p class="mt-1.5 line-clamp-2 text-sm text-zinc-600">{{ $work->short_description }}</p>
                    @endif
                    <p class="mt-auto pt-3 text-xs text-zinc-500">{{ $work->company->name ?? '-' }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($programs->count() === 0 && $works->count() === 0)
<section class="py-16">
    <div class="mx-auto max-w-6xl px-4 text-center sm:px-6">
        <p class="text-zinc-500">Intern ini belum memiliki program atau karya yang tercatat.</p>
    </div>
</section>
@endif
@endsection
