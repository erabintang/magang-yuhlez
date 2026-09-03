@extends('layouts.dashboard')

@section('title', 'Tugas - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Tugas</h1>
        <p class="mt-1 text-sm text-zinc-500">Buat dan kelola tugas untuk intern Anda.</p>
    </div>
    <a href="{{ route('company.tasks.create') }}" class="shrink-0 rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">
        + Buat Tugas
    </a>
</div>

{{-- Filter --}}
<div class="flex gap-2 mb-4">
    <a href="{{ route('company.tasks.index') }}" class="px-3 py-1.5 text-sm rounded-lg {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Semua</a>
    <a href="{{ route('company.tasks.index', ['status' => 'ACTIVE']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'ACTIVE' ? 'bg-green-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Aktif</a>
    <a href="{{ route('company.tasks.index', ['status' => 'CLOSED']) }}" class="px-3 py-1.5 text-sm rounded-lg {{ request('status') === 'CLOSED' ? 'bg-zinc-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Ditutup</a>
</div>

<div class="space-y-3">
    @forelse($tasks as $task)
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-semibold text-zinc-900">{{ $task->title }}</h3>
                        @if($task->priority === 'URGENT')
                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Mendesak</span>
                        @elseif($task->priority === 'HIGH')
                            <span class="px-2 py-0.5 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Tinggi</span>
                        @elseif($task->priority === 'NORMAL')
                            <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Normal</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-600 rounded-full">Rendah</span>
                        @endif
                        @if($task->status === 'ACTIVE')
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500 rounded-full">Ditutup</span>
                        @endif
                        @if($task->is_mandatory)
                            <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Wajib</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-zinc-500">Program: {{ $task->program->title ?? '-' }}</p>
                    @if($task->description)
                        <p class="mt-1 text-sm text-zinc-600 line-clamp-2">{{ $task->description }}</p>
                    @endif
                    <div class="mt-2 flex items-center gap-4 text-xs text-zinc-400">
                        @if($task->deadline)
                            <span class="{{ $task->isOverdue() ? 'text-red-500 font-medium' : '' }}">
                                📅 Deadline: {{ $task->deadline->format('d M Y H:i') }}
                            </span>
                        @endif
                        <span>{{ $task->interns->count() }} intern ditugaskan</span>
                        <span>{{ $task->getCompletionPercentage() }}% selesai</span>
                    </div>
                    {{-- Progress Bar --}}
                    <div class="mt-2 w-full max-w-xs bg-zinc-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $task->getCompletionPercentage() }}%"></div>
                    </div>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('company.tasks.show', $task->id) }}" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
            <p class="text-zinc-500">Belum ada tugas</p>
            <p class="mt-1 text-sm text-zinc-400">Klik "Buat Tugas" untuk membuat tugas baru untuk intern Anda.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $tasks->links() }}</div>
@endsection
