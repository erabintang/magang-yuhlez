/**
 * Chunked File Upload Service
 * Uploads files in chunks to avoid timeouts and reduce memory usage.
 * Each chunk is sent separately, and the server assembles them.
 */
class ChunkedUploader {
    constructor(options = {}) {
        this.chunkSize = options.chunkSize || 5 * 1024 * 1024; // 5MB default
        this.endpoint = options.endpoint || '/files/upload';
        this.token = options.token || document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.maxRetries = options.maxRetries || 3;
        this.onProgress = options.onProgress || (() => {});
        this.onSuccess = options.onSuccess || (() => {});
        this.onError = options.onError || (() => {});
    }

    async upload(file, extraData = {}) {
        const totalChunks = Math.ceil(file.size / this.chunkSize);
        const uploadId = this._generateId();
        let uploadedChunks = 0;

        try {
            for (let i = 0; i < totalChunks; i++) {
                const start = i * this.chunkSize;
                const end = Math.min(start + this.chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk, file.name);
                formData.append('upload_id', uploadId);
                formData.append('chunk_index', i);
                formData.append('total_chunks', totalChunks);
                formData.append('total_size', file.size);
                formData.append('original_name', file.name);
                formData.append('mime_type', file.type || 'application/octet-stream');

                // Append extra data
                Object.entries(extraData).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                let lastError;
                for (let retry = 0; retry <= this.maxRetries; retry++) {
                    try {
                        const response = await fetch(this.endpoint, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.token,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.message || `HTTP ${response.status}`);
                        }

                        uploadedChunks++;
                        this.onProgress({
                            loaded: end,
                            total: file.size,
                            percent: Math.round((uploadedChunks / totalChunks) * 100),
                            chunk: uploadedChunks,
                            totalChunks,
                        });

                        lastError = null;
                        break;
                    } catch (err) {
                        lastError = err;
                        if (retry < this.maxRetries) {
                            await this._sleep(1000 * (retry + 1)); // Exponential backoff
                        }
                    }
                }

                if (lastError) throw lastError;
            }

            // All chunks uploaded - request assembly
            const assembleResponse = await fetch(this.endpoint + '/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    upload_id: uploadId,
                    original_name: file.name,
                    mime_type: file.type || 'application/octet-stream',
                    total_size: file.size,
                    ...extraData,
                }),
            });

            if (!assembleResponse.ok) {
                const errorData = await assembleResponse.json().catch(() => ({}));
                throw new Error(errorData.message || 'Gagal menggabungkan file');
            }

            const result = await assembleResponse.json();
            this.onSuccess(result);
            return result;
        } catch (err) {
            this.onError(err);
            throw err;
        }
    }

    _generateId() {
        return 'upload_' + Date.now() + '_' + Math.random().toString(36).substring(2, 15);
    }

    _sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

// Auto-initialize all file inputs with data-chunked-upload
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-chunked-upload]').forEach(initChunkedInput);
});

function initChunkedInput(input) {
    const bucket = input.dataset.bucket || 'yuhlez';
    const maxFileSize = parseInt(input.dataset.maxSize) || 50 * 1024 * 1024; // 50MB
    const progressBar = input.dataset.progress;
    const previewEl = input.dataset.preview;
    const hiddenInput = input.dataset.hiddenInput;
    const acceptTypes = input.accept || '';

    input.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        // Validate size
        if (file.size > maxFileSize) {
            alert('File terlalu besar. Maksimal: ' + Math.round(maxFileSize / 1024 / 1024) + 'MB');
            input.value = '';
            return;
        }

        // Validate type
        if (acceptTypes) {
            const accepted = acceptTypes.split(',').map(t => t.trim().toLowerCase());
            const ext = '.' + file.name.split('.').pop().toLowerCase();
            const mime = file.type;
            const matched = accepted.some(a => a === ext || a === mime || mime.startsWith(a.replace('*', '')));
            if (!matched) {
                alert('Tipe file tidak diizinkan. Yang diperbolehkan: ' + acceptTypes);
                input.value = '';
                return;
            }
        }

        // Show progress
        const progressContainer = progressBar ? document.getElementById(progressBar) : null;
        if (progressContainer) {
            progressContainer.classList.remove('hidden');
            const bar = progressContainer.querySelector('.progress-fill');
            const text = progressContainer.querySelector('.progress-text');
            if (bar) bar.style.width = '0%';
            if (text) text.textContent = 'Mengupload...';
        }

        // Disable input during upload
        input.disabled = true;

        try {
            const uploader = new ChunkedUploader({
                endpoint: '/files/upload',
                chunkSize: 2 * 1024 * 1024, // 2MB chunks
                onProgress: (info) => {
                    if (progressContainer) {
                        const bar = progressContainer.querySelector('.progress-fill');
                        const text = progressContainer.querySelector('.progress-text');
                        if (bar) bar.style.width = info.percent + '%';
                        if (text) text.textContent = `Mengupload... ${info.percent}% (${info.chunk}/${info.totalChunks} chunk)`;
                    }
                },
                onSuccess: (result) => {
                    if (hiddenInput) {
                        const target = document.querySelector(hiddenInput);
                        if (target) target.value = result.id;
                    }
                    if (progressContainer) {
                        const text = progressContainer.querySelector('.progress-text');
                        if (text) text.textContent = 'Upload selesai!';
                        setTimeout(() => progressContainer.classList.add('hidden'), 2000);
                    }
                    // Show preview for images
                    if (previewEl && file.type.startsWith('image/')) {
                        const preview = document.querySelector(previewEl);
                        if (preview) {
                            const reader = new FileReader();
                            reader.onload = (ev) => {
                                preview.src = ev.target.result;
                                preview.classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                    input.disabled = false;
                },
                onError: (err) => {
                    alert('Gagal upload file: ' + err.message);
                    if (progressContainer) progressContainer.classList.add('hidden');
                    input.disabled = false;
                    input.value = '';
                },
            });

            await uploader.upload(file, { bucket });
        } catch (err) {
            // Error already handled by onError
        }
    });
}

// Export for use in blade templates
if (typeof window !== 'undefined') {
    window.ChunkedUploader = ChunkedUploader;
    window.initChunkedInput = initChunkedInput;
}
