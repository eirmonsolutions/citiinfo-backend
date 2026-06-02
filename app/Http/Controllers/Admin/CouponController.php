<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        // ✅ sirf login admin ke coupons
        $coupons = Coupon::where('user_id', $admin->id)
            ->latest()
            ->paginate(20);

        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        $admin = auth()->user();

        // ✅ Sirf login admin ki listings
        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.coupon.create', compact('listings'));
    }

    public function store(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'listing_id'      => ['required', 'integer'],
            'listing_name'    => ['nullable', 'string', 'max:255'],

            'title'           => ['required', 'string', 'max:255'],
            'code'            => ['required', 'string', 'max:100'],
            'discount_value'  => ['required', 'numeric', 'min:0'],

            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],

            'details'         => ['required', 'string'],

            'featured_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ✅ listing ownership check (security)
        $listingOk = BusinessListing::where('id', $request->listing_id)
            ->where('user_id', $admin->id)
            ->exists();

        if (!$listingOk) {
            return back()->withErrors(['listing_id' => 'Invalid listing selected.'])->withInput();
        }

        $data = $request->only([
            'listing_id',
            'listing_name',
            'title',
            'code',
            'discount_value',
            'start_date',
            'end_date',
            'details'
        ]);

        $data['user_id'] = $admin->id;

        // ✅ image upload
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $name = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();

            // storage/app/public/coupons/...
            $path = $file->storeAs('coupons', $name, 'public');
            $data['featured_image'] = $path; // coupons/xxx.jpg
        }

        Coupon::create($data);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $admin = auth()->user();

        // ✅ only owner admin
        abort_if($coupon->user_id !== $admin->id, 403);

        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.coupon.edit', compact('coupon', 'listings'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $admin = auth()->user();
        abort_if($coupon->user_id !== $admin->id, 403);

        $request->validate([
            'listing_id'      => ['required', 'integer'],
            'listing_name'    => ['nullable', 'string', 'max:255'],

            'title'           => ['required', 'string', 'max:255'],
            'code'            => ['required', 'string', 'max:100'],
            'discount_value'  => ['required', 'numeric', 'min:0'],

            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],

            'details'         => ['required', 'string'],

            'featured_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ✅ listing ownership check
        $listingOk = BusinessListing::where('id', $request->listing_id)
            ->where('user_id', $admin->id)
            ->exists();

        if (!$listingOk) {
            return back()->withErrors(['listing_id' => 'Invalid listing selected.'])->withInput();
        }

        $data = $request->only([
            'listing_id',
            'listing_name',
            'title',
            'code',
            'discount_value',
            'start_date',
            'end_date',
            'details'
        ]);

        // ✅ image replace
        if ($request->hasFile('featured_image')) {
            // delete old
            if (!empty($coupon->featured_image) && Storage::disk('public')->exists($coupon->featured_image)) {
                Storage::disk('public')->delete($coupon->featured_image);
            }

            $file = $request->file('featured_image');
            $name = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('coupons', $name, 'public');

            $data['featured_image'] = $path;
        }

        $coupon->update($data);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully.');
    }

    public function toggle(Coupon $coupon)
    {
        $admin = auth()->user();
        abort_if($coupon->user_id !== $admin->id, 403);

        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return back()->with('success', 'Coupon status updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $admin = auth()->user();
        abort_if($coupon->user_id !== $admin->id, 403);

        if (!empty($coupon->featured_image) && Storage::disk('public')->exists($coupon->featured_image)) {
            Storage::disk('public')->delete($coupon->featured_image);
        }

        $coupon->delete();

        return back()->with('success', 'Coupon deleted.');
    }
}
