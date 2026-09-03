@extends('layouts.dashboard')

@section('title', 'Detail Tugas - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <a href="{{ route('company.tasks.index') }}" class="text-sm text-yellow-600 hover:underline">← Kembali ke Daftar Tugas</a>
        <div class="flex items-start justify-between gap-4 mt-1">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900">{{ $task->title }}</h2>
                <p class="mt-1 text-sm text-zinc-500">Program: {{ $task->program->title ?? '-' }}</p>
            </div>
            <div class="flex gap-2 shrink-0">
                @if($task->status === 'ACTIVE')
                    <form action="{{ route('company.tasks.toggle', $task->id) }}" method="POST" onsubmit="return confirm('Tutup tugas ini?')">
                        @csrf
                        <button type="submit" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Tutup Tugas</button>
                    </form>
                @else
                    <form action="{{ route('company.tasks.toggle', $task->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-xl bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600">Buka Kembali</button>
                    </form>
                @endif
                <form action="{{ route('company.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini? Semua data terkait akan dihapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Task Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Informasi Tugas</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-500">Status</p>
                    @if($task->status === 'ACTIVE')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Aktif</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">Ditutup</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Prioritas</p>
                    @if($task->priority === 'URGENT')
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Mendesak</span>
                    @elseif($task->priority === 'HIGH')
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded">Tinggi</span>
                    @elseif($task->priority === 'NORMAL')
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Normal</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded">Rendah</span>
                    @endif
                </div>
                @if($task->deadline)
                <div>
                    <p class="text-xs text-zinc-500">Deadline</p>
                    <p class="font-medium {{ $task->isOverdue() ? 'text-red-600' : '' }}">{{ $task->deadline->format('d M Y H:i') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-zinc-500">Wajib</p>
                    <p class="font-medium">{{ $task->is_mandatory ? 'Ya' : 'Tidak' }}</p>
                </div>
                <div>
                    <p class="text-xs text-zinc-500">Dibuat</p>
                    <p class="font-medium">{{ $task->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Progress</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-zinc-600">Selesai</span>
                        <span class="font-medium text-zinc-900">{{ $task->getCompletionPercentage() }}%</span>
                    </div>
                    <div class="w-full bg-zinc-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all" style="width: {{ $task->getCompletionPercentage() }}%"></div>
                    </div>
                </div>
                @php
                    $completed = $task->interns->where('status', 'COMPLETED')->count();
                    $inProgress = $task->interns->where('status', 'IN_PROGRESS')->count();
                    $pending = $task->interns->where('status', 'PENDING')->count();
                    $total = $task->interns->count();
                @endphp
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-lg bg-blue-50 p-2">
                        <p class="text-lg font-bold text-blue-700">{{ $pending }}</p>
                        <p class="text-xs text-blue-600">Menunggu</p>
                    </div>
                    <div class="rounded-lg bg-yellow-50 p-2">
                        <p class="text-lg font-bold text-yellow-700">{{ $inProgress }}</p>
                        <p class="text-xs text-yellow-600">Dikerjakan</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-2">
                        <p class="text-lg font-bold text-green-700">{{ $completed }}</p>
                        <p class="text-xs text-green-600">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($task->description || $task->instructions)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-zinc-900 mb-2">Deskripsi</h3>
        <p class="text-sm text-zinc-600 whitespace-pre-line">{{ $task->description }}</p>
        @if($task->instructions)
            <h3 class="font-semibold text-zinc-900 mt-4 mb-2">Instruksi</h3>
            <p class="text-sm text-zinc-600 whitespace-pre-line">{{ $task->instructions }}</p>
        @endif
    </div>
    @endif

    {{-- Intern List --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-zinc-900 mb-4">Daftar Intern ({{ $task->interns->count() }})</h3>
        <div class="space-y-3">
            @forelse($task->interns as $ti)
                <div class="flex items-center justify-between rounded-xl border border-zinc-200 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-zinc-900">{{ $ti->intern->name ?? 'Intern' }}</p>
                        <p class="text-xs text-zinc-400">{{ $ti->intern->contact_email ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($ti->status === 'COMPLETED')
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">✅ Selesai</span>
                            @if($ti->completed_at)
                                <span class="text-xs text-zinc-400">{{ $ti->completed_at->format('d M Y H:i') }}</span>
                            @endif
                        @elseif($ti->status === 'IN_PROGRESS')
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded">🔄 Dikerjakan</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">⏳ Menunggu Diterima</span>
                        @endif
                    </div>
                </div>
                @if($ti->note)
                    <div class="ml-4 rounded-lg bg-zinc-50 border border-zinc-200 px-4 py-2">
                        <p class="text-xs text-zinc-500">Catatan intern:</p>
                        <p class="text-sm text-zinc-700">{{ $ti->note }}</p>
                    </div>
                @endif
            @empty
                <p class="text-sm text-zinc-400 italic">Tidak ada intern yang ditugaskan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
