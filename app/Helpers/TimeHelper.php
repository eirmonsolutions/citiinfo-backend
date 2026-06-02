<?php

use Carbon\Carbon;

if (!function_exists('listingNow')) {
    function listingNow($listing)
    {
        // 1️⃣ City timezone (highest priority)
        if (!empty($listing->cityRel?->timezone)) {
            return Carbon::now($listing->cityRel->timezone);
        }

        // 2️⃣ Country timezone
        if (!empty($listing->countryRel?->timezone)) {
            return Carbon::now($listing->countryRel->timezone);
        }

        // 3️⃣ Fallback app timezone
        return Carbon::now(config('app.timezone'));
    }
}
