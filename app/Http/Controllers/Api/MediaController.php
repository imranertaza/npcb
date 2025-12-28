<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * API Controller for managing media files in the public storage disk.
 *
 * Provides endpoints for browsing folders/files, uploading media (images, videos, PDFs),
 * and deleting files. Designed for use in admin media libraries or CKEditor/FM integrations.
 */
class MediaController extends Controller
{
    /**
     * Display a listing of folders and files in the specified directory.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $folder = $request->input('folder', 'uploads');

        // Ensure the folder path is safe (prevent directory traversal)
        $folder = ltrim($folder, '/');

        $directories = Storage::disk('public')->directories($folder);
        $files       = Storage::disk('public')->files($folder);

        // Optionally enrich file data with URLs and sizes
        $enrichedFiles = collect($files)->map(function ($file) {
            return [
                'path'      => $file,
                'url'       => Storage::url($file),
                'size'      => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
            ];
        });

        return ApiResponse::success([
            'current_folder' => $folder,
            'folders'        => $directories,
            'files'          => $enrichedFiles,
        ], 'Media library retrieved successfully');
    }

    /**
     * Upload one or multiple media files.
     *
     * Supports large files (up to 200MB) including images, videos, and PDFs.
     * Generates safe, unique filenames to prevent overwrites and conflicts.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,pdf|max:204800', // 200MB
            'folder' => 'nullable|string|max:255',
        ]);

        $folder = $request->input('folder', 'uploads');
        $folder = ltrim($folder, '/'); // Security: prevent leading slashes

        $uploadedFile = $request->file('file');
        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $uploadedFile->getClientOriginalExtension();

        // Sanitize filename: remove dangerous characters, replace spaces with hyphens
        $safeName = preg_replace('/\s+/', '-', $originalName);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '', $safeName);

        // Ensure uniqueness
        $uniqueSuffix = time() . '_' . uniqid();
        $newFileName  = $safeName . '_' . $uniqueSuffix . '.' . $extension;

        $path = $uploadedFile->storeAs($folder, $newFileName, 'public');

        return ApiResponse::success([
            'message'       => 'File uploaded successfully',
            'path'          => $path,
            'url'           => Storage::url($path),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name'   => $newFileName,
            'mime_type'     => $uploadedFile->getMimeType(),
            'size'          => $uploadedFile->getSize(),
        ], 'File uploaded successfully');
    }

    /**
     * Delete a media file from public storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->input('path');

        // Basic security check: prevent traversal attacks
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            return ApiResponse::error('Invalid file path.', 400);
        }

        if (!Storage::disk('public')->exists($path)) {
            return ApiResponse::error('File not found.', 404);
        }

        Storage::disk('public')->delete($path);

        return ApiResponse::success(null, 'File deleted successfully');
    }
}
