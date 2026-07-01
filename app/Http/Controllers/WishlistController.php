<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WishlistController extends Controller
{
    public function indexAdmin()
    {
        $userId = auth()->id();

        $wishIds = Wishlist::where('user_id', $userId)->pluck('business_id')->all();

        $listings = BusinessListing::query()
            ->with(['gallery', 'hours', 'categoryRel', 'cityRel', 'stateRel'])
            ->whereIn('id', $wishIds)
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->latest()
            ->get();

        return view('admin.wishlist.index', compact('listings', 'wishIds'));
    }

    public function toggle(Request $request)
    {
        $userId = auth()->id();

        if (! $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Login required',
            ], 401);
        }

        $request->validate([
            'business_id' => 'required|integer|exists:business_listings,id',
        ]);

        $businessId = (int) $request->business_id;

        $existing = Wishlist::where('user_id', $userId)
            ->where('business_id', $businessId)
            ->first();

        if ($existing) {
            $existing->delete();
            Cache::forget("wishlist_count_{$userId}");

            return response()->json([
                'success' => true,
                'saved' => false,
                'action' => 'removed',
                'message' => 'Listing removed from wishlist.',
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'business_id' => $businessId,
        ]);

        Cache::forget("wishlist_count_{$userId}");

        return response()->json([
            'success' => true,
            'saved' => true,
            'action' => 'added',
            'message' => 'Listing added to wishlist.',
        ]);
    }

    public function index()
    {
        $userId = auth()->id();

        if (! $userId) {
            return redirect()->route('login');
        }

        return redirect()->route('wishlist.index');
    }
}
