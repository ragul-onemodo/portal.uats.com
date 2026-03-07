<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;

class FileUploadService
{
    protected string $disk = 'edgehub';

    protected string $directory;
    protected string|int $deviceId;

    public function __construct(string $directory, string|int $deviceId)
    {
        $this->directory = trim($directory, '/');
        $this->deviceId = $deviceId;
    }

    /**
     * Upload a single image file
     *
     * @return string Stored file path
     */
    public function uploadImage(
        UploadedFile $file,
        ?string $filename = null
    ): string {
        $filename ??= $this->generateFilename($file);

        $path = $this->storagePath() . '/' . $filename;

        try {
            Storage::disk($this->disk)->writeStream(
                $path,
                fopen($file->getRealPath(), 'r'),
                [
                    'mimetype' => $file->getMimeType(),
                    'visibility' => 'private',
                ]
            );
        } catch (FilesystemException $e) {
            throw new \RuntimeException(
                'File upload failed: ' . $e->getMessage(),
                previous: $e
            );
        }

        return $path;
    }

    /**
     * Upload multiple images and return stored paths
     */
    public function uploadImages(array $files): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file);
            }
        }

        return $paths;
    }

    /**
     * Generate a safe, unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Base storage path for this service instance
     */
    protected function storagePath(): string
    {
        return $this->directory . '/' . $this->deviceId;
    }
}
