<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    /**
     * List news with optional search + pagination
     */
    public function index(Request $request)
    {
        $query = News::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('news_title', 'like', "%{$search}%")
                    ->orWhere('short_des', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%")
                    ->orWhere('meta_keyword', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $news = $query->paginate($perPage);

        return ApiResponse::success($news, 'News retrieved successfully');
    }

    /**
     * Show single news by slug
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->with('categories.parent')->firstOrFail();
        return ApiResponse::success($news, 'News retrieved successfully');
    }

    /**
     * Store new news
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_title'     => 'required|string|max:255',
            'slug'           => 'required|string|unique:news,slug',
            'short_des'      => 'required|string|max:255',
            'description'    => 'required|string',
            'meta_title'     => 'nullable|string',
            'meta_keyword'   => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image'          => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'       => 'nullable|string|max:255',
            'publish_date'   => 'nullable|date',
            'status'         => ['required', Rule::in(['0', '1'])],
            'categories' => 'required|array',
            'categories.*' => 'exists:news_categories,id',
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();
        $validated['publish_date'] = $request->input('publish_date', now());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $news = News::create($validated);

        if (isset($validated['categories'])) {
            $news->categories()->sync($validated['categories']);
        }

        return ApiResponse::success($news, 'News created successfully');
    }

    /**
     * Update existing news
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $validated = $request->validate([
            'news_title'     => 'required|string|max:255',
            'slug'           => [
                'required',
                'string',
                Rule::unique('news', 'slug')->ignore($news->id),
            ],
            'short_des'      => 'required|string|max:255',
            'description'    => 'required|string',
            'meta_title'     => 'nullable|string',
            'meta_keyword'   => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'       => 'nullable|string|max:255',
            'status'         => ['required', Rule::in(['0', '1'])],
            'categories' => 'required|array',
            'categories.*' => 'exists:news_categories,id',
        ]);

        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($validated);

        if (isset($validated['categories'])) {
            $news->categories()->sync($validated['categories']);
        }

        return ApiResponse::success($news, 'News updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $news->status = $news->status === '1' ? '0' : '1';
        $news->updatedBy = Auth::id();
        $news->save();

        return ApiResponse::success([
            'status' => $news->status,
        ], $news->status === '1' ? 'News active' : 'News inactive');
    }

    /**
     * Delete news
     */
    public function destroy($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return ApiResponse::success(null, 'News deleted successfully');
    }
}
