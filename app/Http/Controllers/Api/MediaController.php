<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->input('folder', 'uploads');

        return response()->json([
            'folders' => Storage::disk('public')->directories($folder),
            'files'   => Storage::disk('public')->files($folder),
        ]);
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $parent = $request->input('parent', 'uploads');
        $path   = $parent . '/' . $request->name;

        Storage::disk('public')->makeDirectory($path);

        return response()->json(['message' => 'Folder created', 'path' => $path]);
    }

    public function renameFolder(Request $request)
    {
        $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string'
        ]);

        $oldPath = $request->old_path;
        $newPath = dirname($oldPath) . '/' . $request->new_name;

        Storage::disk('public')->move($oldPath, $newPath);

        return response()->json(['message' => 'Folder renamed', 'path' => $newPath]);
    }

    public function deleteFolder(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        Storage::disk('public')->deleteDirectory($request->path);

        return response()->json(['message' => 'Folder deleted']);
    }
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,pdf|max:204800',
            'folder' => 'nullable|string'
        ]);


        $folder = $request->input('folder', 'uploads');

        $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $request->file('file')->getClientOriginalExtension();

        $safeName = preg_replace('/\s+/', '-', $originalName);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '', $safeName);

        $uniqueSuffix = time();
        $newFileName  = $safeName . '_' . $uniqueSuffix . '.' . $extension;

        $path = $request->file('file')->storeAs($folder, $newFileName, 'public');

        return response()->json([
            'message'       => 'File uploaded successfully',
            'path'          => $path,
            'url'           => Storage::url($path),
            'original_name' => $request->file('file')->getClientOriginalName(),
            'stored_name'   => $newFileName,
            'type'          => $extension
        ]);
    }

    public function deleteFile(Request $request)
    {
        $request->validate(['path' => 'required|string']);
        Storage::disk('public')->delete($request->path);

        return response()->json(['message' => 'File deleted']);
    }
    public function renameFile(Request $request)
    {
        $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string'
        ]);

        $oldPath = $request->old_path;
        $folder  = dirname($oldPath);
        $newPath = $folder . '/' . $request->new_name;

        Storage::disk('public')->move($oldPath, $newPath);

        return response()->json([
            'message' => 'File renamed',
            'old_path' => $oldPath,
            'new_path' => $newPath,
            'url' => Storage::url($newPath)
        ]);
    }
}
