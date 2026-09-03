@extends('layouts.dashboard')
@section('page-title', 'Peserta Magang - YUHLEZ')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold text-zinc-900">Peserta Magang</h1><p class="mt-1 text-sm text-zinc-500">Intern yang menjadi peserta di program Anda</p></div>
<div class="space-y-8">
    @forelse($participants as $group)
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-zinc-900">{{ $group['program']->title }}</h2>
            <div class="space-y-3">
                @foreach($group['interns'] as $pi)
                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm">
                        <div>
                            <p class="font-medium text-zinc-900">{{ $pi->intern->name ?? 'Intern' }}</p>
                            <p class="text-xs text-zinc-500">Bergabung {{ $pi->joined_at?->format('d M Y') }}</p>
                        </div>
                        <form action="{{ route('company.interns.remove', [$group['program']->id, $pi->intern_id]) }}" method="POST" onsubmit="return confirm('Keluarkan peserta ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Keluarkan</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center"><p class="text-zinc-500">Belum ada peserta magang</p></div>
    @endforelse
</div>
@endsection