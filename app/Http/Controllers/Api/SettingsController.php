<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class SettingsController extends Controller
{
    public function index()
    {
        // Adjusted to match the new ApiResponse::success() signature
        return ApiResponse::success(Setting::all(), 'Settings retrieved successfully');
    }

    // PUT /api/settings/{label}
    public function update(Request $request, $label)
    {
        $setting = Setting::where('label', $label)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:155',
            'value' => 'sometimes|string|max:155',
        ]);

        $validated['updatedBy'] = Auth::user()->id;

        $setting->update($validated);

        // Adjusted to match the new ApiResponse::success() signature
        return ApiResponse::success($setting, 'Setting updated successfully');
    }
}
