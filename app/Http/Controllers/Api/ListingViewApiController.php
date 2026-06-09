<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Services\ListingViewService;
use Illuminate\Http\JsonResponse;

class ListingViewApiController extends Controller
{
    public function store(string $slug, ListingViewService $viewService): JsonResponse
    {
        $listing = BusinessListing::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->firstOrFail();

        $viewService->record($listing);

        return response()->json([
            'success'     => true,
            'views_count' => $listing->fresh()->views_count,
        ]);
    }
}
