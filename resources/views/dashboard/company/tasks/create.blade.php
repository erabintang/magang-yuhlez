@extends('layouts.dashboard')

@section('title', 'Buat Tugas - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('company.tasks.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Daftar Tugas</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Buat Tugas Baru</h1>
    <p class="mt-1 text-sm text-zinc-500">Buat tugas untuk intern di program magang Anda.</p>
</div>

<form method="POST" action="{{ route('company.tasks.store') }}" id="taskForm">
    @csrf

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Program Selection --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Program Magang</h2>
                <div class="mt-4">
                    <label for="program_id" class="block text-sm font-medium text-zinc-700 mb-1">Pilih Program *</label>
                    <select name="program_id" id="program_id" required
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('program_id') border-red-500 @enderror"
                        onchange="updateInternsList()">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}
                                data-interns='@json($program->programInterns->map(fn($pi) => ["id" => $pi->intern->id, "name" => $pi->intern->name ?? "Intern {$pi->intern_id}"]))'>
                                {{ $program->title }} ({{ $program->programInterns->count() }} peserta)
                            </option>
                        @endforeach
                    </select>
                    @error('program_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Task Details --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Detail Tugas</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-zinc-700 mb-1">Judul Tugas *</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Buat Landing Page untuk Projek X">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('description') border-red-500 @enderror"
                            placeholder="Ringkasan singkat tentang tugas ini...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-zinc-700 mb-1">Instruksi Lengkap</label>
                        <textarea name="instructions" id="instructions" rows="6"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('instructions') border-red-500 @enderror"
                            placeholder="Jelaskan detail tugas, langkah-langkah, dan kriteria penyelesaian...">{{ old('instructions') }}</textarea>
                        @error('instructions') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Pengaturan</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-zinc-700 mb-1">Deadline</label>
                        <input type="datetime-local" name="deadline" id="deadline" value="{{ old('deadline') }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('deadline') border-red-500 @enderror">
                        @error('deadline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-zinc-700 mb-1">Prioritas *</label>
                        <select name="priority" id="priority" required
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                            <option value="LOW" {{ old('priority') === 'LOW' ? 'selected' : '' }}>Rendah</option>
                            <option value="NORMAL" {{ old('priority', 'NORMAL') === 'NORMAL' ? 'selected' : '' }}>Normal</option>
                            <option value="HIGH" {{ old('priority') === 'HIGH' ? 'selected' : '' }}>Tinggi</option>
                            <option value="URGENT" {{ old('priority') === 'URGENT' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_mandatory" value="0">
                        <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', '1') === '1' ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400/20">
                        <span class="text-sm font-medium text-zinc-700">Tugas wajib dikerjakan</span>
                    </label>
                </div>
            </div>

            {{-- Intern Selection --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Pilih Intern</h2>
                <p class="mt-1 text-sm text-zinc-500">Pilih intern yang akan mendapat tugas ini. Mereka akan menerima notifikasi.</p>
                <div id="internsContainer" class="mt-4">
                    <p class="text-sm text-zinc-400 italic">Pilih program terlebih dahulu untuk melihat daftar intern.</p>
                </div>
                @error('intern_ids') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="font-semibold text-zinc-900">Ringkasan</h3>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Program</dt>
                        <dd class="text-right text-zinc-900" id="summaryProgram">-</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Jumlah Penerima</dt>
                        <dd class="text-right text-zinc-900" id="summaryInterns">0</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Prioritas</dt>
                        <dd class="text-right text-zinc-900" id="summaryPriority">Normal</dd>
                    </div>
                </dl>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                📋 Buat & Kirim Tugas
            </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
function updateInternsList() {
    const select = document.getElementById('program_id');
    const container = document.getElementById('internsContainer');
    const selectedOption = select.options[select.selectedIndex];

    if (!selectedOption || !selectedOption.value) {
        container.innerHTML = '<p class="text-sm text-zinc-400 italic">Pilih program terlebih dahulu untuk melihat daftar intern.</p>';
        document.getElementById('summaryProgram').textContent = '-';
        document.getElementById('summaryInterns').textContent = '0';
        return;
    }

    const programTitle = selectedOption.textContent.trim().split(' (')[0];
    document.getElementById('summaryProgram').textContent = programTitle;

    try {
        const interns = JSON.parse(selectedOption.dataset.interns || '[]');
        if (interns.length === 0) {
            container.innerHTML = '<p class="text-sm text-zinc-400 italic">Tidak ada intern terdaftar di program ini.</p>';
            document.getElementById('summaryInterns').textContent = '0';
            return;
        }

        container.innerHTML = `
            <div class="flex items-center gap-2 mb-3">
                <button type="button" onclick="toggleAllInterns(true)" class="text-xs text-yellow-600 hover:underline">Pilih Semua</button>
                <span class="text-zinc-300">|</span>
                <button type="button" onclick="toggleAllInterns(false)" class="text-xs text-zinc-500 hover:underline">Batal Pilih Semua</button>
            </div>
            <div class="space-y-2">
                ${interns.map(intern => `
                    <label class="flex items-center gap-3 rounded-xl border border-zinc-200 px-4 py-3 hover:bg-zinc-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="intern_ids[]" value="${intern.id}"
                            class="h-4 w-4 rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400/20 intern-checkbox"
                            onchange="updateInternsCount()">
                        <div>
                            <p class="text-sm font-medium text-zinc-900">${intern.name}</p>
                        </div>
                    </label>
                `).join('')}
            </div>
        `;
    } catch(e) {
        container.innerHTML = '<p class="text-sm text-red-500">Gagal memuat daftar intern.</p>';
    }
}

function toggleAllInterns(check) {
    document.querySelectorAll('.intern-checkbox').forEach(cb => {
        cb.checked = check;
    });
    updateInternsCount();
}

function updateInternsCount() {
    const checked = document.querySelectorAll('.intern-checkbox:checked').length;
    document.getElementById('summaryInterns').textContent = checked;
}

document.addEventListener('DOMContentLoaded', function() {
    // Priority change handler
    document.getElementById('priority').addEventListener('change', function() {
        const labels = { 'LOW': 'Rendah', 'NORMAL': 'Normal', 'HIGH': 'Tinggi', 'URGENT': 'Mendesak' };
        document.getElementById('summaryPriority').textContent = labels[this.value] || this.value;
    });

    // Form validation before submit
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.intern-checkbox:checked').length;
        if (checked === 0) {
            e.preventDefault();
            alert('Pilih minimal satu intern penerima tugas.');
            return;
        }
    });
});
</script>
@endsection
