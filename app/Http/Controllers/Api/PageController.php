<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::latest();

        // Apply search if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('page_title', 'like', "%{$search}%")
                    ->orWhere('short_des', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%")
                    ->orWhere('meta_keyword', 'like', "%{$search}%");
            });
        }
        if ($request->filled('all')) {
            $pages = $query->select(['id', 'page_title'])->get(); // you can set per_page here
        } else {

            $pages = $query->paginate(10); // you can set per_page here
        }

        return ApiResponse::success($pages, 'Pages retrieved successfully');
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return ApiResponse::success($page, 'Page retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug|max:300',
            'short_des' => 'required|string|max:255',
            'page_description' => 'required|string',
            'temp' => 'nullable|string|max:255',
            'f_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'page_type' => ['required', Rule::in(['page', 'post', 'video', 'analyses'])],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'createdBy' => 'nullable|integer',
            'updatedBy' => 'nullable|integer',
        ]);
        $validated['createdBy'] = Auth::user()->id;
        $validated['updatedBy'] = Auth::user()->id;
        if ($request->hasFile('f_image')) {
            $validated['f_image'] = $request->file('f_image')->store('pages', 'public');
        }

        $page = Page::create($validated);
        return response()->json(['message' => 'Page created successfully', 'data' => $page], 201);
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:300', Rule::unique('pages', 'slug')->ignore($page->id)],
            'short_des' => 'required|string|max:255',
            'page_description' => 'required|string',
            'temp' => 'nullable|string|max:255',
            'f_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'page_type' => ['required', Rule::in(['page', 'post', 'video', 'analyses'])],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
        ]);

        if ($request->hasFile('f_image')) {
            if ($page->f_image && Storage::disk('public')->exists($page->f_image)) {
                Storage::disk('public')->delete($page->f_image);
            }
            $validated['f_image'] = $request->file('f_image')->store('pages', 'public');
        }

        $page->update($validated);
        return response()->json(['message' => 'Page updated successfully', 'data' => $page], 200);
    }

    public function toggleStatus($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->status = $page->status === 'Active' ? 'Inactive' : 'Active';
        $page->save();

        return response()->json([
            'message' => $page->status === 'Active' ? 'Page activated' : 'Page deactivated',
            'status' => $page->status
        ]);
    }

    public function destroy($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        if ($page->f_image && Storage::disk('public')->exists($page->f_image)) {
            Storage::disk('public')->delete($page->f_image);
        }

        $page->delete();
        return ApiResponse::success($page, 'Page deleted successfully');
    }
}
