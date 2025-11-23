<?php


use Illuminate\Support\Facades\Storage;

if (!function_exists('getImageUrl')) {
    function getImageUrl(?string $path): string
    {
        // Debugging (optional)
        // dump($path);

        // Fallback if no path provided
        if (empty($path)) {
            return asset('assets/images/default.svg');
        }

        // If already an absolute URL
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        // Normalize path
        $normalized = ltrim($path, '/');

        // Check if file exists in public storage
        if (Storage::disk('public')->exists($normalized)) {
            return asset("storage/{$normalized}");
        }

        // Fallback if file not found
        return asset('assets/images/default.svg');
    }
}
