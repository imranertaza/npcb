<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * API Controller for managing news articles.
 *
 * Handles CRUD operations, status toggling, and listing of news with optional search and pagination.
 */
class NewsController extends Controller
{
    /**
     * Retrieve a paginated list of news articles with optional search.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * Retrieve a single news article by its slug along with related categories and parent.
     *
     * @param string $slug The unique slug of the news article
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function show($id)
    {
        $news = News::findOrFail($id)->with('categories.parent')->firstOrFail();
        return ApiResponse::success($news, 'News retrieved successfully');
    }

    /**
     * Store a new news article.
     *
     * Validates input, handles file uploads for main image/video and featured image,
     * assigns creator/updater, and syncs categories.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_title'       => 'required|string|max:255',
            'slug'             => 'required|string|unique:news,slug',
            'short_des'        => 'required|string|max:255',
            'description'      => 'required|string',
            'meta_title'       => 'nullable|string',
            'meta_keyword'     => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image'            => 'required|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov,wmv|max:500000',
            'f_image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'         => 'nullable|string|max:255',
            'publish_date'     => 'nullable|date',
            'status'           => ['required', Rule::in(['0', '1'])],
            'categories'       => 'required|array',
            'categories.*'     => 'exists:news_categories,id',
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();
        $validated['publish_date'] = $request->input('publish_date', now());

        // Create news first (without images)
        $news = News::create($validated);

        // Handle main image
        if ($request->hasFile('image')) {
            $filename = uniqid('news_image_') . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')
                ->storeAs("news/{$news->id}/images/image", $filename, 'public');
            $news->update(['image' => $path]);
        }

        // Handle featured image
        if ($request->hasFile('f_image')) {
            $filename = uniqid('news_f_image_') . '.' . $request->file('f_image')->getClientOriginalExtension();
            $path = $request->file('f_image')
                ->storeAs("news/{$news->id}/images/f-image", $filename, 'public');
            $news->update(['f_image' => $path]);
        }

        if (isset($validated['categories'])) {
            $news->categories()->sync($validated['categories']);
        }

        return ApiResponse::success($news, 'News created successfully');
    }


    /**
     * Update an existing news article.
     *
     * Handles validation, file replacement (deletes old files if new ones are uploaded),
     * updates updater ID, and syncs categories.
     *
     * @param Request $request
     * @param int $id The ID of the news article to update
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $news = News::findOrFail($id);

            $validated = $request->validate([
                'news_title'       => 'required|string|max:255',
                'slug'             => [
                    'required',
                    'string',
                    Rule::unique('news', 'slug')->ignore($news->id),
                ],
                'short_des'        => 'required|string|max:255',
                'description'      => 'required|string',
                'meta_title'       => 'nullable|string',
                'meta_keyword'     => 'nullable|string',
                'meta_description' => 'nullable|string',
                'image'            => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov,wmv|max:500000',
                'f_image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'alt_name'         => 'nullable|string|max:255',
                'status'           => ['required', Rule::in(['0', '1'])],
                'categories'       => 'required|array',
                'categories.*'     => 'exists:news_categories,id',
            ]);

            $validated['updatedBy'] = Auth::id();

            // Handle main image/video replacement
            if ($request->hasFile('image')) {
                if ($news->image && Storage::disk('public')->exists($news->image)) {
                    Storage::disk('public')->delete($news->image);
                }

                $filename = uniqid('news_image_') . '.' . $request->file('image')->getClientOriginalExtension();
                $validated['image'] = $request->file('image')
                    ->storeAs("news/{$news->id}/images/image", $filename, 'public');
            }

            // Handle featured image replacement
            if ($request->hasFile('f_image')) {
                if ($news->f_image && Storage::disk('public')->exists($news->f_image)) {
                    Storage::disk('public')->delete($news->f_image);
                }

                $filename = uniqid('news_f_image_') . '.' . $request->file('f_image')->getClientOriginalExtension();
                $validated['f_image'] = $request->file('f_image')
                    ->storeAs("news/{$news->id}/images/f-image", $filename, 'public');
            }

            $news->update($validated);

            if (isset($validated['categories'])) {
                $news->categories()->sync($validated['categories']);
            }

            return ApiResponse::success($news, 'News updated successfully');
        } catch (\Exception $e) {
            Log::error('News update failed: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => Auth::id(),
            ]);

            return ApiResponse::error('Failed to update news. Please try again later.', 500);
        }
    }


    /**
     * Toggle the publication status (active/inactive) of a news article.
     *
     * @param string $slug The slug of the news article
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function toggleStatus($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $news->status = $news->status == 1 ? 0 : 1;
        $news->updatedBy = Auth::id();
        $news->save();

        return ApiResponse::success([
            'status' => $news->status,
        ], $news->status == 1 ? 'News active' : 'News inactive');
    }

    /**
     * Permanently delete a news article along with its associated media files.
     *
     * @param string $slug The slug of the news article to delete
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }
        if ($news->f_image && Storage::disk('public')->exists($news->f_image)) {
            Storage::disk('public')->delete($news->f_image);
        }

        $news->delete();

        return ApiResponse::success(null, 'News deleted successfully');
    }
}
