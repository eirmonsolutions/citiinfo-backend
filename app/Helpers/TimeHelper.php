<?php

use App\Services\BusinessHoursService;
use Carbon\Carbon;

if (! function_exists('listingNow')) {
    function listingNow($listing): Carbon
    {
        $timezone = app(BusinessHoursService::class)->resolveTimezone($listing);

        return Carbon::now($timezone);
    }
}

if (! function_exists('listingOpenStatus')) {
    function listingOpenStatus($listing): array
    {
        return app(BusinessHoursService::class)->status($listing);
    }
}
