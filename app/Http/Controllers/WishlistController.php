<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\BusinessListing;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function indexAdmin()
    {
        $userId = auth()->id();

        $ids = Wishlist::where('user_id', $userId)->pluck('business_id');

        $listings = BusinessListing::whereIn('id', $ids)
            ->latest()
            ->get();

        return view('admin.wishlist.index', compact('listings'));
    }

    public function toggle(Request $request)
    {
        $userId = auth()->id() ?? auth('admin')->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Login required'
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

            return response()->json([
                'success' => true,
                'saved' => false,
                'action' => 'removed',
                'message' => 'Listing removed from wishlist.'
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'business_id' => $businessId,
        ]);

        return response()->json([
            'success' => true,
            'saved' => true,
            'action' => 'added',
            'message' => 'Listing added to wishlist.'
        ]);
    }

    public function index()
    {
        $userId = auth()->id() ?? auth('admin')->id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $wishBusinessIds = Wishlist::where('user_id', $userId)->pluck('business_id');

        $listings = BusinessListing::whereIn('id', $wishBusinessIds)
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->latest()
            ->get();

        return view('pages.wishlist', compact('listings'));
    }
}