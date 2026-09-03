@extends('layouts.app')

@section('title', 'Reset Password - YUHLEZ')

@section('body')
<div class="flex min-h-[80vh] items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-zinc-900">Reset Password</h1>
                <p class="mt-2 text-sm text-zinc-500">Masukkan password baru Anda di bawah ini.</p>
            </div>

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
                        <input type="email" name="email_display" id="email" value="{{ $email }}" disabled readonly
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl bg-zinc-50 text-sm text-zinc-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" required autofocus
                            class="w-full px-4 py-2.5 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none text-sm"
                            placeholder="Minimal 6 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-2.5 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none text-sm"
                            placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-yellow-400 text-zinc-950 font-semibold rounded-xl hover:bg-yellow-300 transition-colors text-sm">
                        Ubah Password
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
