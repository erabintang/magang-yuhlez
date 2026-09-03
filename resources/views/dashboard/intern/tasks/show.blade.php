@extends('layouts.dashboard')

@section('title', 'Detail Tugas - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <a href="{{ route('intern.tasks.index') }}" class="text-sm text-yellow-600 hover:underline">← Kembali ke Daftar Tugas</a>
        <h2 class="mt-2 text-2xl font-bold text-zinc-900">{{ $task->title }}</h2>
        <p class="mt-1 text-sm text-zinc-500">{{ $task->company->name ?? '-' }} - {{ $task->program->title ?? '-' }}</p>
    </div>

    {{-- Task Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Informasi Tugas</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-zinc-500">Status</p>
                    @php $myStatus = $task->interns->first()?->status ?? 'PENDING'; @endphp
                    @if($myStatus === 'COMPLETED')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">✅ Selesai</span>
                    @elseif($myStatus === 'IN_PROGRESS')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">🔄 Dikerjakan</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">⏳ Menunggu Diterima</span>
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
                    @php
                        $isNearDeadline = false;
                        $daysUntilDeadline = null;
                        if ($task->deadline) {
                            $daysUntilDeadline = now()->diffInDays($task->deadline, false);
                            $isNearDeadline = $daysUntilDeadline <= 7;
                        }
                    @endphp
                    <p class="font-medium {{ ($task->isOverdue() || $isNearDeadline) && $myStatus !== 'COMPLETED' ? 'text-red-600' : '' }}">
                        {{ $task->deadline->format('d M Y H:i') }}
                        @if($myStatus !== 'COMPLETED' && $task->deadline)
                            @if($isNearDeadline && !$task->isOverdue())
                                <span class="text-xs text-orange-600 font-normal ml-2">({{ max(0, $daysUntilDeadline) }} hari lagi)</span>
                            @elseif($task->isOverdue())
                                <span class="text-xs text-red-600 font-normal ml-2">(Terlambat)</span>
                            @endif
                        @endif
                    </p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-zinc-500">Wajib Dikerjakan</p>
                    <p class="font-medium">{{ $task->is_mandatory ? 'Ya' : 'Tidak' }}</p>
                </div>
            </div>
        </div>

        @if($myStatus === 'COMPLETED' && $task->interns->first()?->completed_at)
        <div class="bg-green-50 rounded-xl shadow-sm p-6 border border-green-200">
            <h3 class="font-semibold text-green-900 mb-2">✅ Tugas Selesai</h3>
            <p class="text-sm text-green-700">Diselesaikan pada: {{ $task->interns->first()->completed_at->format('d M Y H:i') }}</p>
            @if($task->interns->first()?->note)
                <p class="mt-2 text-sm text-green-800"><strong>Catatan Anda:</strong> {{ $task->interns->first()->note }}</p>
            @endif
        </div>
        @endif
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

    {{-- Action Buttons --}}
    @if($task->status === 'ACTIVE')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-zinc-900 mb-4">Aksi</h3>
            <div class="flex flex-wrap gap-3">
                {{-- PENDING: Show accept button --}}
                @if($myStatus === 'PENDING')
                    <form action="{{ route('intern.tasks.accept', $task->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Anda yakin ingin menerima dan mulai mengerjakan tugas ini?')"
                            class="rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">
                            ✅ Ya, Saya Setujui dan Mulai Mengerjakan
                        </button>
                    </form>
                @endif

                {{-- IN_PROGRESS: Show complete button only near deadline --}}
                @if($myStatus === 'IN_PROGRESS')
                    @php
                        $canComplete = false;
                        if (!$task->deadline) {
                            $canComplete = true;
                        } elseif ($daysUntilDeadline <= 7) {
                            $canComplete = true;
                        }
                    @endphp

                    @if($canComplete)
                        <form action="{{ route('intern.tasks.complete', $task->id) }}" method="POST" id="completeForm" class="flex-1">
                            @csrf
                            <div class="flex gap-3 items-end">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-zinc-700 mb-1">Catatan (opsional)</label>
                                    <input type="text" name="note" placeholder="Catatan penyelesaian tugas..."
                                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                                </div>
                                <button type="submit" onclick="return confirm('Tandai tugas sebagai selesai?')"
                                    class="shrink-0 rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white hover:bg-green-600 transition-colors">
                                    ✅ Selesai
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="rounded-xl bg-zinc-100 px-6 py-3 text-sm text-zinc-600">
                            ⏳ Tombol "Selesai" akan muncul saat deadline mendekati (1 minggu sebelum deadline).
                            <br><span class="text-xs text-zinc-400">Deadline: {{ $task->deadline->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
