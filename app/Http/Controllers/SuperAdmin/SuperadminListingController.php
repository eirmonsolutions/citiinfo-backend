<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessListing;
use App\Models\User;
use App\Models\Category;
use App\Models\Country;
use App\Models\Feature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ListingAdminCredentialsMail;
use App\Models\BusinessContact;
use App\Models\BusinessHour;
use App\Models\BusinessSocialLink;
use App\Models\BusinessFeature;
use App\Models\BusinessService;
use App\Models\BusinessGallery;
use App\Models\BusinessVideoLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperadminListingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = BusinessListing::query()
            ->with(['categoryRel', 'cityRel'])
            ->orderBy('id', 'desc');

        if ($status === 'trash') {
            $query->onlyTrashed();
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        $listings = $query->paginate(10)->withQueryString();

        $counts = [
            'all'       => BusinessListing::count(),
            'published' => BusinessListing::where('status', 'published')->count(),
            'pending'   => BusinessListing::where('status', 'pending')->count(),
            'expired'   => BusinessListing::where('status', 'expired')->count(),
            'trash'     => BusinessListing::onlyTrashed()->count(),
        ];

        return view('superadmin.listing.index', compact('listings', 'status', 'counts'));
    }

    public function approve(BusinessListing $listing)
    {
        $listing->update([
            'status'      => 'published',
            'is_allowed'  => 1,
            'approved_at' => now(),
        ]);

        $user = User::find($listing->user_id);

        if ($user) {
            if ((bool) $user->is_auto_created === true) {
                $plainPassword = Str::random(10);

                $user->update([
                    'password' => Hash::make($plainPassword),
                    'role'     => 'admin',
                ]);

                Mail::to($user->email)->send(
                    new ListingAdminCredentialsMail($user, $plainPassword, $listing, true)
                );
            } else {
                $user->update([
                    'role' => 'admin',
                ]);

                Mail::to($user->email)->send(
                    new ListingAdminCredentialsMail($user, null, $listing, false)
                );
            }
        }

        return back()->with('success', 'Listing approved and published. Approval email sent successfully.');
    }

    public function show(BusinessListing $listing)
    {
        // Superadmin listing view me features ke andar related Feature (icon_image) bhi load karna zaroori hai
        $listing->load([
            'features.feature',
        ]);

        return view('superadmin.listing.view', compact('listing'));
    }

    public function destroy(BusinessListing $listing)
    {
        // trash me jaate hi homepage se bhi hata do
        if ($listing->show_on_homepage) {
            $listing->show_on_homepage = 0;
            $listing->save();
        }

        $listing->delete();

        return back()->with('success', 'Listing moved to Trash!');
    }

    public function restore($id)
    {
        $listing = BusinessListing::withTrashed()->findOrFail($id);
        $listing->restore();

        return redirect()
            ->route('superadmin.listing.index', ['status' => 'all'])
            ->with('success', 'Listing restored successfully!');
    }

    public function pendingCount()
    {
        $count = BusinessListing::where('status', 'pending')->count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function toggleAllow(BusinessListing $listing)
    {
        $listing->is_allowed = !$listing->is_allowed;

        // agar disallow hua to homepage se bhi hata do
        if (!$listing->is_allowed && $listing->show_on_homepage) {
            $listing->show_on_homepage = 0;
        }

        $listing->save();

        $message = $listing->is_allowed
            ? 'Listing allowed successfully!'
            : 'Listing disallowed successfully!';

        return back()->with('success', $message);
    }

    public function toggleHomepage(BusinessListing $listing)
    {
        // sirf published + allowed listing hi homepage pool me ja sake
        if ($listing->status !== 'published' || !$listing->is_allowed) {
            return back()->with('error', 'Only published and allowed listings can be shown on homepage.');
        }

        // ✅ max 6 wali condition hata di
        $listing->show_on_homepage = !$listing->show_on_homepage;
        $listing->save();

        $message = $listing->show_on_homepage
            ? 'Listing added to homepage pool successfully!'
            : 'Listing removed from homepage pool successfully!';

        return back()->with('success', $message);
    }

    public function edit(BusinessListing $listing)
    {
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
            'features.feature',
            'services',
            'gallery',
            'videos',
        ]);

        return view('superadmin.listing.edit', compact('listing', 'categories', 'countries', 'features'));
    }

    public function update(Request $request, BusinessListing $listing)
    {
        // Check if the listing belongs to the current logged-in user, else superadmin can edit it
        abort_if(!auth()->check(), 403, 'Unauthorized');

        // Log::info('Superadmin Listing Update Data:', $request->all());

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
            'email' => 'nullable|email', // ✅ contact email only (OWNER change nahi karega)
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

            $featuresStr   = $request->input('features');        // csv names
            $featureImages = $request->input('feature_icons');   // csv image paths (hidden input name)
            $featureIdsStr = $request->input('feature_id');      // csv ids

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
                        'is_cover'    => $index === 0,
                        'sort_order'  => $index,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            if ($request->filled('deleted_gallery_ids')) {

                $ids = explode(',', $request->deleted_gallery_ids);

                $images = BusinessGallery::whereIn('id', $ids)->get();

                foreach ($images as $image) {

                    if (
                        $image->image_path &&
                        Storage::disk('public')->exists($image->image_path)
                    ) {

                        Storage::disk('public')->delete($image->image_path);
                    }

                    $image->delete();
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

            return redirect()->route('superadmin.listing.index')
                ->with('success', 'Listing updated successfully!');
        });
    }
}
