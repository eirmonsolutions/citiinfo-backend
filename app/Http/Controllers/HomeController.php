<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\BusinessListing;
use App\Models\Wishlist;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Categories with listing count
        $categories = Category::where('is_active', 1)
            ->where('is_home', 1)
            ->withCount([
                'businessListings as listings_count' => function ($q) {
                    $q->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ])
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();

        $wishIds = auth()->check()
            ? Wishlist::where('user_id', auth()->id())
                ->pluck('business_id')
                ->toArray()
            : [];

        // ✅ Homepage par sirf random 6 listings
        $listings = BusinessListing::with([
                'gallery',
                'hours',
                'contacts',
                'reviews' => function ($q) {
                    $q->where('is_approved', 1)->latest()->limit(1);
                },
            ])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_approved', 1);
            }], 'rating')
            ->withCount(['reviews as ratings_count' => function ($q) {
                $q->where('is_approved', 1);
            }])
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->where('show_on_homepage', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $cityNames = ['Melbourne', 'Sydney', 'Perth', 'Brisbane'];

        $cityIds = BusinessListing::whereNotNull('city')
            ->select('city')
            ->distinct()
            ->pluck('city')
            ->toArray();

        $cities = City::whereIn('id', $cityIds)
            ->whereIn('name', $cityNames)
            ->withCount([
                'listings as listings_count' => function ($q) {
                    $q->where('is_allowed', 1)
                        ->where('status', 'published')
                        ->whereNull('deleted_at');
                }
            ])
            ->get()
            ->keyBy('name');

        return view('pages.homepage', compact('categories', 'listings', 'cities', 'wishIds'));
    }
}