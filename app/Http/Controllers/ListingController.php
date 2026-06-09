<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use App\Models\Feature;
use App\Models\User; // ✅ add
use App\Mail\ListingAdminCredentialsMail; // ✅ add
use App\Models\BusinessListing;
use App\Models\Suburb;
use App\Models\BusinessContact;
use App\Models\BusinessSocialLink;
use App\Models\BusinessHour;
use App\Models\BusinessService;
use App\Models\BusinessFeature;
use App\Models\BusinessGallery;
use App\Models\BusinessVideoLink;
use Illuminate\Support\Facades\Hash; // ✅ add
use Illuminate\Support\Facades\Mail; // ✅ add
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\BusinessReview;
use App\Mail\ListingSubmittedMail;
use App\Mail\NewListingAdminNotificationMail;
use App\Services\ListingViewService;

class ListingController extends Controller
{





    public function show($slug, ListingViewService $viewService)
    {
        $today = Carbon::today()->toDateString();

        $listing = BusinessListing::with([
            'hours',
            'gallery',
            'features.feature',
            'cityRel',
            'stateRel',
            'countryRel',
            'faqs.items',
            'contacts',
            'socialLinks',
            'services',

            'announcements' => function ($q) use ($today) {
                $q->where('is_active', 1)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->latest();
            },

            'events' => function ($q) use ($today) {
                $q->where('is_active', 1)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->latest();
            },

            'coupons' => function ($q) use ($today) {
                $q->where('is_active', 1)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->latest();
            },
        ])
            ->withAvg(['reviews as avg_rating' => fn($q) => $q->where('is_approved', 1)], 'rating')
            ->withCount(['reviews as total_reviews' => fn($q) => $q->where('is_approved', 1)])
            ->where('slug', $slug)
            ->firstOrFail();

        if ($listing->status === 'published' && $listing->is_allowed) {
            $viewService->record($listing);
        }

        // ✅ Reviews Base Query (approved only)
        $reviewsBase = $listing->reviews()->where('is_approved', true);

        // ✅ Pagination (5 per page)
        $reviews = (clone $reviewsBase)->paginate(5);

        // ✅ Stats from eager loaded data
        $totalReviews = $listing->total_reviews;
        $avgRating    = round($listing->avg_rating ?? 0, 1);

        $stars = DB::table('business_reviews')
            ->select('rating', DB::raw('count(*) as total'))
            ->where('business_id', $listing->id)
            ->where('is_approved', 1)
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $starCounts = [
            5 => $stars[5] ?? 0,
            4 => $stars[4] ?? 0,
            3 => $stars[3] ?? 0,
            2 => $stars[2] ?? 0,
            1 => $stars[1] ?? 0,
        ];

        return view('pages.listingdetail', compact(
            'listing',
            'reviews',
            'totalReviews',
            'avgRating',
            'starCounts'
        ));
    }

    public function storeReview(Request $request, $slug)
    {
        $listing = BusinessListing::where('slug', $slug)->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        BusinessReview::create([
            'business_id'  => $listing->id,
            'user_id'      => auth()->id(),
            'name'         => auth()->user()->name,
            'email'        => auth()->user()->email,
            'rating'       => (int) $request->rating,
            'review'       => $request->review,
            'is_approved'  => 1, // ✅ immediate show (change to 0 if admin approval)
        ]);

        return redirect()
            ->route('listingdetail', $listing->slug)
            ->with('swal_success', 'Review submitted successfully!');
    }


    public function create()
    {
        $countries  = Country::orderBy('name')->get();
        $features   = Feature::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pages.addlisting', compact('countries', 'categories', 'features'));
    }

    public function getStates(Request $request)
    {
        return State::where('country_id', $request->country_id)->orderBy('name')->get();
    }

    public function getCities(Request $request)
    {
        return City::where('state_id', $request->state_id)->orderBy('name')->get();
    }

    public function getSubAreas(Request $request)
    {
        return Suburb::where('city_id', $request->city_id)
            ->orderBy('name')
            ->get();
    }


    public function store(Request $request)
    {
        Log::info('Form Submit Data:', $request->all());

        // ✅ Accept both: (form fields) and (your JSON keys)
        $businessName = $request->input('business_name');
        $categoryId   = $request->input('category_id');

        $countryId = $request->input('country_id');
        $stateVal  = $request->input('state') ?? $request->input('state_id');
        $cityVal   = $request->input('city')  ?? $request->input('city_id');

        $addressVal = $request->input('address') ?? $request->input('full_address');
        $descVal    = $request->input('description') ?? $request->input('business_description');

        $listingType = $request->input('listing_type') ?? $request->input('listing_option') ?? 'free';

        $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id'   => 'required',
            'agree_terms'   => 'accepted',
            'business_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'business_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // ✅ make email required so we can create/assign admin user
            'email' => auth()->check() ? 'nullable|email' : 'required|email',
        ]);

        return DB::transaction(function () use (
            $request,
            $businessName,
            $categoryId,
            $countryId,
            $stateVal,
            $cityVal,
            $addressVal,
            $descVal,
            $listingType
        ) {

            // ✅ slug
            $baseSlug = $request->filled('slug')
                ? Str::slug($request->input('slug'))
                : Str::slug($businessName);

            $slug = $baseSlug;
            $i = 1;
            while (BusinessListing::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            // ✅ logo upload
            $logoPath = null;
            if ($request->hasFile('business_logo')) {
                $logoPath = $request->file('business_logo')->store('business/logo', 'public');
            }

            $listingEmail = auth()->check()
                ? auth()->user()->email
                : $request->input('email');

            $user = auth()->check()
                ? auth()->user()
                : User::where('email', $listingEmail)->first();

            if (!$user) {
                $plainPassword = Str::random(10);

                $user = User::create([
                    'name'            => $request->input('contact_name') ?? $request->input('business_name') ?? 'Admin',
                    'email'           => $listingEmail,
                    'password'        => Hash::make($plainPassword),
                    'role'            => 'user',
                    'is_blocked'      => 0,
                    'is_auto_created' => true,
                ]);
            }

            // ✅ create listing (NOW $user exists)
            $listing = BusinessListing::create([
                'user_id'       => $user->id,
                'business_name' => $businessName,
                'category_id'   => $categoryId,
                'category'      => $request->input('category') ?? null,
                'slug'          => $slug,
                'country'       => $countryId,
                'state'         => $stateVal,
                'city'          => $cityVal,
                'address'       => $addressVal,
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude'),
                'description'   => $descVal,
                'logo'          => $logoPath,
                'listing_type'  => $listingType,
                'is_featured'   => (bool)($request->input('is_featured') ?? false),
                'status'        => 'pending',
                'submitted_at'  => now(),
            ]);

            Mail::to($listingEmail)->send(new ListingSubmittedMail($listing));

            // admin ko mail
            $adminEmails = array_filter([
                'vishaleirmon15896@gmail.com',
                'info@eirmonsolutions.com.au',
            ]);

            Mail::to($adminEmails)->send(new NewListingAdminNotificationMail($listing));

            // ✅ contact
            if ($request->filled('contact_name') || $request->filled('phone') || $request->filled('email')) {
                BusinessContact::create([
                    'business_id'     => $listing->id,
                    'contact_name'    => $request->input('contact_name'),
                    'phone'           => $request->input('phone'),
                    'email' => $listingEmail,
                    'alternate_phone' => $request->input('alternate_phone'),
                    'website'         => $request->input('website'),
                    'is_primary'      => true,
                ]);
            }

            // ✅ business hours save
            $hours = $request->input('hours', []);
            $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($daysOrder as $day) {
                $d = $hours[$day] ?? [];
                $isClosed = empty($d['start']) || empty($d['end']) ? 1 : 0;

                BusinessHour::create([
                    'business_id' => $listing->id,
                    'day_of_week' => $day,
                    'is_closed'   => $isClosed,
                    'open_time'   => $d['start'] ?? null,
                    'close_time'  => $d['end'] ?? null,
                    'break_start' => $d['lunch_start'] ?? null,
                    'break_end'   => $d['lunch_end'] ?? null,
                ]);
            }

            // ✅ social links
            $platforms = $request->input('social_platform', []);
            $urls      = $request->input('social_url', []);

            if (is_array($platforms) && count($platforms)) {
                foreach ($platforms as $k => $p) {
                    $u = $urls[$k] ?? null;
                    if ($p && $u) {
                        BusinessSocialLink::create([
                            'business_id' => $listing->id,
                            'platform'    => $p,
                            'url'         => $u,
                        ]);
                    }
                }
            } else {
                $directSocial = [
                    'facebook'  => $request->input('facebook'),
                    'instagram' => $request->input('instagram'),
                    'youtube'   => $request->input('youtube'),
                    'twitter'   => $request->input('twitter'),
                    'linkedin'  => $request->input('linkedin'),
                    'snapchat'  => $request->input('snapchat'),
                ];

                foreach ($directSocial as $platform => $url) {
                    if (!empty($url)) {
                        BusinessSocialLink::create([
                            'business_id' => $listing->id,
                            'platform'    => $platform,
                            'url'         => $url,
                        ]);
                    }
                }
            }

            // ✅ FEATURES (save only once) — CSV from hidden inputs
            $featuresStr      = $request->input('features');
            $featureImagesStr = $request->input('feature_images');
            $featureIdsStr    = $request->input('feature_id');

            if (!empty($featuresStr)) {
                $names = array_values(array_filter(array_map('trim', explode(',', $featuresStr))));
                $images = !empty($featureImagesStr)
                    ? array_values(array_map('trim', explode(',', $featureImagesStr)))
                    : [];
                $ids = !empty($featureIdsStr)
                    ? array_values(array_map('trim', explode(',', $featureIdsStr)))
                    : [];

                foreach ($names as $idx => $fname) {
                    BusinessFeature::create([
                        'business_id'   => $listing->id,
                        'feature_id'    => $ids[$idx] ?? null,
                        'feature_name'  => $fname,
                        'feature_image' => $images[$idx] ?? null,
                    ]);
                }
            }

            // ✅ services
            $sn = $request->input('service_name', []);
            if (is_array($sn) && count($sn)) {
                foreach ($sn as $k => $name) {
                    if (!$name) continue;

                    BusinessService::create([
                        'business_id'       => $listing->id,
                        'name'              => $name,
                        'description'       => $request->input("service_description.$k"),
                        'price'             => $request->input("service_price.$k"),
                        'currency'          => $request->input("service_currency.$k"),
                        'duration_minutes'  => $request->input("service_duration.$k"),
                        'is_popular'        => (bool)($request->input("service_popular.$k") ?? false),
                        'sort_order'        => $k,
                    ]);
                }
            } else {
                $services = $request->input('services', []);
                if (is_array($services)) {
                    foreach ($services as $k => $svc) {
                        if (!is_array($svc)) continue;
                        $name = $svc['name'] ?? null;
                        if (!$name) continue;

                        BusinessService::create([
                            'business_id'      => $listing->id,
                            'name'             => $name,
                            'description'      => $svc['description'] ?? null,
                            'price'            => $svc['price'] ?? null,
                            'currency'         => $svc['currency'] ?? null,
                            'duration_minutes' => $svc['duration'] ?? null,
                            'is_popular'       => (bool)($svc['popular'] ?? false),
                            'sort_order'       => $k,
                        ]);
                    }
                }
            }

            // ✅ gallery upload (multiple) - OFFLOADED TO QUEUE
            if ($request->hasFile('business_gallery')) {
                foreach ($request->file('business_gallery') as $index => $img) {
                    // Store temporarily in local disk
                    $tempName = uniqid('temp_') . '.' . $img->getClientOriginalExtension();
                    $tempPath = $img->storeAs('temp-uploads', $tempName, 'local');

                    // Dispatch job
                    \App\Jobs\ProcessListingGalleryImage::dispatch($listing->id, $tempPath, $index);
                }
            }

            // ✅ video link
            $videoUrl = $request->input('video_link_url') ?? $request->input('youtube_video');

            if (!empty($videoUrl) || $request->filled('embed_code')) {
                BusinessVideoLink::create([
                    'business_id'     => $listing->id,
                    'video_link_url'  => $videoUrl,
                    'embed_code'      => $request->input('embed_code'),
                    'provider'        => $request->input('provider'),
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your listing submitted successfully! Waiting for approval.',
                    'listing_id' => $listing->id,
                    'status' => $listing->status,
                ]);
            }

            return back()->with('success', 'Your listing submitted successfully! (Waiting for approval)');
        });
    }
}
