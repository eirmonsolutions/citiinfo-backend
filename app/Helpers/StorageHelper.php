<?php

use App\Support\StorageUrl;

if (! function_exists('storage_public_url')) {
    function storage_public_url(?string $path): ?string
    {
        return StorageUrl::public($path);
    }
}
