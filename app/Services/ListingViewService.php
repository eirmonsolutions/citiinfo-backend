<?php

namespace App\Services;

use App\Models\BusinessListing;

class ListingViewService
{
    public function record(BusinessListing $listing): void
    {
        $listing->increment('views_count');
    }
}
