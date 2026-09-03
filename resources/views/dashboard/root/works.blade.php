@extends('layouts.dashboard')
@section('page-title', 'Karya - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Semua Karya</h1>
        <p class="mt-1 text-sm text-zinc-500">Karya dari seluruh perusahaan</p>
    </div>
</div>
<div class="flex gap-2 mb-4">
    <a href="{{ route('root.works.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('type') ? 'bg-yuhlez-primary text-white' : 'bg-gray-100 text-gray-600' }}">Semua</a>
    <a href="{{ route('root.works.index', ['type' => 'PROGRAM_WORK']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('type') === 'PROGRAM_WORK' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600' }}">Program</a>
</div>
<form action="{{ route('root.works.index') }}" method="GET" class="flex gap-2 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karya..." class="flex-1 rounded-xl border border-zinc-300 px-4 py-2 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
    <button type="submit" class="rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">Cari</button>
</form>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($works as $work)
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-semibold text-zinc-900">{{ $work->title }}</h3>
                <span class="shrink-0 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-800">{{ $work->work_type === 'PUBLIC_WORK' ? 'Karya' : 'Program' }}</span>
            </div>
            @if($work->short_description) <p class="mt-1.5 line-clamp-2 text-sm text-zinc-600">{{ $work->short_description }}</p> @endif
            <p class="mt-3 text-xs text-zinc-400">{{ $work->company->name ?? '-' }} · {{ $work->category ?? '-' }}</p>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('root.works.show', $work->slug) }}" class="text-sm text-yuhlez-primary hover:underline">Detail</a>
                <form action="{{ route('root.works.destroy', $work->slug) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center"><p class="text-zinc-500">Belum ada karya</p></div>
    @endforelse
</div>
<div class="mt-6">{{ $works->withQueryString()->links() }}</div>
@endsection