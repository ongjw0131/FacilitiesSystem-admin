<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
    /**
     * Serve a file for download or preview
     */
    public function download($fileID)
    {
        try {
            $file = File::findOrFail($fileID);
            $filePath = storage_path('app/public/' . $file->filePath);

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'File not found: ' . $filePath], 404);
            }

            return response()->download($filePath, $file->originalName ?? basename($filePath));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Get file content for preview (text files)
     */
    public function preview($fileID)
    {
        try {
            $file = File::findOrFail($fileID);
            $filePath = storage_path('app/public/' . $file->filePath);

            if (!file_exists($filePath)) {
                return response('File not found', 404);
            }

            $extension = strtolower(pathinfo($file->originalName ?? $file->filePath, PATHINFO_EXTENSION));

            // Only allow text file previews
            if (!in_array($extension, ['txt', 'csv', 'json', 'xml', 'log', 'md'])) {
                return response('File type not previewable', 400);
            }

            $content = file_get_contents($filePath);
            
            // Return as plain text with proper header
            return response($content, 200, [
                'Content-Type' => 'text/plain; charset=utf-8',
                'Content-Disposition' => 'inline'
            ]);
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Serve file for inline viewing (PDF, images)
     */
    public function view($fileID)
    {
        try {
            $file = File::findOrFail($fileID);
            $filePath = storage_path('app/public/' . $file->filePath);

            if (!file_exists($filePath)) {
                return response('File not found', 404);
            }

            $extension = strtolower(pathinfo($file->originalName ?? $file->filePath, PATHINFO_EXTENSION));
            
            // Determine MIME type
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'txt' => 'text/plain',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'csv' => 'text/csv',
                'json' => 'application/json',
                'xml' => 'application/xml'
            ];

            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . ($file->originalName ?? basename($filePath)) . '"'
            ]);
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}
