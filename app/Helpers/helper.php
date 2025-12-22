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


if (!function_exists('truncateText')) {
    /**
     * Truncate text to a given length with optional ellipsis.
     *
     * @param string $text   The text to truncate
     * @param int    $length Maximum length
     * @param string $suffix Suffix to append (default: "...")
     * @return string
     */
    function truncateText(string $text, int $length = 100, string $suffix = '...'): string
    {
        $text = strip_tags($text);

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $truncated = mb_substr($text, 0, $length);

        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return $truncated . $suffix;
    }
}



use Carbon\Carbon;

if (!function_exists('formatDate')) {
    /**
     * Format a given date/time into a human-readable string.
     *
     * @param  string|\DateTimeInterface|null  $date
     * @param  string  $format
     * @return string
     */
    function formatDate($date, string $format = 'd M Y'): string
    {
        if (empty($date)) {
            return '';
        }

        return Carbon::parse($date)->format($format);
    }
}
