@extends('layouts.app')
@section('title', 'Perusahaan - YUHLEZ')

@section('body')
<section class="bg-zinc-950 py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-400">Perusahaan</p>
        <h1 class="mt-3 text-4xl font-bold text-white">Perusahaan Mitra YUHLEZ</h1>
        <p class="mt-3 text-zinc-400">Temukan perusahaan yang membuka program magang.</p>
    </div>
</section>

<section class="py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        @if($companies->count() > 0)
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($companies as $company)
                    <a href="{{ route('public.company.show', $company->slug) }}" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-zinc-500 font-bold">{{ substr($company->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600 transition-colors">{{ $company->name }}</h3>
                                <p class="text-sm text-zinc-500">{{ $company->programs_count ?? 0 }} program magang</p>
                            </div>
                        </div>
                        @if($company->short_description)
                            <p class="mt-3 line-clamp-2 text-sm text-zinc-600">{{ $company->short_description }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $companies->links() }}</div>
        @else
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center">
                <p class="text-zinc-500">Belum ada perusahaan terdaftar.</p>
            </div>
        @endif
    </div>
</section>
@endsection
