@extends('layouts.app')

@section('body')
<div class="flex min-h-screen flex-col bg-zinc-50">
    {{-- Dashboard Header --}}
    <header class="sticky top-0 z-40 border-b border-zinc-800 bg-zinc-950">
        <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="YUHLEZ Beranda">
                <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" height="28" class="h-7 w-auto" />
            </a>

            <div class="flex items-center gap-3">
                <span class="hidden rounded-full bg-zinc-800 px-3 py-1 text-xs font-medium text-zinc-200 sm:inline-flex">
                    @if(Auth::user()->role === 'ROOT') Administrator
                    @elseif(Auth::user()->role === 'COMPANY') Perusahaan
                    @elseif(Auth::user()->role === 'INTERN') Intern
                    @endif
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-lg border border-zinc-700 px-3 py-1.5 text-sm font-medium text-zinc-300 transition-colors hover:border-zinc-500 hover:text-white">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    {{-- Profile Incomplete Banner --}}
    @php
        $showIncomplete = Auth::user()->role !== 'ROOT' && (
            (Auth::user()->role === 'INTERN' && (!Auth::user()->internProfile || !Auth::user()->internProfile->whatsapp)) ||
            (Auth::user()->role === 'COMPANY' && (!Auth::user()->companyProfile || !Auth::user()->companyProfile->name))
        );
    @endphp
    @if($showIncomplete)
        <div class="border-b border-amber-200 bg-amber-50">
            <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
                <div>
                    <p class="text-sm font-semibold text-amber-900">Profil Anda belum lengkap.</p>
                    <p class="text-xs text-amber-800">Lengkapi profil terlebih dahulu untuk dapat mendaftar program magang.</p>
                </div>
                <a href="{{ Auth::user()->role === 'INTERN' ? route('intern.profile.edit') : route('company.profile.edit') }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-amber-600">Lengkapi Profil</a>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="flex flex-1">
        {{-- Sidebar Desktop --}}
        <aside class="sticky top-16 hidden h-[calc(100vh-4rem)] w-60 shrink-0 overflow-y-auto border-r border-zinc-200 bg-white p-4 lg:block">
            <nav class="space-y-1" aria-label="Navigasi dashboard">
                @yield('sidebar-nav')
            </nav>
        </aside>

        {{-- Drawer Mobile --}}
        <div id="sidebar-drawer" class="hidden fixed inset-0 z-50 lg:hidden" role="dialog">
            <button type="button" aria-label="Tutup menu" class="absolute inset-0 bg-zinc-950/50" onclick="document.getElementById('sidebar-drawer').classList.add('hidden')"></button>
            <div class="absolute left-0 top-0 h-full w-64 bg-white p-4 shadow-xl">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-sm font-semibold text-zinc-900">Menu</span>
                    <button type="button" onclick="document.getElementById('sidebar-drawer').classList.add('hidden')" class="rounded-lg px-2 py-1 text-sm text-zinc-500 hover:bg-zinc-100">✕</button>
                </div>
                <nav class="space-y-1">
                    @yield('sidebar-nav')
                </nav>
            </div>
        </div>

        {{-- Content --}}
        <div class="min-w-0 flex-1">
            <div class="sticky top-16 z-30 border-b border-zinc-200 bg-zinc-50/95 px-4 py-2.5 backdrop-blur lg:hidden">
                <button type="button" onclick="document.getElementById('sidebar-drawer').classList.remove('hidden')" class="rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm font-medium text-zinc-700">☰ Menu</button>
            </div>
            <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="border-t border-zinc-200 bg-white">
        <div class="mx-auto w-full max-w-6xl px-4 py-6 text-center text-xs text-zinc-400 sm:px-6">
            &copy; {{ date('Y') }} CV Talang Digital Indonesia (YUHLEZ Software House)
        </div>
    </footer>
</div>
@endsection
