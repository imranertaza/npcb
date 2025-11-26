<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // 🟢 List all categories
    public function index(Request $request)
    {
        $all = $request->query('all', false);
        $search = $request->query('search');

        $query = Category::with(['children', 'parent'])->orderBy('sort_order');

        // Apply search if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($all) {
            $categories = $query->get();
        } else {
            $perPage = $request->query('per_page', 10); // default 10
            $categories = $query->paginate($perPage)->onEachSide(1);
        }

        return ApiResponse::success($categories, 'Categories fetched successfully');
    }

    // 🟢 Show single category
    public function show(Category $category)
    {
        $category->load(['children.parent', 'parent']);
        return ApiResponse::success($category, 'Category fetched successfully');
    }

    // 🟢 Create category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id'        => 'nullable|exists:categories,id',
            'category_name'    => 'required|string|max:255',
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword'     => 'nullable|string|max:255',
            'image'            => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'alt_name'         => 'nullable|string|max:255',
            'sort_order'       => 'integer',
            'status'           => 'in:0,1',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $validated['createdBy'] = Auth::id();

        $category = Category::create($validated);

        return ApiResponse::success($category, 'Category created successfully');
    }

    // 🟢 Update category
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'parent_id'        => 'nullable|exists:categories,id',
            'category_name'    => 'required|string|max:255',
            'description'      => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword'     => 'nullable|string|max:255',
            'image'            => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'alt_name'         => 'nullable|string|max:255',
            'sort_order'       => 'integer',
            'status'           => 'in:0,1',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            // Store new image
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        } else {
            // Keep existing image if no new file uploaded
            $validated['image'] = $category->image;
        }

        $validated['updatedBy'] = Auth::id();

        $category->update($validated);

        return ApiResponse::success($category, 'Category updated successfully');
    }

       public function updateStatus(Request $request, Category $category)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);
        $category->status = (string)$request->input('status');
        $category->save();

        return ApiResponse::success($category, 'Category status updated successfully');
    }

    // 🟢 Delete category
    public function destroy(Category $category)
    {
        // Delete image file if it exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return ApiResponse::success(null, 'Category deleted successfully');
    }
}
