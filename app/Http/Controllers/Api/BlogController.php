<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    /**
     * List blogs with optional search + pagination
     */
    public function index(Request $request)
    {
        $query = Blog::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_des', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%")
                    ->orWhere('meta_keyword', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $blogs = $query->paginate($perPage);



        return ApiResponse::success($blogs, 'Blogs retrieved successfully');
    }

    /**
     * Show single blog by slug
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->with('categories.parent')->firstOrFail();
        return ApiResponse::success($blog, 'Blog retrieved successfully');
    }

    /**
     * Store new blog
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'           => 'required|string|unique:blogs,slug',
            'short_des'      => 'required|string|max:255',
            'description'    => 'required|string',
            'meta_title'     => 'nullable|string',
            'meta_keyword'   => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image'          => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'f_image'          => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'       => 'nullable|string|max:255',
            'publish_date'   => 'nullable|date',
            'status'         => ['required', Rule::in(['0', '1'])],
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();
        $validated['publish_date'] = $request->input('publish_date', now());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }
        if ($request->hasFile('f_image')) {
            $validated['f_image'] = $request->file('f_image')->store('blogs', 'public');
        }


        $blog = Blog::create($validated);

        if (isset($validated['categories'])) {
            $blog->categories()->sync($validated['categories']);
        }

        return ApiResponse::success($blog, 'Blog created successfully');
    }

    /**
     * Update existing news
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'           => [
                'required',
                'string',
                Rule::unique('blogs', 'slug')->ignore($blog->id),
            ],
            'short_des'      => 'required|string|max:255',
            'description'    => 'required|string',
            'meta_title'     => 'nullable|string',
            'meta_keyword'   => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'f_image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'       => 'nullable|string|max:255',
            'status'         => ['required', Rule::in(['0', '1'])],
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
        ]);

        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }
        if ($request->hasFile('f_image')) {
            if ($blog->f_image && Storage::disk('public')->exists($blog->f_image)) {
                Storage::disk('public')->delete($blog->f_image);
            }
            $validated['f_image'] = $request->file('f_image')->store('blogs', 'public');
        }



        $blog->update($validated);

        if (isset($validated['categories'])) {
            $blog->categories()->sync($validated['categories']);
        }

        return ApiResponse::success($blog, 'Blog updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $blog->status = $blog->status === '1' ? '0' : '1';
        $blog->updatedBy = Auth::id();
        $blog->save();

        return ApiResponse::success([
            'status' => $blog->status,
        ], $blog->status === '1' ? 'Blog active' : 'Blog inactive');
    }

    /**
     * Delete blog
     */
    public function destroy($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->f_image && Storage::disk('public')->exists($blog->f_image)) {
            Storage::disk('public')->delete($blog->f_image);
        }

        $blog->delete();

        return ApiResponse::success(null, 'Blog deleted successfully');
    }
}
