<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the galleries.
     */
    public function index(Request $request)
    {
        $query = Gallery::withCount('details')->orderBy('sort_order');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('alt_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('all')) {
            $galleries = $query->select(['id', 'name'])->get();
        } else {
            $perPage = $request->input('per_page', 10);
            $galleries = $query->paginate($perPage);
        }

        return ApiResponse::success($galleries, 'Galleries retrieved successfully');
    }

    /**
     * Store a newly created gallery in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'thumb'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'sort_order' => 'nullable|integer',
            'alt_name'   => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('thumb')) {
            $path = $request->file('thumb')->store('gallery/thumbs', 'public');
            $validated['thumb'] = $path;
        }
        $gallery = Gallery::create($validated);

        return ApiResponse::success($gallery, 'Gallery created successfully');
    }

    /**
     * Display the specified gallery along with its details.
     */
    public function show(Gallery $gallery)
    {
        $gallery->load('details');
        return ApiResponse::success($gallery, 'Gallery retrieved successfully');
    }

    /**
     * Update gallery details.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'thumb'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'alt_name'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('thumb')) {
            if ($gallery->thumb && Storage::disk('public')->exists($gallery->thumb)) {
                Storage::disk('public')->delete($gallery->thumb);
            }

            $path = $request->file('thumb')->store('gallery/thumbs', 'public');
            $validated['thumb'] = $path;
        }

        $gallery->update($validated);

        return ApiResponse::success($gallery, 'Gallery updated successfully');
    }

    /**
     * Delete a gallery and its associated details.
     */
    public function destroy(Gallery $gallery)
    {
        foreach ($gallery->details as $detail) {
            if ($detail->image && Storage::disk('public')->exists($detail->image)) {
                Storage::disk('public')->delete($detail->image);
            }
            $detail->delete();
        }

        if ($gallery->thumb && Storage::disk('public')->exists($gallery->thumb)) {
            Storage::disk('public')->delete($gallery->thumb);
        }

        $gallery->delete();

        return ApiResponse::success(null, 'Gallery and its details deleted successfully');
    }

    /**
     * Store a new gallery detail image.
     */
    public function storeDetail(Request $request)
    {

        $request->validate([
            'gallery_id' => 'required|exists:gallery,id',
            'image' => 'required|image|max:2048',
            'alt_name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('gallery/details', 'public');

        $detail = GalleryDetail::create([
            'gallery_id' => $request->gallery_id,
            'image' => $path,
            'alt_name' => $request->alt_name,
            'sort_order' => $request->sort_order ?? 0,
            'createdBy' => Auth::user()->id,
        ]);

        return ApiResponse::success($detail, 'Gallery image added successfully');
    }

    /**
     * Delete a gallery detail image.
     */
    public function destroyGalleryDetail(GalleryDetail $detail)
    {
        if ($detail->image && Storage::disk('public')->exists($detail->image)) {
            Storage::disk('public')->delete($detail->image);
        }

        $detail->delete();

        return ApiResponse::success(null, 'Gallery detail deleted successfully');
    }

    /**
     * Update the alt text and sort order of a gallery detail.
     */
    public function updateDetail(Request $request, GalleryDetail $detail)
    {
        $detail->update([
            'alt_name' => $request->input('alt_name'),
            'sort_order' => $request->input('sort_order', $detail->sort_order),
        ]);
        return ApiResponse::success($detail, 'Alt text updated successfully');
    }
}
