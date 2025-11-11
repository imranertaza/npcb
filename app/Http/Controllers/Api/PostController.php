<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest();
        // Apply search if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('post_title', 'like', "%{$search}%")
                    ->orWhere('short_des', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%")
                    ->orWhere('meta_keyword', 'like', "%{$search}%");
            });
        }

        // Allow per_page override (default 10)
        $perPage = $request->input('per_page', 10);
        $posts = $query->paginate($perPage);

        return ApiResponse::success($posts, 'Posts retrieved successfully');
    }
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return ApiResponse::success($post, 'Post retrieved successfully');
    }
    // Store a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:155',
            'slug' => 'required|string|unique:posts,slug',
            'short_des' => 'required|string|max:155',
            'description' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name' => 'nullable|string|max:255',
            'video_id' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'status' => ['required', Rule::in(['0', '1'])],
        ]);
        $validated['createdBy'] = Auth::user()->id;
        $validated['updatedBy'] = Auth::user()->id;
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($validated);
        return response()->json(['message' => 'Post created successfully', 'data' => $post], 201);
    }
    // Update an existing post
    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'post_title' => 'required|string|max:155',
            'slug' => [
                'required',
                'string',
                Rule::unique('posts', 'slug')->ignore($post->id),
            ],
            'short_des' => 'required|string|max:155',
            'description' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name' => 'nullable|string|max:255',
            'video_id' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'status' => ['required', Rule::in(['0', '1'])],
        ]);
        $validated['createdBy'] = Auth::user()->id;
        $validated['updatedBy'] = Auth::user()->id;
        // Handle image replacement
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            // Store new image
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully', 'data' => $post], 200);
    }

    public function toggleStatus($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->status = $post->status === '1' ? '0' : '1';
        $post->save();

        return response()->json([
            'message' => $post->status === '1' ? 'Post published' : 'Post unpublished',
            'status' => $post->status
        ]);
    }

    // Delete a post
    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Delete image from storage if it exists
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return ApiResponse::success($post, 'Post deleted successfully');
    }
}
