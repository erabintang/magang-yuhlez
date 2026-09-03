<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageService
{
    /**
     * Upload file to local storage
     */
    public static function upload(string $bucket, string $path, $content, string $contentType): void
    {
        try {
            $disk = Storage::disk('public');
            
            // Ensure the directory exists
            $directory = dirname($path);
            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }

            // Ensure content is valid
            if (!is_string($content)) {
                $content = (string) $content;
            }

            $disk->put($path, $content);

            Log::info('StorageService: File uploaded successfully', [
                'bucket' => $bucket,
                'path' => $path,
                'content_type' => $contentType,
                'size' => strlen($content),
            ]);
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to upload file', [
                'bucket' => $bucket,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal upload file ke storage.');
        }
    }

    /**
     * Create signed URL for file download (for local storage, just return the URL)
     */
    public static function createSignedUrl(string $bucket, string $path, int $expiresIn = 3600): string
    {
        try {
            $disk = Storage::disk('public');
            
            if (!$disk->exists($path)) {
                throw new \Exception('File tidak ditemukan.');
            }

            // For local storage, return the URL directly
            return $disk->url($path);
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to create signed URL', [
                'bucket' => $bucket,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal membuat URL untuk file.');
        }
    }

    /**
     * Delete file from local storage
     */
    public static function delete(string $bucket, string $path): void
    {
        try {
            $disk = Storage::disk('public');
            
            if ($disk->exists($path)) {
                $disk->delete($path);
                Log::info('StorageService: File deleted successfully', [
                    'bucket' => $bucket,
                    'path' => $path,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('StorageService: Failed to delete file', [
                'bucket' => $bucket,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if file exists
     */
    public static function exists(string $bucket, string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get file size
     */
    public static function size(string $bucket, string $path): int
    {
        return Storage::disk('public')->size($path);
    }
}
