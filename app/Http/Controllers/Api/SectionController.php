<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return ApiResponse::success($sections, 'Sections retrieved successfully');
    }
    // Show section data
    public function show($name)
    {
        $section = Section::where('name', $name)->firstOrFail();
        return ApiResponse::success($section->data, 'Section data retrieved successfully');
    }

    // Update section data (replace full JSON)
    public function update(Request $request, $name)
    {
        $section = Section::firstOrCreate(['name' => $name]);

        // Validate incoming JSON as associative array
        $data = $request->validate([
            'data' => 'required|array',
        ]);

        $section->data = $data['data'];
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
