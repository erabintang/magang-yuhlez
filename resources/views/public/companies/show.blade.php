@extends('layouts.app')
@section('title', $company->name . ' - YUHLEZ')

@section('body')
<section class="bg-zinc-950 py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <a href="{{ route('public.companies') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">&larr; Kembali ke Perusahaan</a>
        <div class="mt-4 flex items-center gap-4">
            <div class="w-16 h-16 bg-zinc-800 rounded-2xl flex items-center justify-center">
                <span class="text-2xl font-bold text-yellow-400">{{ substr($company->name, 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $company->name }}</h1>
                <p class="text-zinc-400">{{ $company->contact_email }}</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if($company->short_description)
                    <p class="text-lg text-zinc-600 mb-6">{{ $company->short_description }}</p>
                @endif
                @if($company->description)
                    <div class="prose max-w-none text-zinc-700">{!! $company->description !!}</div>
                @endif
            </div>
            <div class="space-y-4">
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                    <h3 class="font-semibold text-zinc-900 mb-3">Informasi</h3>
                    <dl class="space-y-2 text-sm">
                        @if($company->whatsapp)
                            <div class="flex justify-between"><dt class="text-zinc-500">WhatsApp</dt><dd>{{ $company->whatsapp }}</dd></div>
                        @endif
                        @if($company->address)
                            <div class="flex justify-between"><dt class="text-zinc-500">Alamat</dt><dd class="text-right">{{ $company->address }}</dd></div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        @if($company->programs->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-zinc-900 mb-6">Program Magang</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($company->programs as $program)
                        <a href="{{ route('public.program.show', $program->slug) }}" class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                            <h3 class="font-semibold text-zinc-900 group-hover:text-yellow-600">{{ $program->title }}</h3>
                            <p class="mt-1 text-sm text-zinc-500">{{ $program->positions->count() }} posisi</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
