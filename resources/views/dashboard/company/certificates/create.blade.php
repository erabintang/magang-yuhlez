@extends('layouts.dashboard')

@section('title', 'Terbitkan Sertifikat - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('company.certificates.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Daftar Sertifikat</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Terbitkan Sertifikat</h1>
    <p class="mt-1 text-sm text-zinc-500">Upload sertifikat untuk peserta program magang Anda. File yang di-upload akan terlihat oleh semua intern yang terdaftar di program ini.</p>
</div>

<form method="POST" action="{{ route('company.certificates.store') }}" id="certificateForm">
    @csrf

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Program Selection --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Pilih Program</h2>
                <div class="mt-4">
                    <label for="program_id" class="block text-sm font-medium text-zinc-700 mb-1">Program Magang *</label>
                    <select name="program_id" id="program_id" required
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('program_id') border-red-500 @enderror"
                        onchange="updateInternsList()">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}
                                data-interns='@json($program->programInterns->map(fn($pi) => ['id' => $pi->intern->id, 'name' => $pi->intern->name ?? "Intern {$pi->intern_id}"]))'>
                                {{ $program->title }} ({{ $program->programInterns->count() }} peserta)
                            </option>
                        @endforeach
                    </select>
                    @error('program_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Intern Selection --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Pilih Penerima Sertifikat</h2>
                <p class="mt-1 text-sm text-zinc-500">Pilih intern yang akan menerima sertifikat ini. Sertifikat yang di-upload akan terlihat oleh semua intern yang dipilih.</p>
                <div id="internsContainer" class="mt-4">
                    <p class="text-sm text-zinc-400 italic">Pilih program terlebih dahulu untuk melihat daftar intern.</p>
                </div>
                @error('intern_ids') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- File Upload --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Upload File Sertifikat</h2>
                <p class="mt-1 text-sm text-zinc-500">Upload file PDF sertifikat. File akan di-upload secara bertahap untuk ukuran besar.</p>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-zinc-700 mb-2">File Sertifikat (PDF) *</label>
                    <input type="file" id="certFileInput" accept=".pdf"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-400 file:px-4 file:py-1 file:text-sm file:font-semibold file:text-zinc-950 hover:file:bg-yellow-500">

                    {{-- Progress Bar --}}
                    <div id="progressContainer" class="hidden mt-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-zinc-700" id="progressText">Mengupload...</span>
                            <span class="text-sm text-zinc-500" id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-zinc-200 rounded-full h-2.5">
                            <div id="progressBar" class="bg-yellow-400 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    {{-- Uploaded File Info --}}
                    <div id="uploadedFileInfo" class="hidden mt-3 rounded-xl border border-green-200 bg-green-50 p-3">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-green-900 truncate" id="uploadedFileName">-</p>
                                <p class="text-xs text-green-600" id="uploadedFileSize">-</p>
                            </div>
                            <span class="text-green-500 text-lg">✓</span>
                        </div>
                    </div>

                    <input type="hidden" name="file_id" id="fileId" value="{{ old('file_id') }}">
                </div>
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
                        <dt class="text-zinc-500">File</dt>
                        <dd class="text-right text-zinc-900" id="summaryFile">Belum diupload</dd>
                    </div>
                </dl>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                🎓 Terbitkan Sertifikat
            </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
let uploadedFileId = null;

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
    const fileInput = document.getElementById('certFileInput');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');
    const uploadedFileInfo = document.getElementById('uploadedFileInfo');
    const uploadedFileName = document.getElementById('uploadedFileName');
    const uploadedFileSize = document.getElementById('uploadedFileSize');
    const fileIdInput = document.getElementById('fileId');
    const submitBtn = document.getElementById('submitBtn');

    fileInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (file.type !== 'application/pdf' && !file.name.endsWith('.pdf')) {
            alert('Hanya file PDF yang diperbolehkan.');
            fileInput.value = '';
            return;
        }

        // Validate size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 10MB.');
            fileInput.value = '';
            return;
        }

        progressContainer.classList.remove('hidden');
        uploadedFileInfo.classList.add('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = 'Mengupload...';
        progressPercent.textContent = '0%';
        fileInput.disabled = true;
        submitBtn.disabled = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const chunkSize = 2 * 1024 * 1024; // 2MB chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const uploadId = 'upload_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            for (let i = 0; i < totalChunks; i++) {
                const start = i * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk, file.name);
                formData.append('upload_id', uploadId);
                formData.append('chunk_index', i);
                formData.append('total_chunks', totalChunks);
                formData.append('total_size', file.size);
                formData.append('original_name', file.name);
                formData.append('mime_type', 'application/pdf');
                formData.append('bucket', 'yuhlez');

                let lastError;
                for (let retry = 0; retry <= 3; retry++) {
                    try {
                        const response = await fetch('/files/upload', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            const errData = await response.json().catch(() => ({}));
                            throw new Error(errData.message || 'Upload gagal');
                        }

                        const pct = Math.round(((i + 1) / totalChunks) * 100);
                        progressBar.style.width = pct + '%';
                        progressPercent.textContent = pct + '%';
                        progressText.textContent = `Chunk ${i + 1}/${totalChunks}`;
                        lastError = null;
                        break;
                    } catch (err) {
                        lastError = err;
                        if (retry < 3) await new Promise(r => setTimeout(r, 1000 * (retry + 1)));
                    }
                }
                if (lastError) throw lastError;
            }

            // Assemble chunks
            progressText.textContent = 'Menggabungkan file...';
            const assembleResponse = await fetch('/files/upload/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ upload_id: uploadId }),
            });

            if (!assembleResponse.ok) {
                const errData = await assembleResponse.json().catch(() => ({}));
                throw new Error(errData.message || 'Gagal menggabungkan file');
            }

            const result = await assembleResponse.json();
            uploadedFileId = result.id;
            fileIdInput.value = result.id;

            uploadedFileName.textContent = result.original_name || file.name;
            uploadedFileSize.textContent = (result.size / 1024 / 1024).toFixed(2) + ' MB';
            uploadedFileInfo.classList.remove('hidden');

            document.getElementById('summaryFile').textContent = result.original_name || file.name;

            progressText.textContent = 'Upload selesai!';
            progressPercent.textContent = '100%';
            progressBar.style.width = '100%';
            setTimeout(() => progressContainer.classList.add('hidden'), 2000);

        } catch (err) {
            alert('Gagal upload file: ' + err.message);
            progressContainer.classList.add('hidden');
        }

        fileInput.disabled = false;
        submitBtn.disabled = false;
    });

    // Form validation before submit
    document.getElementById('certificateForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.intern-checkbox:checked').length;
        if (checked === 0) {
            e.preventDefault();
            alert('Pilih minimal satu intern penerima sertifikat.');
            return;
        }
        if (!uploadedFileId) {
            e.preventDefault();
            alert('Upload file sertifikat terlebih dahulu.');
            return;
        }
    });
});
</script>
@endsection
