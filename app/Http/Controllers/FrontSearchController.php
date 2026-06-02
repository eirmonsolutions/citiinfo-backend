<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class FrontSearchController extends Controller
{

    public function listingCategory($category)
    {
        $catSlug = strtolower(trim($category));

        // 🔹 Page load log
        Log::info('Listing category page loaded', [
            'category_param' => $category,
            'slug' => $catSlug,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'user_id' => auth()->id() ?? 'guest',
        ]);

        // 🔹 Find category by slug
        $categoryRow = Category::where('slug', $catSlug)->first();

        $query = BusinessListing::query();

        if ($categoryRow) {

            // ✅ REAL & CORRECT FILTER (category_id)
            $query->where('category_id', $categoryRow->id);
        } else {

            // ⚠️ Log warning if category not found
            Log::warning('Category not found', [
                'slug' => $catSlug
            ]);

            // Empty result force (avoid wrong data)
            $query->whereRaw('1 = 0');
        }

        // ✅ Only allowed + published listings
        $query->where('is_allowed', 1)
            ->where('status', 'published')
            ->whereNull('deleted_at');

        $listings = $query->with(['gallery', 'hours', 'cityRel', 'contacts'])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_approved', 1);
            }], 'rating')
            ->withCount(['reviews as ratings_count' => function ($q) {
                $q->where('is_approved', 1);
            }])
            ->latest()
            ->paginate(12);

        // 🔹 Data fetch log
        Log::info('Listing category data fetched', [
            'category_found' => (bool) $categoryRow,
            'category_id' => $categoryRow->id ?? null,
            'total_results' => $listings->total(),
            'current_page' => $listings->currentPage(),
        ]);

        $catName = $categoryRow
            ? $categoryRow->name
            : Str::title(str_replace('-', ' ', $category));

        return view('searchbar.results', [
            'listings' => $listings,
            'catName' => $catName,
            'categoryRow' => $categoryRow,
            'cityName' => null,
        ]);
    }



    public function searchRedirect(Request $request)
    {
        $service = trim($request->service ?? '');
        $cityText = trim($request->city ?? '');
        $cityId = $request->city_id;          // hidden
        $categoryId = $request->category_id;  // hidden

        // ✅ 1) If category_id selected -> city/category page
        if (!empty($categoryId) && !empty($cityId)) {

            $category = Category::findOrFail($categoryId);

            return redirect()->route('city.category', [
                'city'     => (int)$cityId,
                'category' => Str::slug($category->name),
            ]);
        }

        return redirect()->route('search.byText', [
            'service' => $service,
            'city'    => $cityText,
        ]);
    }

    public function searchByText(Request $request)
    {
        $service = trim($request->get('service', ''));
        $cityId  = $request->get('city_id'); // hidden
        $cityTxt = trim($request->get('city', ''));

        $query = BusinessListing::query()
            ->where('is_allowed', 1)
            ->where('status', 'published')
            ->whereNull('deleted_at');

        // ✅ business name search


        if ($service) {
            $query->where('business_name', 'LIKE', "%{$service}%");
        }

        // ✅ city filter (ID based)
        if (!empty($cityId)) {
            $query->where('city', (int)$cityId);
        }

        $listings = $query->with(['gallery', 'hours', 'cityRel', 'contacts'])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_approved', 1);
            }], 'rating')
            ->withCount(['reviews as ratings_count' => function ($q) {
                $q->where('is_approved', 1);
            }])
            ->latest()
            ->paginate(12);

        return view('searchbar.results', [
            'listings'     => $listings,
            'catName'      => $service ?: 'Listings',
            'categoryRow'  => null,
            'cityName'     => $cityTxt ?: null,
        ]);
    }





    // Page: /{city}/{category}
    public function listingByCityCategory($city, $category)
    {


        $cityId = (int) $city;



        $categoryRow = Category::where('slug', strtolower($category))->first();


        $query = BusinessListing::query()
            ->where('city', $cityId);

        if ($categoryRow) {
            $query->where('category_id', $categoryRow->id);
        }



        $count = (clone $query)->count();



        $listings = $query->with(['gallery', 'hours', 'cityRel', 'contacts'])
            ->withAvg(['reviews as avg_rating' => function ($q) {
                $q->where('is_approved', 1);
            }], 'rating')
            ->withCount(['reviews as ratings_count' => function ($q) {
                $q->where('is_approved', 1);
            }])
            ->latest()
            ->paginate(12);



        $cityName = $this->getCityNameById($cityId);
        $catName  = $categoryRow
            ? $categoryRow->name
            : Str::title(str_replace('-', ' ', $category));

        return view('searchbar.results', compact('listings', 'cityName', 'catName', 'categoryRow'));
    }




    // Suggest categories (service field)
    public function categorySuggest(Request $request)
    {
        $term = $request->get('term');

        Log::info('Search term:', [$term]);

        // 🔹 Categories
        $categories = Category::query()
            ->where('is_active', 1)
            ->where('name', 'LIKE', "%{$term}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name'])
            ->map(function ($cat) {
                return [
                    'id'   => $cat->id,
                    'name' => $cat->name,
                    'slug' => \Illuminate\Support\Str::slug($cat->name),
                ];
            });
        // 🔹 Business Listings
        $businesses = BusinessListing::query()
            ->where('business_name', 'LIKE', "%{$term}%")
            ->orderBy('business_name')
            ->limit(10)
            ->get([
                'id',
                'business_name',
                'category_id',
                'slug'
            ]);

        Log::info('Categories:', $categories->toArray());
        Log::info('Businesses:', $businesses->toArray());

        return response()->json([
            'categories' => $categories,
            'businesses' => $businesses
        ]);
    }

    // Suggest cities (city field) - based on DB listings (best)
    public function citySuggest(Request $request)
    {
        $term = $request->get('term', '');
        $categoryId = $request->get('category_id');

        $q = BusinessListing::query()
            ->select('city')
            ->whereNotNull('city');

        if ($categoryId) {
            $q->where('category_id', $categoryId);
        }

        $cityIds = $q->distinct()->limit(20)->pluck('city')->toArray();

        // Ab yaha cities table se name lao
        $cities = \App\Models\City::whereIn('id', $cityIds)
            ->where('name', 'like', "%{$term}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    private function getCityNameById($cityId)
    {
        // Agar aapke paas cities table hai to yaha se name fetch karo
        // Example:
        // return City::where('id', $cityId)->value('name') ?? 'City';

        return 'City'; // fallback (jab tak table confirm na ho)
    }
}
