<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
   // 🟢 List all categories
   public function index()
   {
       $categories = Category::with('children')->orderBy('sort_order')->get();
       return ApiResponse::success($categories, 'Categories fetched successfully');
   }

   // 🟢 Show single category
   public function show(Category $category)
   {
       $category->load('children');
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
           'icon_id'          => 'nullable|integer',
           'image'            => 'nullable|string|max:255',
           'alt_name'         => 'nullable|string|max:255',
           'header_menu'      => 'in:0,1',
           'side_menu'        => 'in:0,1',
           'sort_order'       => 'integer',
           'status'           => 'in:0,1',
       ]);

       $validated['createdBy'] = Auth::user()->id;

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
           'icon_id'          => 'nullable|integer',
           'image'            => 'nullable|string|max:255',
           'alt_name'         => 'nullable|string|max:255',
           'header_menu'      => 'in:0,1',
           'side_menu'        => 'in:0,1',
           'sort_order'       => 'integer',
           'status'           => 'in:0,1',
       ]);

       $validated['updatedBy'] = Auth::user()->id;

       $category->update($validated);

       return ApiResponse::success($category, 'Category updated successfully');
   }

   // 🟢 Delete category
   public function destroy(Category $category)
   {
       $category->delete();
       return ApiResponse::success(null, 'Category deleted successfully');
   }
}
