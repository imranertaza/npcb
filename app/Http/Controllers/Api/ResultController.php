<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ResultController extends Controller
{
    /**
     * List results with optional search + pagination
     */
    public function index(Request $request)
    {
        $query = Result::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $results = $query->paginate($perPage);

        return ApiResponse::success($results, 'Results retrieved successfully');
    }

    /**
     * Show single result by slug
     */
    public function show($slug)
    {
        $result = Result::where('slug', $slug)->firstOrFail();
        return ApiResponse::success($result, 'Result retrieved successfully');
    }

    /**
     * Store new result
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:results,slug',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:4096',
            'status'      => ['required', Rule::in(['0', '1'])],
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('results', 'public');
        }

        $result = Result::create($validated);

        return ApiResponse::success($result, 'Result created successfully');
    }

    /**
     * Update existing result
     */
    public function update(Request $request, $id)
    {
        $result = Result::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => [
                'required',
                'string',
                Rule::unique('results', 'slug')->ignore($result->id),
            ],
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:4096',
            'status'      => ['required', Rule::in(['0', '1'])],
        ]);

        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            if ($result->file && Storage::disk('public')->exists($result->file)) {
                Storage::disk('public')->delete($result->file);
            }
            $validated['file'] = $request->file('file')->store('results', 'public');
        }

        $result->update($validated);

        return ApiResponse::success($result, 'Result updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($slug)
    {
        $result = Result::where('slug', $slug)->firstOrFail();
        $result->status = $result->status === '1' ? '0' : '1';
        $result->updatedBy = Auth::id();
        $result->save();

        return ApiResponse::success([
            'status' => $result->status,
        ], $result->status === '1' ? 'Result active' : 'Result inactive');
    }

    /**
     * Delete result
     */
    public function destroy($slug)
    {
        $result = Result::where('slug', $slug)->firstOrFail();

        if ($result->file && Storage::disk('public')->exists($result->file)) {
            Storage::disk('public')->delete($result->file);
        }

        $result->delete();

        return ApiResponse::success(null, 'Result deleted successfully');
    }
}
