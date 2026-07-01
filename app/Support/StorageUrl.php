<?php

namespace App\Support;

class StorageUrl
{
    public static function public(?string $path): ?string
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
