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
        return ApiResponse::success(Setting::all(), 'Settings retrieved successfully');
    }

    
    public function update(Request $request)
    {
        // === 1. VALIDATION ===
        $validated = $request->validate([

            // Store Info
            'address'                => 'sometimes|string|max:255',
            'email'                  => 'sometimes|email|max:155',
            'phone'                  => 'sometimes|string|max:50',
            'state'                  => 'sometimes|string|max:50',
    
            // File Uploads (only if sent)
            'store_logo'             => 'sometimes|file|mimes:png,jpg,jpeg,svg|max:4096',
            'store_icon'             => 'sometimes|file|mimes:png,jpg,jpeg,ico|max:2048',    
            // Mail Settings
            'mail_protocol'          => 'sometimes|in:smtp,mail,sendmail',
            'mail_address'           => 'sometimes|email|max:155',
            'smtp_host'              => 'sometimes|string|max:155',
            'smtp_username'          => 'sometimes|string|max:155',
            'smtp_password'          => 'sometimes|string|max:255',
            'smtp_port'              => 'sometimes|numeric|between:1,65535',
            'smtp_timeout'           => 'sometimes|numeric|between:1,300',
            'smtp_crypto'            => 'sometimes|in:ssl,tls,""',

            // Social
            'fb_url'                 => 'sometimes|url|max:255',
            'twitter_url'            => 'sometimes|url|max:255',
            'tiktok_url'             => 'sometimes|url|max:255',
            'instagram_url'          => 'sometimes|url|max:255',
            // SEO
            'meta_title'             => 'sometimes|string|max:155',
            'meta_keyword'           => 'sometimes|string|max:255',
            'meta_description'       => 'sometimes|string|max:500',
        ]);
    
        $updated = [];
    
        // === 2. PROCESS EACH FIELD ===
        foreach ($validated as $label => $value) {
            $setting = Setting::where('label', $label)->first();
    
            if (!$setting) {
                continue; // Safety
            }
    
            // === HANDLE FILE UPLOADS ===
            if ($request->hasFile($label)) {
                $file = $request->file($label);
    
                // Delete old file
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
    
                // Save new file
                $ext = $file->getClientOriginalExtension();
                $filename = $label . '_' . time() . '_' . uniqid() . '.' . $ext;
                $path = $file->storeAs('settings', $filename, 'public');
    
                $newValue = $path;
            } else {
                // === TEXT / SELECT / CHECKBOX ===
                $newValue = $value;
            }
    
            // === UPDATE DB ===
            $setting->update([
                'value'     => $newValue,
                'updatedBy' => Auth::id(),
            ]);
    
            $updated[$label] = $newValue;
        }
    
        // === 3. RETURN SUCCESS ===
        return ApiResponse::success($updated, 'Settings updated successfully');
    }
}
