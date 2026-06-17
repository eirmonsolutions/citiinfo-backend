<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\Wishlist;
use App\Services\AuthProfileService;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{
    public function __construct(
        private readonly AuthProfileService $authProfile
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $ids = Wishlist::where('user_id', $user->id)->pluck('business_id');

        $listings = BusinessListing::query()
            ->whereIn('id', $ids)
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->with(['categoryRel', 'cityRel', 'gallery', 'reviews' => fn ($q) => $q->approved()->latest()])
            ->latest()
            ->get();

        return response()->json([
            'ok' => true,
            'wishlist_ids' => $ids->values()->all(),
            'listings' => $listings,
        ]);
    }

    public function ids(Request $request)
    {
        $user = $request->user();

        $ids = Wishlist::where('user_id', $user->id)
            ->pluck('business_id')
            ->values()
            ->all();

        return response()->json([
            'ok' => true,
            'wishlist_ids' => $ids,
            'wishlist_count' => count($ids),
        ]);
    }

    public function toggle(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'business_id' => 'required|integer|exists:business_listings,id',
        ]);

        $businessId = (int) $request->business_id;

        $existing = Wishlist::where('user_id', $user->id)
            ->where('business_id', $businessId)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
            $action = 'removed';
            $message = 'Listing removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'business_id' => $businessId,
            ]);
            $saved = true;
            $action = 'added';
            $message = 'Listing added to wishlist.';
        }

        $profile = $this->authProfile->refreshProfileCache($user);

        return response()->json([
            'ok' => true,
            'success' => true,
            'saved' => $saved,
            'action' => $action,
            'message' => $message,
            'wishlist_count' => $profile['wishlist_count'],
            'wishlist_ids' => $profile['wishlist_ids'],
        ]);
    }
}
