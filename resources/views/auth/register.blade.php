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
                <span class="mt-3 inline-block rounded-xl bg-zinc-900 px-5 py-2 text-sm font-semibold text-white group-hover:bg-zinc-800 transition-colors">Daftar Sekarang</span>
            </a>

            {{-- Company Registration --}}
            <a href="{{ route('register.company') }}" class="block rounded-2xl border border-yellow-400 bg-yellow-50 p-6 text-center hover:shadow-lg transition-all group">
                <div class="w-14 h-14 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900">Ingin Bermitra dengan YUHLEZ?</h3>
                <p class="mt-1 text-sm text-yellow-700">Daftarkan perusahaan Anda dan buka program magang</p>
                <span class="mt-3 inline-block rounded-xl bg-yellow-400 px-5 py-2 text-sm font-semibold text-zinc-950 group-hover:bg-yellow-300 transition-colors">Daftar sebagai Perusahaan</span>
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
