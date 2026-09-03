@extends('layouts.dashboard')
@section('page-title', 'Kelola Beranda')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-zinc-900">Kelola Konten Beranda</h1>
    <p class="text-sm text-zinc-500 mt-1">Kelola semua section yang tampil di halaman beranda publik.</p>
</div>

<div class="grid gap-4">
    @php
        $sectionMeta = [
            'hero' => ['label' => 'Hero', 'desc' => 'Judul utama, deskripsi, dan tombol CTA', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            'about' => ['label' => 'Tentang', 'desc' => 'Visi, misi, dan deskripsi perusahaan', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            'team' => ['label' => 'Tim', 'desc' => 'Daftar anggota tim', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            'services' => ['label' => 'Layanan', 'desc' => 'Daftar layanan yang ditawarkan', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            'process' => ['label' => 'Cara Kerja', 'desc' => 'Langkah-langkah proses kerja', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            'contributors' => ['label' => 'Kontributor', 'desc' => 'Mitra dan kolaborator', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            'cta' => ['label' => 'Call to Action', 'desc' => 'Section ajakan di bagian bawah', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ];
    @endphp

    @foreach(['hero', 'about', 'team', 'services', 'process', 'contributors', 'cta'] as $key)
        @php
            $section = $sections->where('section_key', $key)->first();
            $meta = $sectionMeta[$key];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-5 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $section && $section->is_active ? 'bg-yellow-50 text-yellow-600' : 'bg-zinc-100 text-zinc-400' }} rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-zinc-900">{{ $meta['label'] }}</h3>
                    <p class="text-sm text-zinc-500">{{ $meta['desc'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($section)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-zinc-100 text-zinc-500' }}">
                        {{ $section->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        Belum diatur
                    </span>
                @endif
                <form action="{{ route('root.homepage.toggle', $key) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-zinc-500 hover:text-zinc-700 px-2 py-1 rounded hover:bg-zinc-100">
                        {{ $section && $section->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                <a href="{{ route('root.homepage.edit', $key) }}" class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition">
                    Edit
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
