<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NoticeController extends Controller
{
    /**
     * List notices with optional search + pagination
     */
    public function index(Request $request)
    {
        $query = Notice::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $notices = $query->paginate($perPage);

        return ApiResponse::success($notices, 'Notices retrieved successfully');
    }

    /**
     * Show single notice by slug
     */
    public function show($slug)
    {
        $notice = Notice::where('slug', $slug)->firstOrFail();
        return ApiResponse::success($notice, 'Notice retrieved successfully');
    }

    /**
     * Store new notice
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:notices,slug',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:4096',
            'status'      => ['required', Rule::in(['0', '1'])],
            'type'        => ['required', Rule::in([0, 1])],
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('notices', 'public');
        }

        $notice = Notice::create($validated);

        return ApiResponse::success($notice, 'Notice created successfully');
    }

    /**
     * Update existing notice
     */
    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => [
                'required',
                'string',
                Rule::unique('notices', 'slug')->ignore($notice->id),
            ],
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:4096',
            'status'      => ['required', Rule::in(['0', '1'])],
            'type'        => ['required', Rule::in([0, 1])],
        ]);

        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            if ($notice->file && Storage::disk('public')->exists($notice->file)) {
                Storage::disk('public')->delete($notice->file);
            }
            $validated['file'] = $request->file('file')->store('notices', 'public');
        }

        $notice->update($validated);

        return ApiResponse::success($notice, 'Notice updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($slug)
    {
        $notice = Notice::where('slug', $slug)->firstOrFail();
        $notice->status = $notice->status === '1' ? '0' : '1';
        $notice->updatedBy = Auth::id();
        $notice->save();

        return ApiResponse::success([
            'status' => $notice->status,
        ], $notice->status === '1' ? 'Notice active' : 'Notice inactive');
    }

    /**
     * Delete notice
     */
    public function destroy($slug)
    {
        $notice = Notice::where('slug', $slug)->firstOrFail();

        if ($notice->file && Storage::disk('public')->exists($notice->file)) {
            Storage::disk('public')->delete($notice->file);
        }

        $notice->delete();

        return ApiResponse::success(null, 'Notice deleted successfully');
    }
}
