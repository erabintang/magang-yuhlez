@extends('layouts.app')
@section('title', $program->title . ' - Program Magang - YUHLEZ')
@section('body')
<div class="bg-zinc-950 text-white">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
        <a href="{{ route('public.programs') }}" class="text-sm text-zinc-400 hover:text-yellow-400">← Program Magang</a>
        <div class="mt-8">
            @if($program->registration_end >= now())
                <span class="inline-flex rounded-full bg-green-500/20 px-3 py-1 text-xs font-semibold text-green-400">Pendaftaran Buka</span>
            @else
                <span class="inline-flex rounded-full bg-zinc-700/50 px-3 py-1 text-xs font-semibold text-zinc-400">Pendaftaran Tutup</span>
            @endif
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $program->title }}</h1>
            <p class="mt-2 text-lg text-zinc-400">{{ $program->company->name ?? '-' }}</p>
            @if($program->short_description)
                <p class="mt-4 max-w-3xl text-zinc-300">{{ $program->short_description }}</p>
            @endif
            <div class="mt-6 flex flex-wrap gap-4 text-sm text-zinc-400">
                @if($program->registration_start)
                    <span>📅 Buka: {{ $program->registration_start->format('d M Y') }}</span>
                @endif
                @if($program->registration_end)
                    <span>📅 Tutup: {{ $program->registration_end->format('d M Y') }}</span>
                @endif
                @if($program->program_start)
                    <span>🚀 Mulai: {{ $program->program_start->format('d M Y') }}</span>
                @endif
                @if($program->program_end)
                    <span>🏁 Selesai: {{ $program->program_end->format('d M Y') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <div class="grid gap-8 lg:grid-cols-3">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-8">
            @if($program->description)
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900 mb-3">Deskripsi Program</h2>
                    <div class="prose prose-sm max-w-none text-zinc-600">{!! $program->description !!}</div>
                </div>
            @endif

            {{-- Positions --}}
            @if($program->positions->count() > 0)
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-zinc-900 mb-4">Posisi Tersedia</h2>
                    <div class="space-y-4">
                        @foreach($program->positions as $position)
                            <div class="rounded-xl border border-zinc-100 bg-zinc-50 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="font-semibold text-zinc-900">{{ $position->name }}</h3>
                                        @if($position->description)
                                            <p class="mt-1 text-sm text-zinc-600">{{ $position->description }}</p>
                                        @endif
                                    </div>
                                    @if($position->quota)
                                        <span class="shrink-0 rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-medium text-zinc-600">Kuota: {{ $position->quota }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Company Info --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-zinc-900 mb-3">Perusahaan</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center">
                        <span class="text-zinc-500 font-bold">{{ substr($program->company->name ?? '?', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-zinc-900">{{ $program->company->name ?? '-' }}</p>
                        @if($program->company->address)
                            <p class="text-xs text-zinc-500">{{ $program->company->address }}</p>
                        @endif
                    </div>
                </div>
                @if($program->company->whatsapp)
                    <a href="https://wa.me/{{ ltrim($program->company->whatsapp, '+') }}" target="_blank" class="mt-3 block w-full rounded-xl bg-green-500 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-green-400">WhatsApp</a>
                @endif
            </div>

            {{-- Apply --}}
            @auth
                @if(Auth::user()->role === 'INTERN' && $program->registration_end >= now())
                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6">
                        <h3 class="font-semibold text-zinc-900 mb-2">Daftar Sekarang</h3>
                        <p class="text-sm text-zinc-600 mb-3">Pilih posisi dan kirim lamaranmu.</p>
                        @if(Auth::user()->internProfile && Auth::user()->internProfile->whatsapp)
                            <form action="{{ route('intern.applications.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="program_id" value="{{ $program->id }}">
                                <select name="position_id" required class="w-full rounded-xl border border-zinc-300 px-3 py-2 text-sm mb-3 focus:border-yellow-400 outline-none">
                                    <option value="">Pilih posisi</option>
                                    @foreach($program->positions as $pos)
                                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                                <textarea name="cover_letter" rows="3" class="w-full rounded-xl border border-zinc-300 px-3 py-2 text-sm mb-3 focus:border-yellow-400 outline-none" placeholder="Surat lamaran (opsional)"></textarea>
                                <button type="submit" class="w-full rounded-xl bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Kirim Lamaran</button>
                            </form>
                        @else
                            <a href="{{ route('intern.profile.edit') }}" class="block w-full rounded-xl bg-zinc-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-zinc-800">Lengkapi Profil Dulu</a>
                        @endif
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm text-center">
                    <p class="text-sm text-zinc-500 mb-3">Masuk untuk mendaftar program ini</p>
                    <a href="{{ route('login') }}" class="block w-full rounded-xl bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Masuk dengan Google</a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection