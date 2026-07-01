<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\BusinessContact;
use App\Models\BusinessHour;
use App\Models\BusinessSocialLink;
use App\Models\BusinessFeature;
use App\Models\BusinessService;
use App\Models\BusinessGallery;
use App\Models\BusinessVideoLink;
use Illuminate\Support\Facades\Storage;
use App\Mail\ListingAdminCredentialsMail;


class AdminListingController extends Controller
{
    public function index()
    {
        $listings = BusinessListing::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('admin.listing.index', compact('listings'));
    }

    // ✅ ADD LISTING PAGE (your 6-step design blade)
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $countries  = Country::orderBy('name')->get();
        $features   = Feature::orderBy('name')->get();
        $defaultCountry = Country::defaultCountry();

        return view('admin.listing.create', compact('categories', 'countries', 'features', 'defaultCountry'));
    }

    // ✅ STORE (Dashboard admin add karega => ALWAYS current logged-in admin ki listing)
    public function store(Request $request)
    {
        Log::info('Admin Listing Submit Data:', $request->all());

        // ✅ MUST: user logged-in
        abort_if(!auth()->check(), 403, 'Unauthorized');

        $businessName = $request->input('business_name');
        $categoryId   = $request->input('category_id');

        $countryId = $request->input('country_id');
        $stateVal  = $request->input('state') ?? $request->input('state_id');
        $cityVal   = $request->input('city')  ?? $request->input('city_id');

        $addressVal = $request->input('address') ?? $request->input('full_address');
        $descVal    = $request->input('description') ?? $request->input('business_description');

        $listingType = $request->input('listing_type')
            ?? $request->input('listing_option')
            ?? 'free';

        $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id'   => 'required',
            'business_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'business_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'email' => 'nullable|email', // ✅ contact email only (OWNER change nahi karega)
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

            // ✅ DEBUG: which admin is creating listing
            Log::info('ADMIN STORE AUTH DEBUG', [
                'auth_id'    => auth()->id(),
                'auth_email' => optional(auth()->user())->email,
            ]);

            // ✅ slug unique
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

            // ✅ OWNER ALWAYS logged-in admin
            $ownerId = auth()->id();

            // ✅ create listing
            $listing = BusinessListing::create([
                'user_id'        => $ownerId, // ✅ FIXED: ALWAYS current admin
                'business_name'  => $businessName,
                'category_id'    => $categoryId,
                'category'       => $request->input('category') ?? null,
                'slug'           => $slug,

                'country'        => $countryId,
                'state'          => $stateVal,
                'city'           => $cityVal,
                'address'        => $addressVal,

                'latitude'       => $request->input('latitude'),
                'longitude'      => $request->input('longitude'),

                'description'    => $descVal,
                'logo'           => $logoPath,

                'listing_type'   => $listingType,
                'is_featured'    => (bool)($request->input('is_featured') ?? false),

                'status'         => 'pending',
                'submitted_at'   => now(),
            ]);

            // ✅ contact
            if ($request->filled('contact_name') || $request->filled('phone') || $request->filled('email')) {
                BusinessContact::create([
                    'business_id'     => $listing->id,
                    'contact_name'    => $request->input('contact_name'),
                    'phone'           => $request->input('phone'),
                    'email'           => $request->input('email'), // ✅ contact email only
                    'alternate_phone' => $request->input('alternate_phone'),
                    'website'         => $request->input('website'),
                    'is_primary'      => true,
                ]);
            }

            // ✅ business hours
            $hours = $request->input('hours', []);
            $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($daysOrder as $day) {
                $d = $hours[$day] ?? null;
                $isClosed = empty($d) ? 1 : 0;

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

            // ✅ FEATURES (CSV)
            $featuresStr   = $request->input('features');
            $featureImages = $request->input('feature_icons');
            $featureIdsStr = $request->input('feature_id');

            if (!empty($featuresStr)) {
                $names  = array_values(array_filter(array_map('trim', explode(',', $featuresStr))));
                $images = !empty($featureImages) ? array_values(array_map('trim', explode(',', $featureImages))) : [];
                $ids    = !empty($featureIdsStr) ? array_values(array_map('trim', explode(',', $featureIdsStr))) : [];

                foreach ($names as $idx => $fname) {
                    $fid = $ids[$idx] ?? null;
                    $fallback = $fid ? Feature::where('id', $fid)->value('icon_image') : null;

                    BusinessFeature::create([
                        'business_id'   => $listing->id,
                        'feature_id'    => $fid,
                        'feature_name'  => $fname,
                        'feature_image' => $images[$idx] ?? $fallback,
                    ]);
                }
            }

            // ✅ SERVICES
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

            // ✅ gallery upload
            if ($request->hasFile('business_gallery')) {
                foreach ($request->file('business_gallery') as $index => $img) {
                    $path = $img->store('business/gallery', 'public');

                    BusinessGallery::create([
                        'business_id' => $listing->id,
                        'image_path'  => $path,
                        'caption'     => null,
                        'alt_text'    => null,
                        'is_cover'    => $index === 0,
                        'sort_order'  => $index,
                        'uploaded_at' => now(),
                    ]);
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

            return redirect()->route('admin.listing.index')
                ->with('success', 'Listing submitted successfully! (Waiting for a approval)');
        });
    }

    public function edit(BusinessListing $listing)
    {
        abort_if($listing->user_id !== auth()->id(), 403);

        $categories = Category::orderBy('name')->get();
        $countries  = Country::orderBy('name')->get();
        $features   = Feature::orderBy('name')->get();

        $listing->load([
            'categoryRel',
            'countryRel',
            'stateRel',
            'cityRel',
            'contacts',
            'hours',
            'socialLinks',
            'features',
            'services',
            'gallery',
            'videos',
        ]);

        return view('admin.listing.edit', compact('listing', 'categories', 'countries', 'features'));
    }



    public function update(Request $request, BusinessListing $listing)
    {
        abort_if($listing->user_id !== auth()->id(), 403);

        Log::info('Admin Listing Update Data:', $request->all());

        $businessName = $request->input('business_name');
        $categoryId   = $request->input('category_id');

        $countryId = $request->input('country_id');
        $stateVal  = $request->input('state') ?? $request->input('state_id');
        $cityVal   = $request->input('city')  ?? $request->input('city_id');

        $addressVal = $request->input('address') ?? $request->input('full_address');
        $descVal    = $request->input('description') ?? $request->input('business_description');

        $listingType = $request->input('listing_type')
            ?? $request->input('listing_option')
            ?? ($listing->listing_type ?? 'free');

        $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id'   => 'required',
            'business_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'business_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'email' => 'nullable|email',
        ]);

        return DB::transaction(function () use (
            $request,
            $listing,
            $businessName,
            $categoryId,
            $countryId,
            $stateVal,
            $cityVal,
            $addressVal,
            $descVal,
            $listingType
        ) {

            // ✅ slug unique (change on edit)
            $baseSlug = $request->filled('slug')
                ? Str::slug($request->input('slug'))
                : Str::slug($businessName);

            $slug = $baseSlug;
            $i = 1;
            while (BusinessListing::where('slug', $slug)->where('id', '!=', $listing->id)->exists()) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            // ✅ logo upload (if new)
            $logoPath = $listing->logo;
            if ($request->hasFile('business_logo')) {
                $logoPath = $request->file('business_logo')->store('business/logo', 'public');
            }

            // ✅ update listing main
            $listing->update([
                'business_name' => $businessName,
                'category_id'   => $categoryId,
                'category'      => $request->input('category') ?? $listing->category,
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
                'is_featured'   => (bool)($request->input('is_featured') ?? $listing->is_featured),
            ]);

            // ✅ CONTACT (updateOrCreate)
            if ($request->filled('contact_name') || $request->filled('phone') || $request->filled('email')) {
                BusinessContact::updateOrCreate(
                    ['business_id' => $listing->id, 'is_primary' => true],
                    [
                        'contact_name'    => $request->input('contact_name'),
                        'phone'           => $request->input('phone'),
                        'email'           => $request->input('email'),
                        'alternate_phone' => $request->input('alternate_phone'),
                        'website'         => $request->input('website'),
                    ]
                );
            }

            // ✅ HOURS (delete + reinsert)
            BusinessHour::where('business_id', $listing->id)->delete();

            $hours = $request->input('hours', []);
            $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($daysOrder as $day) {
                $d = $hours[$day] ?? null;
                $isClosed = empty($d) ? 1 : 0;

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

            // ✅ SOCIAL LINKS (delete + reinsert)
            BusinessSocialLink::where('business_id', $listing->id)->delete();

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

            // ✅ FEATURES (delete + reinsert) — FIXED FOR IMAGE
            BusinessFeature::where('business_id', $listing->id)->delete();

            $featuresStr    = $request->input('features');        // csv names
            $featureImages  = $request->input('feature_icons');   // csv image paths (hidden input name)
            $featureIdsStr  = $request->input('feature_id');      // csv ids

            if (!empty($featuresStr)) {
                $names  = array_values(array_filter(array_map('trim', explode(',', $featuresStr))));
                $images = !empty($featureImages) ? array_values(array_map('trim', explode(',', $featureImages))) : [];
                $ids    = !empty($featureIdsStr) ? array_values(array_map('trim', explode(',', $featureIdsStr))) : [];

                foreach ($names as $idx => $fname) {
                    $fid = $ids[$idx] ?? null;

                    // fallback: Feature master icon_image
                    $fallback = $fid ? Feature::where('id', $fid)->value('icon_image') : null;

                    BusinessFeature::create([
                        'business_id'   => $listing->id,
                        'feature_id'    => $fid,
                        'feature_name'  => $fname,
                        'feature_image' => $images[$idx] ?? $fallback, // ✅ IMPORTANT
                    ]);
                }
            }

            // ✅ SERVICES (delete + reinsert)
            BusinessService::where('business_id', $listing->id)->delete();

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

            // ✅ GALLERY (only add new uploads)
            if ($request->hasFile('business_gallery')) {
                $startIndex = (int) BusinessGallery::where('business_id', $listing->id)->count();

                foreach ($request->file('business_gallery') as $index => $img) {
                    $path = $img->store('business/gallery', 'public');

                    BusinessGallery::create([
                        'business_id' => $listing->id,
                        'image_path'  => $path,
                        'caption'     => null,
                        'alt_text'    => null,
                        'is_cover'    => ($startIndex + $index) === 0,
                        'sort_order'  => ($startIndex + $index),
                        'uploaded_at' => now(),
                    ]);
                }
            }

            // ✅ VIDEO (updateOrCreate / delete if cleared)
            $videoUrl = $request->input('video_link_url') ?? $request->input('youtube_video');

            if (!empty($videoUrl) || $request->filled('embed_code')) {
                BusinessVideoLink::updateOrCreate(
                    ['business_id' => $listing->id],
                    [
                        'video_link_url' => $videoUrl,
                        'embed_code'     => $request->input('embed_code'),
                        'provider'       => $request->input('provider'),
                    ]
                );
            } else {
                BusinessVideoLink::where('business_id', $listing->id)->delete();
            }

            return redirect()->route('admin.listing.index')
                ->with('success', 'Listing updated successfully!');
        });
    }


    public function destroy(BusinessListing $listing)
    {
        // ✅ security: sirf apni listing delete kar sake
        abort_if($listing->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($listing) {

            // ✅ Contacts
            \App\Models\BusinessContact::where('business_id', $listing->id)->delete();

            // ✅ Hours
            \App\Models\BusinessHour::where('business_id', $listing->id)->delete();

            // ✅ Social links
            \App\Models\BusinessSocialLink::where('business_id', $listing->id)->delete();

            // ✅ Features
            \App\Models\BusinessFeature::where('business_id', $listing->id)->delete();

            // ✅ Services
            \App\Models\BusinessService::where('business_id', $listing->id)->delete();

            // ✅ Gallery (files + DB)
            $galleries = \App\Models\BusinessGallery::where('business_id', $listing->id)->get();
            foreach ($galleries as $img) {
                if ($img->image_path && \Storage::disk('public')->exists($img->image_path)) {
                    \Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }

            // ✅ Video links
            \App\Models\BusinessVideoLink::where('business_id', $listing->id)->delete();

            // ✅ Logo delete
            if ($listing->logo && \Storage::disk('public')->exists($listing->logo)) {
                \Storage::disk('public')->delete($listing->logo);
            }

            // ✅ Finally delete listing
            $listing->delete();
        });

        return redirect()->route('admin.listing.index')
            ->with('success', 'Listing deleted successfully!');
    }

    public function deleteGallery(BusinessGallery $gallery)
    {
        // ✅ security: only owner admin
        if (!$gallery->business || $gallery->business->user_id != auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // ✅ delete file from storage/public
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        // ✅ delete db row
        $gallery->delete();

        return response()->json(['success' => true]);
    }
}
