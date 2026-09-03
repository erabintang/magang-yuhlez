@extends('layouts.app')

@section('title', 'Lupa Password - YUHLEZ')

@section('body')
<div class="flex min-h-[80vh] items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-zinc-900">Lupa Password?</h1>
                <p class="mt-2 text-sm text-zinc-500">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

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

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2.5 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none text-sm"
                            placeholder="nama@email.com">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-yellow-400 text-zinc-950 font-semibold rounded-xl hover:bg-yellow-300 transition-colors text-sm">
                        Kirim Tautan Reset
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
