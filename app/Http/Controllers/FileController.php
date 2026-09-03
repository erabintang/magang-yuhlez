<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\InternshipApplication;
use App\Models\InternProfile;
use App\Models\WorkGallery;
use App\Models\Work;
use App\Models\WorkIntern;
use App\Models\Certificate;
use App\Models\CompanyProfile;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileController extends Controller
{
    protected static array $uploadChunks = [];

    /**
     * JSON flags that ensure safe encoding of ALL data:
     * - JSON_UNESCAPED_UNICODE: don't escape unicode chars
     * - JSON_INVALID_UTF8_SUBSTITUTE: replace invalid UTF-8 bytes with U+FFFD
     * This prevents "Malformed UTF-8 characters" errors from ANY input.
     */
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;

    /**
     * Sanitize a filename to be safe for storage and JSON encoding.
     * Keeps unicode letters, numbers, dots, dashes, underscores.
     * Replaces everything else with underscore.
     */
    private function sanitizeFilename(string $name): string
    {
        // Remove null bytes
        $name = str_replace("\0", '', $name);
        // Ensure valid UTF-8 - replace any invalid bytes
        $name = mb_convert_encoding($name, 'UTF-8', 'UTF-8');
        // Replace dangerous chars, keep unicode letters/numbers
        $name = preg_replace('/[^\p{L}\p{N}. _-]/u', '_', $name);
        // Collapse multiple underscores
        $name = preg_replace('/_+/', '_', $name);
        // Trim underscores and dots from edges
        $name = trim($name, '_. ');
        // Ensure not empty
        $name = $name ?: 'file';
        // Limit length
        return mb_substr($name, 0, 120, 'UTF-8');
    }

    /**
     * Safe JSON response that never fails on bad encoding.
     */
    private function jsonResponse(array $data, int $status = 200): \Illuminate\Http\JsonResponse
    {
        // Ensure all string values are valid UTF-8 before encoding
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
        });
        return response()->json($data, $status, [], self::JSON_FLAGS);
    }

    /**
     * Upload file - supports both single-shot and chunked upload.
     */
    public function upload(Request $request)
    {
        $user = Auth::user();

        // ── Chunked upload: receive one chunk ──
        if ($request->has('chunk_index') && $request->has('total_chunks')) {
            return $this->receiveChunk($request, $user);
        }

        // ── Single-shot upload ──
        $request->validate([
            'file' => 'required|file|max:' . (config('app.max_upload_size_mb', 50) * 1024),
            'bucket' => 'required|string|max:50',
        ]);

        try {
            $file = $request->file('file');
            $bucket = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->bucket);

            $originalName = $file->getClientOriginalName();
            $safeName = $this->sanitizeFilename($originalName);
            $storagePath = $bucket . '/' . Str::random(32) . '/' . $safeName;

            $content = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType() ?: 'application/octet-stream';

            StorageService::upload($bucket, $storagePath, $content, $mimeType);

            $fileRecord = File::create([
                'bucket_name' => $bucket,
                'storage_path' => $storagePath,
                'original_name' => mb_substr($originalName, 0, 255, 'UTF-8'),
                'mime_type' => $mimeType,
                'size_bytes' => $file->getSize(),
                'uploaded_by' => $user->id,
                'created_at' => now(),
            ]);

            return $this->jsonResponse([
                'id' => (string) $fileRecord->id,
                'url' => route('files.download', $fileRecord->id),
                'original_name' => mb_substr($originalName, 0, 255, 'UTF-8'),
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            Log::error('FileController: Upload failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return $this->jsonResponse([
                'message' => 'Gagal upload file. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Receive a single chunk and store it temporarily.
     */
    protected function receiveChunk(Request $request, $user)
    {
        $request->validate([
            'file' => 'required',
            'upload_id' => 'required|string|max:100',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'total_size' => 'required|integer|min:1',
            'original_name' => 'required|string|max:255',
            'mime_type' => 'required|string|max:100',
            'bucket' => 'required|string|max:50',
        ]);

        try {
            // Sanitize upload_id to prevent path traversal
            $uploadId = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->upload_id);
            if (empty($uploadId) || strlen($uploadId) > 100) {
                return $this->jsonResponse(['message' => 'Upload ID tidak valid'], 400);
            }

            $chunkDir = storage_path("app/uploads/{$uploadId}");
            if (!is_dir($chunkDir)) {
                mkdir($chunkDir, 0755, true);
            }

            $chunkFile = $request->file('file');
            $chunkPath = $chunkDir . '/chunk_' . str_pad($request->chunk_index, 5, '0', STR_PAD_LEFT);

            // Sanitize original_name for metadata
            $originalName = mb_substr($request->original_name, 0, 255, 'UTF-8');

            // Store chunk metadata for assembly
            $metaPath = $chunkDir . '/meta.json';
            $meta = file_exists($metaPath) ? json_decode(file_get_contents($metaPath), true) : [];
            if (!is_array($meta)) $meta = [];

            $meta['chunks'][$request->chunk_index] = true;
            $meta['total_chunks'] = (int) $request->total_chunks;
            $meta['original_name'] = $originalName;
            $meta['mime_type'] = mb_substr($request->mime_type, 0, 100, 'UTF-8');
            $meta['total_size'] = (int) $request->total_size;
            $meta['bucket'] = mb_substr($request->bucket, 0, 50, 'UTF-8');
            $meta['uploaded_by'] = $user->id;

            // Write metadata - use JSON_INVALID_UTF8_SUBSTITUTE to prevent encoding errors
            file_put_contents($metaPath, json_encode($meta, self::JSON_FLAGS));

            move_uploaded_file($chunkFile->getRealPath(), $chunkPath);

            return $this->jsonResponse([
                'chunk' => (int) $request->chunk_index,
                'received' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('FileController: Chunk receive failed', [
                'upload_id' => $request->upload_id,
                'error' => $e->getMessage(),
            ]);
            return $this->jsonResponse([
                'message' => 'Gagal menerima chunk. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Assemble all chunks into one file, upload to local storage, save DB record.
     */
    public function uploadComplete(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string|max:100',
        ]);

        try {
            // Sanitize upload_id
            $uploadId = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->upload_id);
            if (empty($uploadId) || strlen($uploadId) > 100) {
                return $this->jsonResponse(['message' => 'Upload ID tidak valid'], 400);
            }

            $chunkDir = storage_path("app/uploads/{$uploadId}");
            $metaPath = $chunkDir . '/meta.json';

            if (!file_exists($metaPath)) {
                return $this->jsonResponse(['message' => 'Upload tidak valid'], 404);
            }

            $meta = json_decode(file_get_contents($metaPath), true);
            if (!is_array($meta)) {
                return $this->jsonResponse(['message' => 'Metadata corrupt'], 400);
            }

            // Verify all chunks present
            $received = count($meta['chunks'] ?? []);
            $total = $meta['total_chunks'] ?? 0;
            if ($received < $total) {
                return $this->jsonResponse([
                    'message' => "Chunk tidak lengkap: {$received}/{$total}",
                ], 400);
            }

            // Sanitize filename
            $originalName = $meta['original_name'] ?? 'file';
            $safeName = $this->sanitizeFilename($originalName);

            // Assemble chunks into temp file
            $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
            $fp = fopen($tempFile, 'wb');

            for ($i = 0; $i < $total; $i++) {
                $chunkPath = $chunkDir . '/chunk_' . str_pad($i, 5, '0', STR_PAD_LEFT);
                if (!file_exists($chunkPath)) {
                    fclose($fp);
                    @unlink($tempFile);
                    return $this->jsonResponse([
                        'message' => "Chunk #{$i} hilang",
                    ], 400);
                }
                fwrite($fp, file_get_contents($chunkPath));
            }
            fclose($fp);

            // Upload assembled file to local storage
            $bucket = preg_replace('/[^a-zA-Z0-9_-]/', '', $meta['bucket'] ?? 'yuhlez');
            $storagePath = $bucket . '/' . Str::random(32) . '/' . $safeName;
            $mimeType = $meta['mime_type'] ?? 'application/octet-stream';

            StorageService::upload(
                $bucket,
                $storagePath,
                file_get_contents($tempFile),
                $mimeType
            );

            // Save DB record
            $fileRecord = File::create([
                'bucket_name' => $bucket,
                'storage_path' => $storagePath,
                'original_name' => mb_substr($originalName, 0, 255, 'UTF-8'),
                'mime_type' => $mimeType,
                'size_bytes' => (int) ($meta['total_size'] ?? 0),
                'uploaded_by' => $meta['uploaded_by'] ?? Auth::id(),
                'created_at' => now(),
            ]);

            // Cleanup
            $this->cleanupChunks($chunkDir);
            @unlink($tempFile);

            return $this->jsonResponse([
                'id' => (string) $fileRecord->id,
                'url' => route('files.download', $fileRecord->id),
                'original_name' => mb_substr($originalName, 0, 255, 'UTF-8'),
                'mime_type' => $mimeType,
                'size' => (int) ($meta['total_size'] ?? 0),
            ]);
        } catch (\Exception $e) {
            Log::error('FileController: Upload complete failed', [
                'upload_id' => $request->upload_id,
                'error' => $e->getMessage(),
            ]);
            return $this->jsonResponse([
                'message' => 'Gagal menyelesaikan upload. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Remove temp chunk directory
     */
    protected function cleanupChunks(string $chunkDir): void
    {
        if (!is_dir($chunkDir)) return;
        $files = glob($chunkDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) @unlink($file);
        }
        @rmdir($chunkDir);
    }

    /**
     * Download file - redirect ke signed URL.
     */
    public function download(string $id)
    {
        try {
            $file = File::findOrFail($id);
            $user = Auth::user();

            if (!$this->canAccessFile($file, $user)) {
                abort(403, 'Anda tidak memiliki akses ke file ini.');
            }

            $signedUrl = StorageService::createSignedUrl(
                $file->bucket_name,
                $file->storage_path,
                3600
            );

            if (request()->expectsJson() || request()->ajax()) {
                return $this->jsonResponse(['url' => $signedUrl]);
            }

            return redirect($signedUrl);
        } catch (\Exception $e) {
            Log::error('FileController: Download failed', [
                'file_id' => $id,
                'error' => $e->getMessage(),
            ]);
            abort(404, 'File tidak ditemukan.');
        }
    }

    /**
     * Check if user can access the file
     */
    protected function canAccessFile(File $file, $user): bool
    {
        if (!$user) return false;

        // ROOT can access everything
        if ($user->role === 'ROOT') return true;

        // Uploader can access their own files
        if ($file->uploaded_by === $user->id) return true;

        // Company can access: banner, gallery, logo, CV of applicants, intern submission files
        if ($user->role === 'COMPANY') {
            $company = $user->companyProfile;
            if (!$company) return false;

            if (\App\Models\ProgramBanner::where('file_id', $file->id)
                ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->exists()) {
                return true;
            }

            if ($company->logo_file_id === $file->id) return true;

            $galleryWorkIds = WorkGallery::where('file_id', $file->id)
                ->whereNull('deleted_at')
                ->pluck('work_id');
            if ($galleryWorkIds->isNotEmpty()) {
                if (Work::where('company_id', $company->id)
                    ->whereIn('id', $galleryWorkIds)
                    ->whereNull('deleted_at')
                    ->exists()) {
                    return true;
                }
            }

            $internIds = InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->whereNull('deleted_at')
                ->pluck('intern_id');
            if ($internIds->isNotEmpty()) {
                if (InternProfile::whereIn('id', $internIds)
                    ->where('cv_file_id', $file->id)
                    ->whereNull('deleted_at')
                    ->exists()) {
                    return true;
                }
            }

            // Company can access files from intern submissions for their works
            $submissionFileIds = \App\Models\WorkSubmissionFile::where('file_id', $file->id)
                ->pluck('submission_id');
            if ($submissionFileIds->isNotEmpty()) {
                if (\App\Models\WorkSubmission::whereIn('id', $submissionFileIds)
                    ->whereHas('work', fn($q) => $q->where('company_id', $company->id))
                    ->exists()) {
                    return true;
                }
            }

            // Company can access certificate files they issued
            if (Certificate::where('file_id', $file->id)
                ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->exists()) {
                return true;
            }

            return false;
        }

        // Intern can access: their own files, their certificates, gallery of works they participate in, their submission files
        if ($user->role === 'INTERN') {
            $intern = $user->internProfile;
            if (!$intern) return false;

            if (Certificate::where('intern_id', $intern->id)
                ->where('file_id', $file->id)
                ->whereNull('deleted_at')
                ->exists()) {
                return true;
            }

            $galleryIds = WorkGallery::where('file_id', $file->id)
                ->whereNull('deleted_at')
                ->pluck('id');
            if ($galleryIds->isNotEmpty()) {
                if (WorkIntern::where('intern_id', $intern->id)
                    ->whereNull('removed_at')
                    ->whereNull('deleted_at')
                    ->whereIn('work_id', function ($q) use ($galleryIds) {
                        $q->select('work_id')
                            ->from('work_gallery')
                            ->whereIn('id', $galleryIds);
                    })
                    ->exists()) {
                    return true;
                }
            }

            // Intern can access their own submission files
            $submissionIds = \App\Models\WorkSubmission::where('intern_id', $intern->id)
                ->pluck('id');
            if ($submissionIds->isNotEmpty()) {
                if (\App\Models\WorkSubmissionFile::where('file_id', $file->id)
                    ->whereIn('submission_id', $submissionIds)
                    ->exists()) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}
