<?php

if (! function_exists('storage_public_url')) {
    /**
     * Always build storage URLs from APP_URL (api.citiinfo.com.au), not the request Host.
     */
    function storage_public_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $clean = ltrim($path, '/');

        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, strlen('storage/'));
        }

        return rtrim((string) config('app.url'), '/').'/storage/'.$clean;
    }
}
