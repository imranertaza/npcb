<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::paginate();
        return ApiResponse::success($sections, 'Sections retrieved successfully');
    }
    // Show section data
    public function show($id)
    {
        $section = Section::findOrFail($id);
        return ApiResponse::success($section, 'Section data retrieved successfully');
    }

    // Update section data (replace full JSON)
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        // dd($request->all());
        // Validate incoming JSON as associative array
        $data = $request->validate([
            'data' => 'required|json',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = json_decode($request->input('data'), true);

        if ($request->hasFile('image')) {
            // remove old image if exists
            if (!empty($section->data['image']) && Storage::disk('public')->exists($section->data['image'])) {
                Storage::disk('public')->delete($section->data['image']);
            }

            $data['image'] = $request->file('image')->store('web/about', 'public');
        }

        $section->data = $data;
        $section->save();

        return response()->json([
            'message' => 'Section updated successfully',
            'section' => $section,
        ]);
    }

    // Update a single key/value inside section
    public function updateKey(Request $request, $name)
    {
        $section = Section::firstOrCreate(['name' => $name]);

        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
        ]);

        $section->setValue($validated['key'], $validated['value']);

        return response()->json([
            'message' => 'Key updated successfully',
            'section' => $section,
        ]);
    }
}
