<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventCategoryController extends Controller
{
    /*  List categories with optional search and pagination */
    public function index(Request $request)
    {
        $all = $request->query('all', false);
        $search = $request->query('search');

        $query = EventCategory::with(['children', 'parent'])->orderBy('sort_order');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($all) {
            $categories = $query->select(['id', 'category_name'])->get();
        } else {
            $perPage = $request->query('per_page', 10);
            if ($perPage > 0) {
                $categories = $query->paginate($perPage)->onEachSide(1);
            } else {
                $categories = $query->select(['id', 'category_name'])->where('parent_id', null)->get();
            }
        }

        return ApiResponse::success($categories, 'Categories fetched successfully');
    }

    /*  Get single category details */
    public function show(EventCategory $category)
    {
        $category->load(['children.parent', 'parent']);
        return ApiResponse::success($category, 'Category fetched successfully');
    }

    /*  Create new category */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id'        => 'nullable|exists:event_categories,id',
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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('event_categories', 'public');
            $validated['image'] = $path;
        }

        $validated['createdBy'] = Auth::id();

        $category = EventCategory::create($validated);

        return ApiResponse::success($category, 'Category created successfully');
    }

    /*  Update existing category */
    public function update(Request $request, EventCategory $category)
    {

        $validated = $request->validate([
            'parent_id'        => 'nullable|exists:event_categories,id',
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

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $path = $request->file('image')->store('event_categories', 'public');
            $validated['image'] = $path;
        } else {

            $validated['image'] = $category->image;
        }

        $validated['updatedBy'] = Auth::id();

        $category->update($validated);

        return ApiResponse::success($category, 'Category updated successfully');
    }

    /*  Update category status */
    public function updateStatus(Request $request, EventCategory $category)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);
        $category->status = (string)$request->input('status');
        $category->save();

        return ApiResponse::success($category, 'Category status updated successfully');
    }

    /*  Delete category */
    public function destroy(EventCategory $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return ApiResponse::success(null, 'Category deleted successfully');
    }
}
