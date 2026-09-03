@extends('layouts.app')

@section('title', 'Daftar sebagai Intern - YUHLEZ')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-zinc-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <a href="{{ route('home') }}" class="flex justify-center">
                <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" class="h-10 w-auto" />
            </a>
            <h2 class="mt-6 text-center text-3xl font-bold text-zinc-900">Daftar sebagai Intern</h2>
            <p class="mt-2 text-center text-sm text-zinc-500">
                Bergabung dengan program magang di YUHLEZ
            </p>
        </div>

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.intern.post') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1">Nama Lengkap</label>
                <input id="name" name="name" type="text" required autofocus
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
                <input id="email" name="email" type="email" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('email') border-red-500 @enderror"
                    placeholder="email@contoh.com" value="{{ old('email') }}">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('password') border-red-500 @enderror"
                    placeholder="Minimal 6 karakter">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none"
                    placeholder="Ulangi password">
            </div>

            <button type="submit" class="w-full rounded-xl bg-zinc-900 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-zinc-800">
                Daftar sebagai Intern
            </button>
        </form>

        {{-- Divider --}}
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-zinc-50 px-2 text-zinc-500">atau</span>
            </div>
        </div>

        {{-- Google OAuth for Intern --}}
        <a href="{{ route('google.redirect', ['intent' => 'intern']) }}" class="w-full flex items-center justify-center px-4 py-3 border border-zinc-300 rounded-xl shadow-sm text-sm font-medium text-zinc-700 bg-white hover:bg-zinc-50 transition-colors">
            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Daftar dengan Google
        </a>

        <div class="text-center">
            <p class="text-sm text-zinc-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-yellow-600 hover:text-yellow-500 font-medium">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
