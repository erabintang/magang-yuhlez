@extends('layouts.app')

@section('title', 'Program Magang - YUHLEZ')

@section('body')
{{-- Navigation --}}
<header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-yuhlez-primary rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold">Y</span>
                    </div>
                    <span class="text-xl font-bold text-yuhlez-dark">YUHLEZ</span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('public.programs') }}" class="text-yuhlez-primary font-medium">Program Magang</a>
                <a href="{{ route('public.companies') }}" class="text-gray-600 hover:text-yuhlez-primary">Perusahaan</a>
                <a href="{{ route('public.works') }}" class="text-gray-600 hover:text-yuhlez-primary">Karya</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route(Auth::user()->role === 'ROOT' ? 'root.dashboard' : (Auth::user()->role === 'COMPANY' ? 'company.dashboard' : 'intern.dashboard')) }}" class="text-gray-600 hover:text-yuhlez-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary transition">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Program Magang</h1>
        <p class="mt-2 text-gray-600">Temukan program magang yang sesuai dengan minat dan keahlianmu</p>
    </div>

    {{-- Search --}}
    <form action="{{ route('public.programs') }}" method="GET" class="mb-8">
        <div class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari program..."
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            <button type="submit" class="px-6 py-3 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary transition">
                Cari
            </button>
        </div>
    </form>

    {{-- Programs Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($programs as $program)
            <a href="{{ route('public.program.show', $program->slug) }}" class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                @if($program->banner)
                    <img src="{{ $program->banner->storage_path }}" alt="{{ $program->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-yuhlez-primary to-yuhlez-accent"></div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-1 bg-yuhlez-light text-yuhlez-primary text-xs font-medium rounded">Magang</span>
                        @if($program->registration_end >= now())
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Buka</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded">Tutup</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $program->title }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $program->company->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $program->short_description }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $program->registration_end->format('d M Y') }}
                        </div>
                        <span>{{ $program->positions->count() }} posisi</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada program magang tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $programs->withQueryString()->links() }}
    </div>
</main>
@endsection
