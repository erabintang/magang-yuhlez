@extends('layouts.app')

@section('title', 'Daftar - YUHLEZ')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-zinc-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <a href="{{ route('home') }}" class="flex justify-center">
                <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" class="h-10 w-auto" />
            </a>
            <h2 class="mt-6 text-center text-3xl font-bold text-zinc-900">Daftar di YUHLEZ</h2>
            <p class="mt-2 text-center text-sm text-zinc-500">
                Pilih jenis akun yang ingin Anda daftarkan
            </p>
        </div>

        <div class="space-y-4">
            {{-- Intern Registration --}}
            <a href="{{ route('register.intern') }}" class="block rounded-2xl border border-zinc-200 bg-white p-6 text-center hover:shadow-lg hover:border-yellow-400 transition-all group">
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900">Daftar sebagai Intern</h3>
                <p class="mt-1 text-sm text-zinc-500">Bergabung dengan program magang dan kembangkan skill digital Anda</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('register.intern') }}" class="block w-full rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 transition-colors">Daftar Manual</a>
                    <a href="{{ route('google.redirect', ['intent' => 'intern']) }}" class="block w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-medium text-zinc-700 bg-white hover:bg-zinc-50 transition-colors">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>
            </a>

            {{-- Company Registration --}}
            <a href="{{ route('register.company') }}" class="block rounded-2xl border border-yellow-400 bg-yellow-50 p-6 text-center hover:shadow-lg transition-all group">
                <div class="w-14 h-14 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900">Ingin Bermitra dengan YUHLEZ?</h3>
                <p class="mt-1 text-sm text-yellow-700">Daftarkan perusahaan Anda dan buka program magang</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('register.company') }}" class="block w-full rounded-xl bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">Daftar Manual</a>
                    <a href="{{ route('google.redirect', ['intent' => 'company']) }}" class="block w-full rounded-xl border border-yellow-400 px-4 py-2.5 text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-100 transition-colors">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-0.5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>
            </a>
        </div>

        <div class="text-center">
            <p class="text-sm text-zinc-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-yellow-600 hover:text-yellow-500 font-medium">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
