<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Event Image Service
 * 
 * Handles all image-related operations for events
 */
class EventImageService
{
    /**
     * Upload event image and create necessary copies
     */
    public function uploadEventImage(UploadedFile $file): string
    {
        // Store in storage/app/public/events
        $path = $file->store('events', 'public');
        $basename = basename($path);
        $storageFile = storage_path('app/public/' . $path);

        // Copy to public/events directory
        $this->copyToPublicEvents($storageFile, $basename);

        // Copy to public/storage/events directory
        $this->copyToPublicStorage($storageFile, $basename);

        return Storage::url($path);
    }

    /**
     * Copy image to public/events directory
     */
    protected function copyToPublicEvents(string $sourceFile, string $basename): void
    {
        $publicDir = public_path('events');
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }

        $publicFile = $publicDir . '/' . $basename;
        if (file_exists($sourceFile)) {
            @copy($sourceFile, $publicFile);
        }
    }

    /**
     * Copy image to public/storage/events directory
     */
    protected function copyToPublicStorage(string $sourceFile, string $basename): void
    {
        $publicStorageDir = public_path('storage/events');
        if (!is_dir($publicStorageDir)) {
            @mkdir($publicStorageDir, 0755, true);
        }

        $publicStorageFile = $publicStorageDir . '/' . $basename;
        if (file_exists($sourceFile)) {
            @copy($sourceFile, $publicStorageFile);
        }
    }

    /**
     * Delete event image and all its copies
     */
    public function deleteEventImage(string $imageUrlPath): bool
    {
        $basename = basename($imageUrlPath);
        
        // Delete from storage
        $storagePath = str_replace('/storage/', '', $imageUrlPath);
        Storage::disk('public')->delete($storagePath);

        // Delete from public/events
        $publicFile = public_path('events/' . $basename);
        if (file_exists($publicFile)) {
            @unlink($publicFile);
        }

        // Delete from public/storage/events
        $publicStorageFile = public_path('storage/events/' . $basename);
        if (file_exists($publicStorageFile)) {
            @unlink($publicStorageFile);
        }

        return true;
    }
}