<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ListingApiController extends Controller
{
    public function index(Request $request)
    {
        $q            = trim($request->get('q', ''));
        $location     = trim($request->get('location', ''));
        $city         = trim($request->get('city', ''));
        $categorySlug = trim($request->get('category_slug', ''));
        $online       = $request->boolean('online');
        $sort         = $request->get('sort', 'newest');
        $perPage      = (int) $request->get('per_page', 20);

        $today = now()->toDateString();

        $query = BusinessListing::query()
            ->with([
                'cityRel',
                'stateRel',
                'countryRel',
                'categoryRel',
                'contacts',
                'services',
                'gallery',
                'reviews',
                'features.feature',
                'hours',
                'coupons',
                'faqs.items',
                'socialLinks',

                'announcements' => function ($q) use ($today) {
                    $q->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->latest();
                },

                'events' => function ($q) use ($today) {
                    $q->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->latest();
                },
            ])
            ->where('status', 'published')
            ->where('is_allowed', 1);

        if ($request->boolean('homepage')) {
            $query->where('show_on_homepage', 1);
        }

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('business_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhereHas('categoryRel', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%")
                            ->orWhere('slug', 'like', "%{$q}%");
                    });
            });
        }

        if ($city !== '') {

            $cityKey = strtolower(str_replace(['-', '_'], ' ', trim($city)));

            $stateMap = [
                'melbourne' => 3903,
                'brisbane'  => 3905,
                'sydney'    => 3906,
                'perth'     => 3904,
            ];

            $stateId = $stateMap[$cityKey] ?? null;

            if ($stateId) {

                $cityIds = City::where('state_id', $stateId)
                    ->pluck('id')
                    ->toArray();

                $query->where(function ($qq) use ($cityIds, $stateId) {
                    $qq->whereIn('city', $cityIds)
                        ->orWhere('state', $stateId);
                });
            } else {

                $cityIds = City::where('name', 'like', "%{$city}%")
                    ->pluck('id')
                    ->toArray();

                $query->where(function ($qq) use ($city, $cityIds) {
                    $qq->whereIn('city', $cityIds)
                        ->orWhere('city', 'like', "%{$city}%")
                        ->orWhereHas('cityRel', function ($c) use ($city) {
                            $c->where('name', 'like', "%{$city}%");
                        })
                        ->orWhere('address', 'like', "%{$city}%");
                });
            }
        }

        if ($location !== '') {
            $query->where(function ($qq) use ($location) {
                $qq->where('city', $location)
                    ->orWhereHas('cityRel', function ($c) use ($location) {
                        $c->where('name', $location);
                    });
            });
        }

        if ($categorySlug !== '') {
            $query->whereHas('categoryRel', function ($c) use ($categorySlug) {
                $c->where('slug', $categorySlug);
            });
        }

        if ($online && Schema::hasColumn('business_listings', 'is_online')) {
            $query->where('is_online', 1);
        }

        if ($sort === 'popular') {
            $query->orderByDesc('views_count')->orderByDesc('id');
        } elseif ($sort === 'top_rated') {
            $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('business_name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('business_name', 'desc');
        } elseif ($sort === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'date_desc') {
            $query->latest();
        } else {
            $query->latest();
        }

        $listings = $query->paginate($perPage)->withQueryString();

        $cities = BusinessListing::query()
            ->with('cityRel')
            ->where('status', 'published')
            ->where('is_allowed', 1)
            ->get()
            ->map(fn($listing) => $listing->cityRel->name ?? $listing->city)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $listings->items(),
            'pagination' => [
                'current_page' => $listings->currentPage(),
                'last_page'    => $listings->lastPage(),
                'per_page'     => $listings->perPage(),
                'total'        => $listings->total(),
                'from'         => $listings->firstItem(),
                'to'           => $listings->lastItem(),
            ],
            'cities' => $cities,
        ]);
    }
}
