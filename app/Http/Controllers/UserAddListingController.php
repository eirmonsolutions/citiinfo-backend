<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Support\Facades\Mail;
use App\Models\BusinessListing;
use App\Models\BusinessContact;
use App\Models\BusinessSocialLink;
use App\Models\BusinessHour;
use App\Models\BusinessService;
use App\Models\BusinessFeature;
use App\Models\BusinessGallery;
use App\Models\BusinessVideoLink;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Mail\ListingSubmittedMail;
use App\Mail\NewListingAdminNotificationMail;

class UserAddListingController extends Controller
{
    public function index()
    {
        $countries  = Country::orderBy('name')->get();
        $features   = Feature::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $defaultCountry = Country::defaultCountry();

        return view('user.addlisting.create', compact('countries', 'categories', 'features', 'defaultCountry'));
    }

    public function getStates(Request $request)
    {
        return State::where('country_id', $request->country_id)->orderBy('name')->get();
    }

    public function getCities(Request $request)
    {
        return City::where('state_id', $request->state_id)->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        Log::info('User Add Listing Submit Data:', $request->all());

        $authUser = auth()->user();
        if (!$authUser) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

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
            'business_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'business_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        return DB::transaction(function () use (
            $request,
            $authUser,
            $businessName,
            $categoryId,
            $countryId,
            $stateVal,
            $cityVal,
            $addressVal,
            $descVal,
            $listingType
        ) {
            // ✅ unique slug
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

            // ✅ owner is logged-in user
            $user = $authUser;

            // ✅ LISTING create => PENDING (SuperAdmin approve karega)
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

                // ✅ pending until approve
                'status'        => 'pending',
                'is_allowed'    => 0,          // (optional but recommended)
                'submitted_at'  => now(),
                'approved_at'   => null,
            ]);

            // ✅ contact email auto from auth user
            $contactEmail = $request->input('contact_email') ?? $user->email;

            if ($request->filled('contact_name') || $request->filled('phone') || !empty($contactEmail)) {
                BusinessContact::create([
                    'business_id'     => $listing->id,
                    'contact_name'    => $request->input('contact_name'),
                    'phone'           => $request->input('phone'),
                    'email'           => $contactEmail,
                    'alternate_phone' => $request->input('alternate_phone'),
                    'website'         => $request->input('website'),
                    'is_primary'      => true,
                ]);
            }
              $listingEmail = $contactEmail ?: $user->email;

            $adminEmails = array_filter([
                'vishaleirmon15896@gmail.com',
                'info@eirmonsolutions.com.au',
            ]);
              

            // ✅ business hours
            $hours = $request->input('hours', []);
            $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

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

            // ✅ FEATURES (CSV hidden input)
            $featuresStr      = $request->input('features');
            $featureImagesStr = $request->input('feature_images');
            $featureIdsStr    = $request->input('feature_id');

            if (!empty($featuresStr)) {
                $names  = array_values(array_filter(array_map('trim', explode(',', $featuresStr))));
                $images = !empty($featureImagesStr) ? array_values(array_map('trim', explode(',', $featureImagesStr))) : [];
                $ids    = !empty($featureIdsStr) ? array_values(array_map('trim', explode(',', $featureIdsStr))) : [];

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

            // ✅ gallery upload
            if ($request->hasFile('business_gallery')) {
                $manager = new ImageManager(new Driver());

                foreach ($request->file('business_gallery') as $index => $img) {
                    $image = $manager->read($img->getPathname());
                    $image->scaleDown(width: 1200);

                    $filename = uniqid('gallery_') . '.jpg';
                    $path = 'business/gallery/' . $filename;

                    $quality = 85;
                    do {
                        $encoded = $image->toJpeg($quality);
                        $quality -= 5;
                    } while (strlen((string)$encoded) > (500 * 1024) && $quality > 30);

                    Storage::disk('public')->put($path, (string)$encoded);

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
               Mail::to($listingEmail)->send(new ListingSubmittedMail($listing));
                 foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new NewListingAdminNotificationMail($listing));
                }



            return back()->with('success', 'Listing added successfully! Now waiting for SuperAdmin approval.');
        });
    }
}
