<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        // Adjusted to match the new ApiResponse::success() signature
        return ApiResponse::success(Setting::all(), 'Settings retrieved successfully');
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'   => 'sometimes|string|max:155',
            'site_email'  => 'sometimes|string|max:155',
            'site_phone'  => 'sometimes|string|max:155',
            'favicon'     => 'sometimes|file|mimes:png,jpg,jpeg,ico|max:2048',
            'logo'        => 'sometimes|file|mimes:png,jpg,jpeg,svg|max:4096',
        ]);

        foreach ($validated as $label => $value) {
            $setting = Setting::where('label', $label)->first();
            if ($setting) {

                if ($request->hasFile($label)) {
                    $file = $request->file($label);

                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $filename = $label . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('settings', $filename, 'public');

                    $setting->update([
                        'value' => $path,
                        'updatedBy' => Auth::id(),
                    ]);
                } else {
                    $setting->update([
                        'value' => $value,
                        'updatedBy' => Auth::id(),
                    ]);
                }
            }
        }

        return ApiResponse::success(null, 'Settings updated successfully');
    }
}
