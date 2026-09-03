@extends('layouts.dashboard')

@section('title', 'Kirim Karya - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('intern.works.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Karya</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">Kirim Karya</h1>
    <p class="mt-1 text-sm text-zinc-500">Kirim file projek Anda untuk karya <strong>{{ $work->title }}</strong>.</p>
</div>

<form method="POST" action="{{ route('intern.submissions.store', $work->slug) }}" id="submissionForm">
    @csrf

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main Form --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Detail Karya</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-zinc-700 mb-1">Judul Pengiriman *</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Website Company Profile - Bagian Frontend">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none @error('description') border-red-500 @enderror"
                            placeholder="Jelaskan apa yang Anda kerjakan...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tech_stack" class="block text-sm font-medium text-zinc-700 mb-1">Tech Stack</label>
                        <input type="text" name="tech_stack" id="tech_stack" value="{{ old('tech_stack') }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none"
                            placeholder="Contoh: Laravel, Vue.js, Tailwind CSS, MySQL">
                    </div>
                </div>
            </div>

            {{-- File Upload --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-zinc-900">Upload File</h2>
                <p class="mt-1 text-sm text-zinc-500">Upload file ZIP projek Anda. File akan di-upload secara otomatis per bagian untuk file besar.</p>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-zinc-700 mb-2">File Projek (ZIP) *</label>
                    <input type="file" id="fileInput" accept=".zip,.rar,.7z,.tar.gz"
                        class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">

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

                    {{-- Uploaded Files List --}}
                    <div id="uploadedFiles" class="mt-4 space-y-2"></div>

                    {{-- Hidden input for file IDs --}}
                    <input type="hidden" name="file_ids" id="fileIds" value="{{ old('file_ids', '[]') }}">
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="font-semibold text-zinc-900">Karya</h3>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Judul</dt>
                        <dd class="text-right text-zinc-900">{{ $work->title }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Perusahaan</dt>
                        <dd class="text-right text-zinc-900">{{ $work->company->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Kirim Karya
            </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');
    const uploadedFiles = document.getElementById('uploadedFiles');
    const fileIds = document.getElementById('fileIds');
    const submitBtn = document.getElementById('submitBtn');

    let uploadedFileData = [];
    try { uploadedFileData = JSON.parse(fileIds.value || '[]'); } catch(e) {}

    fileInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        const allowedExts = ['.zip', '.rar', '.7z', '.tar.gz'];
        const ext = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedExts.includes(ext)) {
            alert('Hanya file ZIP, RAR, 7Z, atau TAR.GZ yang diperbolehkan.');
            fileInput.value = '';
            return;
        }

        // Validate size (max 2GB)
        if (file.size > 2 * 1024 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 2GB.');
            fileInput.value = '';
            return;
        }

        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = 'Mengupload...';
        progressPercent.textContent = '0%';
        fileInput.disabled = true;
        submitBtn.disabled = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const chunkSize = 4 * 1024 * 1024; // 4MB chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const uploadId = 'upload_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            // Upload chunks
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
                formData.append('mime_type', file.type || 'application/zip');
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

            // Add to uploaded files list
            uploadedFileData.push(result.id);
            fileIds.value = JSON.stringify(uploadedFileData);

            // Show in UI
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between rounded-xl border border-green-200 bg-green-50 p-3';
            fileItem.innerHTML = `
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-green-900 truncate">${result.original_name || file.name}</p>
                    <p class="text-xs text-green-600">${(result.size / 1024 / 1024).toFixed(2)} MB ✓</p>
                </div>
                <input type="hidden" name="file_ids[]" value="${result.id}">
            `;
            uploadedFiles.appendChild(fileItem);

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
});
</script>
@endsection
