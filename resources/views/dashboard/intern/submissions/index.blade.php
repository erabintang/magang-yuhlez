@extends('layouts.dashboard')

@section('title', 'Karya Saya - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.intern._sidebar')
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Karya Saya</h1>
        <p class="mt-1 text-sm text-zinc-500">Daftar karya yang telah Anda kirim ke perusahaan.</p>
    </div>
    @if($worksCount > 0)
        <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" 
            class="shrink-0 rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors">
            + Upload Karya
        </button>
    @endif
</div>

@if($submissions->count() > 0)
    <div class="space-y-4">
        @foreach($submissions as $submission)
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-zinc-900">{{ $submission->title }}</h3>
                            @if($submission->status === 'PENDING')
                                <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Menunggu Review</span>
                            @elseif($submission->status === 'ACCEPTED')
                                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Diterima</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Ditolak</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">Karya: {{ $submission->work->title }} - {{ $submission->work->company->name ?? '-' }}</p>
                        @if($submission->description)
                            <p class="mt-1 text-sm text-zinc-600 line-clamp-2">{{ $submission->description }}</p>
                        @endif
                        @if($submission->tech_stack)
                            <p class="mt-1 text-xs text-zinc-400">Tech: {{ $submission->tech_stack }}</p>
                        @endif
                        <p class="mt-2 text-xs text-zinc-400">{{ $submission->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <a href="{{ route('intern.submissions.show', $submission->id) }}" class="shrink-0 rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Detail</a>
                </div>
                @if($submission->review_note && $submission->status === 'REJECTED')
                    <div class="mt-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2">
                        <p class="text-sm text-red-700"><strong>Catatan:</strong> {{ $submission->review_note }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $submissions->links() }}</div>
@else
    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center">
        <p class="text-zinc-500">Belum ada karya yang dikirim.</p>
        @if($worksCount > 0)
            <p class="mt-1 text-sm text-zinc-400">Klik tombol "Upload Karya" untuk mengirim karya pertama Anda.</p>
        @else
            <p class="mt-1 text-sm text-zinc-400">Anda belum terdaftar di karya manapun. Hubungi perusahaan untuk bergabung.</p>
        @endif
    </div>
@endif

{{-- Upload Modal --}}
@if($worksCount > 0)
<div id="uploadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-4">
        <div class="sticky top-0 bg-white border-b border-zinc-200 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-zinc-900">Upload Karya Baru</h2>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600 text-xl">&times;</button>
        </div>

        <form method="POST" id="uploadForm" class="p-6 space-y-5">
            @csrf

            {{-- Select Work --}}
            <div>
                <label for="work_slug" class="block text-sm font-medium text-zinc-700 mb-1">Pilih Karya *</label>
                <select name="work_slug" id="work_slug" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none">
                    <option value="">-- Pilih Karya --</option>
                    @foreach($works as $work)
                        <option value="{{ $work->slug }}">{{ $work->title }} - {{ $work->company->name ?? '-' }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div>
                <label for="modal_title" class="block text-sm font-medium text-zinc-700 mb-1">Judul Pengiriman *</label>
                <input type="text" name="title" id="modal_title" required
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none"
                    placeholder="Contoh: Website Company Profile - Bagian Frontend">
            </div>

            {{-- Description --}}
            <div>
                <label for="modal_description" class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
                <textarea name="description" id="modal_description" rows="3"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none"
                    placeholder="Jelaskan apa yang Anda kerjakan..."></textarea>
            </div>

            {{-- Tech Stack --}}
            <div>
                <label for="modal_tech_stack" class="block text-sm font-medium text-zinc-700 mb-1">Tech Stack</label>
                <input type="text" name="tech_stack" id="modal_tech_stack"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none"
                    placeholder="Contoh: Laravel, Vue.js, Tailwind CSS">
            </div>

            {{-- File Upload --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">File Projek (ZIP/RAR/7Z) *</label>
                <input type="file" id="modalFileInput" accept=".zip,.rar,.7z,.tar.gz"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 outline-none file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-400 file:px-4 file:py-1 file:text-sm file:font-semibold file:text-zinc-950 hover:file:bg-yellow-500">

                {{-- Progress Bar --}}
                <div id="modalProgressContainer" class="hidden mt-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-zinc-700" id="modalProgressText">Mengupload...</span>
                        <span class="text-sm text-zinc-500" id="modalProgressPercent">0%</span>
                    </div>
                    <div class="w-full bg-zinc-200 rounded-full h-2.5">
                        <div id="modalProgressBar" class="bg-yellow-400 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                {{-- Uploaded File Info --}}
                <div id="modalUploadedFileInfo" class="hidden mt-3 rounded-xl border border-green-200 bg-green-50 p-3">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-green-900 truncate" id="modalUploadedFileName">-</p>
                            <p class="text-xs text-green-600" id="modalUploadedFileSize">-</p>
                        </div>
                        <span class="text-green-500 text-lg">✓</span>
                    </div>
                </div>

                <input type="hidden" name="file_ids" id="modalFileIds" value="[]">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-50">
                    Batal
                </button>
                <button type="submit" id="modalSubmitBtn"
                    class="flex-1 rounded-xl bg-yellow-400 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Kirim Karya
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('modalFileInput');
    const progressContainer = document.getElementById('modalProgressContainer');
    const progressBar = document.getElementById('modalProgressBar');
    const progressText = document.getElementById('modalProgressText');
    const progressPercent = document.getElementById('modalProgressPercent');
    const uploadedFileInfo = document.getElementById('modalUploadedFileInfo');
    const uploadedFileName = document.getElementById('modalUploadedFileName');
    const uploadedFileSize = document.getElementById('modalUploadedFileSize');
    const fileIdsInput = document.getElementById('modalFileIds');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const uploadForm = document.getElementById('uploadForm');

    let uploadedFileData = [];

    if (!fileInput) return;

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
        uploadedFileInfo.classList.add('hidden');
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

            uploadedFileData.push(result.id);
            fileIdsInput.value = JSON.stringify(uploadedFileData);

            uploadedFileName.textContent = result.original_name || file.name;
            uploadedFileSize.textContent = (result.size / 1024 / 1024).toFixed(2) + ' MB';
            uploadedFileInfo.classList.remove('hidden');

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

    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const workSlug = document.getElementById('work_slug').value;
        const title = document.getElementById('modal_title').value;
        const fileIds = JSON.parse(fileIdsInput.value || '[]');

        if (!workSlug) {
            alert('Pilih karya terlebih dahulu.');
            return;
        }
        if (!title) {
            alert('Masukkan judul pengiriman.');
            return;
        }
        if (fileIds.length === 0) {
            alert('Upload file terlebih dahulu.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';

        // Submit via fetch
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('title', title);
        formData.append('description', document.getElementById('modal_description').value);
        formData.append('tech_stack', document.getElementById('modal_tech_stack').value);
        fileIds.forEach(id => formData.append('file_ids[]', id));

        fetch(`/dashboard/intern/works/${workSlug}/submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        }).then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else if (response.ok) {
                window.location.reload();
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Gagal mengirim karya');
                });
            }
        }).catch(err => {
            alert('Gagal mengirim karya: ' + err.message);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Karya';
        });
    });
});
</script>
@endsection
