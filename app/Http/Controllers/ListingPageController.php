<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessListing;
use App\Models\Wishlist;

class ListingPageController extends Controller
{
    public function index(Request $request)
    {
        // GET filters
        $q        = trim($request->get('q', ''));
        $location = trim($request->get('location', ''));
        $online   = $request->boolean('online');
        $sort     = $request->get('sort', 'newest');   // popular|top_rated|newest|nearest
        $view     = $request->get('view', 'grid');     // grid|list

        $query = BusinessListing::query()
            ->with('cityRel') // relation eager load
            ->where('status', 'published')
            ->where('is_allowed', 1);

        $wishIds = auth()->check()
            ? Wishlist::where('user_id', auth()->id())
            ->pluck('business_id')
            ->toArray()
            : [];

        // ✅ Search
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('business_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // ✅ Location filter
        if ($location !== '') {
            $query->where(function ($qq) use ($location) {
                $qq->where('city', $location);

                if (method_exists(BusinessListing::class, 'cityRel')) {
                    $qq->orWhereHas('cityRel', function ($c) use ($location) {
                        $c->where('name', $location);
                    });
                }
            });
        }

        // ✅ Online
        if ($online && \Schema::hasColumn('business_listings', 'is_online')) {
            $query->where('is_online', 1);
        }

        // ✅ Sorting
        if ($sort === 'popular' && \Schema::hasColumn('business_listings', 'views')) {
            $query->orderByDesc('views')->orderByDesc('id');
        } elseif ($sort === 'top_rated' && \Schema::hasColumn('business_listings', 'avg_rating')) {
            $query->orderByDesc('avg_rating')->orderByDesc('id');
        } else {
            $query->latest();
        }

        // ✅ Paginate
        $listings = $query->get();

        // ✅ Dropdown ke liye DB se unique cities nikaalo
        $cities = BusinessListing::query()
            ->with('cityRel')
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->get()
            ->map(function ($listing) {
                return $listing->cityRel->name ?? $listing->city;
            })
            ->filter(function ($city) {
                return !empty($city);
            })
            ->unique()
            ->sort()
            ->values();

        return view('pages.listingpage', compact(
            'listings',
            'q',
            'location',
            'online',
            'sort',
            'view',
            'cities',
            'wishIds'
        ));
    }
}
