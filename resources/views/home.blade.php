@extends('layouts.app')

@php
    $hero = $homepageSections->get('hero')?->content ?? [];
    $about = $homepageSections->get('about')?->content ?? [];
    $teamItems = $homepageSections->get('team')?->content['items'] ?? [];
    $serviceItems = $homepageSections->get('services')?->content['items'] ?? [];
    $processSection = $homepageSections->get('process');
    $processSteps = $processSection ? ($processSection->content['steps'] ?? []) : [];
    $processData = $processSection?->content ?? [];
    $contributorItems = $homepageSections->get('contributors')?->content['items'] ?? [];
    $cta = $homepageSections->get('cta')?->content ?? [];
@endphp

@section('title', 'YUHLEZ - Solusi Digital & Platform Magang')
@section('description', $hero['description'] ?? 'YUHLEZ Software House - solusi website, web apps, dan sistem digital dari Tegal.')

@section('body')
{{-- HERO with 3D Particle Grid --}}
<section class="bg-zinc-950 text-white relative overflow-hidden min-h-[85vh] flex items-center">
    <canvas id="heroCanvas" class="absolute inset-0 w-full h-full"></canvas>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-zinc-950 pointer-events-none"></div>
    <div class="mx-auto grid w-full max-w-6xl gap-10 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-[1.2fr_0.8fr] lg:items-center relative z-10">
        <div>
            @if(!empty($hero['subtitle']))
                <p class="text-sm font-semibold uppercase tracking-widest text-yellow-400 hero-subtitle">{{ $hero['subtitle'] }}</p>
            @endif
            <h1 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl hero-title">{!! str_replace('YUHLEZ', '<span class="gradient-text">YUHLEZ</span>', $hero['title'] ?? 'From Useless to YUHLEZ') !!}</h1>
            @if(!empty($hero['description']))
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-zinc-300 hero-desc">{{ $hero['description'] }}</p>
            @endif
            <div class="mt-8 flex flex-wrap gap-3 hero-cta">
                @if(!empty($hero['cta_primary_text']))
                    <a href="{{ $hero['cta_primary_url'] ?? route('public.programs') }}" class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-semibold text-zinc-950 transition-all hover:bg-yellow-300 hover:shadow-lg hover:shadow-yellow-400/25 magnetic-btn glow-btn">{{ $hero['cta_primary_text'] }}</a>
                @endif
                <a href="https://portfolio.yuhlez.com/" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-zinc-700 px-5 py-3 text-sm font-semibold text-zinc-200 transition-colors hover:border-zinc-500 hover:text-white magnetic-btn">Lihat Portfolio</a>
                @if(!empty($hero['cta_secondary_text']))
                    <a href="{{ $hero['cta_secondary_url'] ?? route('login') }}" class="rounded-xl border border-zinc-700 px-5 py-3 text-sm font-semibold text-zinc-200 transition-colors hover:border-zinc-500">{{ $hero['cta_secondary_text'] }}</a>
                @endif
            </div>
        </div>
        <div class="hidden rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 lg:block hero-card">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <span class="text-zinc-950 font-bold text-lg">Y</span>
                </div>
                <span class="text-2xl font-bold">YUHLEZ</span>
            </div>
            <p class="mt-4 text-sm leading-relaxed text-zinc-400">
                CV Talang Digital Indonesia atau YUHLEZ Software House melahirkan karya-karya inovatif di bidang sistem informasi manajemen, bisnis, dan pemerintah, dengan komitmen solusi transformasi digital yang berkualitas dan pengembangan berkelanjutan.
            </p>
            <dl class="mt-6 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-zinc-500">Kantor pusat</dt><dd class="text-right text-zinc-200">Tegal, Jawa Tengah</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-zinc-500">Lokasi utama</dt><dd class="text-right text-zinc-200">Kalisapu, Slawi, Tegal</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-zinc-500">Berdiri</dt><dd class="text-zinc-200">2021</dd></div>
            </dl>
        </div>
    </div>
</section>

@if(!empty($about['heading']))
{{-- TENTANG --}}
<section class="py-16 sm:py-20 glow-section">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="reveal">
        @if(!empty($about['subtitle']))<p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">{{ $about['subtitle'] }}</p>@endif
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">{{ $about['heading'] }}</h2>
        @if(!empty($about['description']))<p class="mt-3 max-w-3xl text-zinc-600">{{ $about['description'] }}</p>@endif
        </div>
        <div class="mt-8 grid gap-6 lg:grid-cols-3 stagger-grid reveal">
            @if(!empty($about['vision']))
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 lg:col-span-1 hover-lift">
                    <h3 class="text-lg font-semibold text-zinc-900">Visi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $about['vision'] }}</p>
                </div>
            @endif
            @if(!empty($about['mission_items']) && count($about['mission_items']) > 0)
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 lg:col-span-2 hover-lift">
                    <h3 class="text-lg font-semibold text-zinc-900">Misi</h3>
                    <ol class="mt-2 list-decimal space-y-1.5 pl-5 text-sm leading-relaxed text-zinc-600">
                        @foreach($about['mission_items'] as $m)<li>{{ $m }}</li>@endforeach
                    </ol>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

@if(count($teamItems) > 0)
<div class="section-divider my-0"></div>
{{-- TIM --}}
<section class="bg-zinc-50 py-16 sm:py-20 relative overflow-hidden">
    <div class="gradient-mesh"></div>
    <div class="mx-auto max-w-6xl px-4 sm:px-6 relative z-10">
        <div class="text-center mb-12">
            <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Tim Kami</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">Our Core <span class="gradient-text">Team</span></h2>
            <p class="mt-3 max-w-xl mx-auto text-zinc-500">Orang-orang hebat di balik setiap karya YUHLEZ</p>
        </div>
        <div class="flex flex-wrap justify-center gap-10 lg:gap-14">
            @foreach($teamItems as $i => $member)
                <div class="text-center w-[260px]">
                    <div class="mx-auto w-[240px] h-[360px] overflow-hidden rounded-2xl bg-white shadow-lg">
                        @if(!empty($member['photo']))
                            <img src="{{ asset($member['photo']) }}" alt="{{ $member['name'] }}" loading="lazy" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-zinc-100 text-6xl text-zinc-300 font-bold">{{ substr($member['name'], 0, 1) }}</div>
                        @endif
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-zinc-900">{{ $member['name'] }}</h3>
                    <p class="mt-1 text-sm font-medium text-yellow-600">{{ $member['focus'] ?? $member['role'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(count($serviceItems) > 0)
<div class="section-divider my-0"></div>
{{-- LAYANAN --}}
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Layanan</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Solusi digital untuk kebutuhan Anda</h2>
        <p class="mt-3 max-w-3xl text-zinc-600">Dari website profile hingga sistem manajemen dengan dukungan 24/7.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($serviceItems as $service)
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 service-card tilt-card reveal reveal-delay-{{ ($loop->index % 3) + 1 }}">
                    <h3 class="font-semibold text-zinc-900">{{ $service['title'] ?? '' }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $service['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featuredWorks->count() > 0)
{{-- KARYA INTERN --}}
<section id="portfolio" class="bg-zinc-50 py-16 sm:py-20 relative overflow-hidden">
    <div class="gradient-mesh"></div>
    <div class="mx-auto max-w-6xl px-4 sm:px-6 relative z-10">
        <div class="text-center mb-10 reveal">
            <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Karya</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">Karya nyata, bukan sekadar <span class="gradient-text">janji</span></h2>
            <p class="mt-3 max-w-3xl text-zinc-500 mx-auto">Project yang dikerjakan oleh intern YUHLEZ bersama perusahaan mitra.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredWorks as $work)
                <a href="{{ route('public.work.show', $work->slug) }}" class="group relative flex flex-col rounded-2xl border border-zinc-200 bg-white shadow-sm overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1 reveal reveal-delay-{{ ($loop->index % 3) + 1 }}">
                    {{-- Header gradient --}}
                    <div class="relative h-36 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/10 to-transparent"></div>
                        <span class="relative z-10 rounded-lg bg-yellow-400/90 px-3 py-1 text-xs font-bold uppercase tracking-wide text-zinc-950">{{ $work->category ?? $work->work_type }}</span>
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 p-5 flex flex-col">
                        <h3 class="font-bold text-lg text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $work->title }}</h3>
                        <p class="mt-2 text-sm text-zinc-500 line-clamp-2 flex-1">{{ $work->short_description ?? '' }}</p>
                        {{-- Intern avatars --}}
                        @if($work->interns->count() > 0)
                            <div class="mt-3 flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    @foreach($work->interns->take(3) as $wi)
                                        @if($wi->intern)
                                            <div class="w-7 h-7 rounded-full bg-zinc-200 border-2 border-white flex items-center justify-center">
                                                <span class="text-[10px] font-semibold text-zinc-600">{{ substr($wi->intern->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <span class="text-xs text-zinc-400">{{ $work->interns->count() }} intern</span>
                            </div>
                        @endif
                        {{-- Links --}}
                        <div class="mt-4 pt-3 border-t border-zinc-100 flex items-center gap-3">
                            @if(!empty($work->source_code_url))
                                <a href="{{ $work->source_code_url }}" target="_blank" rel="noopener" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                                    Source Code
                                </a>
                            @endif
                            @if(!empty($work->deploy_url))
                                <a href="{{ $work->deploy_url }}" target="_blank" rel="noopener" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Live Demo
                                </a>
                            @endif
                            @if($work->gallery->count() > 0)
                                <span class="ml-auto inline-flex items-center gap-1 text-xs text-zinc-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $work->gallery->count() }} foto
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-center reveal">
            <a href="{{ route('public.works') }}" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-zinc-800 hover:shadow-lg">
                Lihat Semua Karya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- PROGRAM MAGANG (dynamic from DB) --}}
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Program Magang</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Program magang terbaru</h2>
        <p class="mt-3 max-w-3xl text-zinc-600">Data langsung dari platform YUHLEZ, daftar, lamar, dan pantau pendaftaranmu.</p>
        @if($featuredPrograms->count() > 0)
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php $revealCount = 0; @endphp
                @foreach($featuredPrograms as $program)
                    @php $revealCount++; @endphp
                    <a href="{{ route('public.program.show', $program->slug) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition-all hover:shadow-lg hover:-translate-y-1 reveal reveal-delay-{{ min($revealCount, 3) }}">
                        <div class="flex h-32 items-center justify-center bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-950">
                            <span class="rounded-lg bg-yellow-400 px-3 py-1 text-xs font-bold uppercase tracking-wide text-zinc-950">Magang</span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-2 mb-2">
                                @if($program->registration_end >= now())
                                    <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Buka</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500 rounded-full">Tutup</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $program->title }}</h3>
                            <p class="mt-1 text-sm text-zinc-600">{{ $program->company->name ?? '-' }}</p>
                            <p class="mt-2 line-clamp-2 text-sm text-zinc-500">{{ $program->short_description }}</p>
                            <div class="mt-auto pt-3 flex items-center justify-between text-xs text-zinc-400">
                                <span>{{ $program->positions->count() }} posisi</span>
                                <span>Tutup: {{ $program->registration_end->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('public.programs') }}" class="inline-flex items-center gap-1 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-zinc-800">Lihat semua program</a>
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center">
                <p class="text-zinc-500">Belum ada program magang yang tersedia.</p>
            </div>
        @endif
    </div>
</section>

{{-- PERUSAHAAN (dynamic from DB) --}}
<section class="bg-zinc-50 py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Perusahaan</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Perusahaan yang membuka magang</h2>
        @if($featuredCompanies->count() > 0)
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredCompanies as $company)
                    <a href="{{ route('public.company.show', $company->slug) }}" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-zinc-500 font-bold">{{ substr($company->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $company->name }}</h3>
                                <p class="text-sm text-zinc-500">{{ $company->programs_count ?? $company->programs->count() }} program magang</p>
                            </div>
                        </div>
                        @if($company->short_description)<p class="mt-3 line-clamp-2 text-sm text-zinc-600">{{ $company->short_description }}</p>@endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-zinc-200 bg-white p-12 text-center"><p class="text-zinc-500">Belum ada perusahaan terdaftar.</p></div>
        @endif
    </div>
</section>

@if(count($contributorItems) > 0)
{{-- KONTRIBUTOR --}}
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Kontributor</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Mitra yang mendukung YUHLEZ</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($contributorItems as $contrib)
                <a href="{{ $contrib['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                    @if(!empty($contrib['logo']))
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-zinc-100">
                            <img src="{{ asset($contrib['logo']) }}" alt="{{ $contrib['name'] }}" class="h-full w-full object-contain" loading="lazy">
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600">{{ $contrib['name'] }}</h3>
                        @if(!empty($contrib['description']))<p class="mt-0.5 text-xs text-zinc-500">{{ $contrib['description'] }}</p>@endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- KARYA (dynamic from DB) --}}
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Karya</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">Karya terbaru di platform</h2>
        @if($featuredWorks->count() > 0)
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredWorks as $work)
                    <a href="{{ route('public.work.show', $work->slug) }}" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-all hover:shadow-lg hover:-translate-y-1 reveal reveal-delay-{{ ($loop->index % 3) + 1 }}">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $work->title }}</h3>
                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $work->work_type === 'PUBLIC_WORK' ? 'bg-yellow-50 text-yellow-800' : 'bg-sky-50 text-sky-800' }}">{{ $work->work_type === 'PUBLIC_WORK' ? 'Karya' : 'Program' }}</span>
                        </div>
                        @if($work->short_description)<p class="mt-1.5 line-clamp-3 text-sm leading-relaxed text-zinc-600">{{ $work->short_description }}</p>@endif
                        <p class="mt-auto pt-3 text-xs text-zinc-500">{{ $work->company->name ?? '-' }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('public.works') }}" class="inline-flex items-center gap-1 rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-zinc-800">Lihat semua karya</a>
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center"><p class="text-zinc-500">Belum ada karya yang dipublikasikan.</p></div>
        @endif
    </div>
</section>

@if(count($processSteps) > 0)
{{-- CARA KERJA --}}
<section class="bg-zinc-50 py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        @if(!empty($processData['subtitle']))<p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">{{ $processData['subtitle'] }}</p>@endif
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900">{{ $processData['heading'] ?? 'Cara kerja' }}</h2>
        @if(!empty($processData['description']))<p class="mt-3 max-w-3xl text-zinc-600">{{ $processData['description'] }}</p>@endif
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 stagger-grid reveal">
            @foreach($processSteps as $step)
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 hover-lift">
                    <span class="text-3xl font-extrabold text-yellow-500">{{ $step['step'] ?? str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-2 font-semibold text-zinc-900">{{ $step['title'] ?? '' }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ $step['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- REGISTRATION CTA --}}
<section class="bg-zinc-50 py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="text-center mb-12">
            <p class="text-sm font-semibold uppercase tracking-widest text-yellow-500">Bergabung dengan YUHLEZ</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">Mulai Perjalanan Digital Anda</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-2 max-w-4xl mx-auto">
            {{-- Intern CTA --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-8 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-zinc-900">Daftar sebagai Intern</h3>
                <p class="mt-2 text-sm text-zinc-600">Bergabung dengan program magang dan kembangkan skill digital Anda bersama YUHLEZ.</p>
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('register.intern') }}" class="rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white hover:bg-zinc-800 transition-colors">Daftar Sekarang</a>
                    <span class="text-xs text-zinc-400">atau</span>
                    <a href="{{ route('login') }}" class="text-sm text-yellow-600 hover:text-yellow-500 font-medium">Masuk</a>
                </div>
            </div>
            {{-- Company CTA --}}
            <div class="rounded-2xl border border-yellow-400 bg-yellow-50 p-8 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-zinc-900">Ingin Bermitra dengan YUHLEZ?</h3>
                <p class="mt-2 text-sm text-zinc-600">Daftarkan perusahaan Anda dan buka program magang untuk talenta digital terbaik.</p>
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('register.company') }}" class="rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">Daftar sebagai Perusahaan</a>
                    <span class="text-xs text-yellow-600">atau</span>
                    <a href="{{ route('login') }}" class="text-sm text-yellow-600 hover:text-yellow-500 font-medium">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if(!empty($cta['heading']))
{{-- CTA --}}
<section class="bg-zinc-950 text-white">
    <div class="mx-auto w-full max-w-6xl px-4 py-16 text-center sm:px-6 sm:py-20 reveal">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">{!! str_replace('go digital', '<span class="text-yellow-400">go digital?</span>', $cta['heading'] ?? 'Sudah siap untuk go digital?') !!}</h2>
        @if(!empty($cta['description']))<p class="mx-auto mt-3 max-w-xl text-zinc-400">{{ $cta['description'] }}</p>@endif
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            @if(!empty($cta['email']))
                <a href="mailto:{{ $cta['email'] }}" class="rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300 magnetic-btn glow-btn">Hubungi Kami</a>
            @endif
            @if(!empty($cta['whatsapp']))
                <a href="https://wa.me/{{ $cta['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-green-400 magnetic-btn">WhatsApp</a>
            @endif
            <a href="{{ route('public.programs') }}" class="rounded-xl border border-zinc-700 px-6 py-3 text-sm font-semibold text-zinc-200 transition-colors hover:border-zinc-500 magnetic-btn">Jelajahi Program Magang</a>
        </div>
    </div>
</section>
@endif
@endsection

@section('styles')
<style>
    .dark body, .dark .bg-white { background: #0a0a0a !important; color: #e4e4e7 !important; }
    .dark .bg-zinc-50 { background: #18181b !important; }
    .dark .bg-zinc-100 { background: #27272a !important; }
    .dark .text-zinc-900, .dark .text-zinc-800 { color: #fafafa !important; }
    .dark .text-zinc-700, .dark .text-zinc-600 { color: #a1a1aa !important; }
    .dark .text-zinc-500, .dark .text-zinc-400 { color: #71717a !important; }
    .dark .border-zinc-200 { border-color: #27272a !important; }
    .dark .bg-white { background: #18181b !important; }
    .dark header { background: rgba(0,0,0,0.95) !important; border-color: #27272a !important; }
    .dark footer { background: #000 !important; border-color: #27272a !important; }
    #heroCanvas { position: absolute; inset: 0; width: 100%; height: 100%; }
</style>
@endsection

@section('scripts')
    @parent
    <script>
    (function() {
        const canvas = document.getElementById('heroCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let w, h, mouse = { x: 0.5, y: 0.5 };
        const cols = 50, rows = 35;
        let points = [];

        function resize() {
            w = canvas.width = canvas.offsetWidth;
            h = canvas.height = canvas.offsetHeight;
            init();
        }

        function init() {
            points = [];
            const sx = w / cols, sy = h / rows;
            for (let j = 0; j <= rows; j++) {
                for (let i = 0; i <= cols; i++) {
                    points.push({ x: i * sx, y: j * sy, bx: i * sx, by: j * sy });
                }
            }
        }

        canvas.parentElement.addEventListener('mousemove', e => {
            const r = canvas.getBoundingClientRect();
            mouse.x = (e.clientX - r.left) / r.width;
            mouse.y = (e.clientY - r.top) / r.height;
        });

        function draw() {
            ctx.clearRect(0, 0, w, h);
            const mx = mouse.x * w, my = mouse.y * h;
            const strength = 80;

            points.forEach(p => {
                const dx = p.bx - mx, dy = p.by - my;
                const dist = Math.sqrt(dx * dx + dy * dy);
                const force = Math.max(0, 1 - dist / 250) * strength;
                p.x = p.bx + (dx / (dist || 1)) * force;
                p.y = p.by + (dy / (dist || 1)) * force;
            });

            // Draw grid lines
            ctx.strokeStyle = 'rgba(234, 179, 8, 0.06)';
            ctx.lineWidth = 0.5;
            for (let j = 0; j <= rows; j++) {
                ctx.beginPath();
                for (let i = 0; i <= cols; i++) {
                    const p = points[j * (cols + 1) + i];
                    i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y);
                }
                ctx.stroke();
            }
            for (let i = 0; i <= cols; i++) {
                ctx.beginPath();
                for (let j = 0; j <= rows; j++) {
                    const p = points[j * (cols + 1) + i];
                    j === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y);
                }
                ctx.stroke();
            }

            // Draw dots at intersections
            points.forEach(p => {
                const dx = p.x - p.bx, dy = p.y - p.by;
                const displacement = Math.sqrt(dx * dx + dy * dy);
                const alpha = 0.15 + Math.min(displacement / strength, 1) * 0.6;
                const radius = 1 + Math.min(displacement / strength, 1) * 2;
                ctx.beginPath();
                ctx.arc(p.x, p.y, radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(234, 179, 8, ${alpha})`;
                ctx.fill();
            });

            requestAnimationFrame(draw);
        }

        window.addEventListener('resize', resize);
        resize();
        draw();
    })();
    </script>
@endsection

