<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function bannerSliders(Request $request, $key)
    {
        $slides = Slider::where('key', $key)->paginate();
        return ApiResponse::success($slides, 'success');
    }
    public function show($id)
    {
        $slide = Slider::findOrFail($id);
        return ApiResponse::success($slide, 'success');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|string',
            'order'       => 'nullable|integer',
            'enabled'     => 'nullable|numeric|in:0,1',
            'image'       => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('web/banner', 'public');
        }

        $data['key'] = 'banner_section';

        $slide = Slider::create($data);

        return response()->json(['message' => 'Slide created successfully', 'slide' => $slide]);
    }

    // Update existing slide
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|string',
            'order'       => 'nullable|integer',
            'enabled'     => 'boolean',
            'image'       => 'nullable|file|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('web/banner', 'public');
        }

        $slider->update($data);

        return response()->json(['message' => 'Slide updated successfully', 'slide' => $slider]);
    }

    public function toggle($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->enabled = $slider->enabled ? 0 : 1;
        $slider->save();

        return ApiResponse::success($slider, 'Slide toggled successfully');
    }

    // Delete slide
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->image && Storage::disk('public')->exists($slider->image) ? Storage::disk('public')->delete($slider->image) : null;
        $slider->delete();
        return ApiResponse::success($slider, 'Slide deleted successfully');
    }
}
