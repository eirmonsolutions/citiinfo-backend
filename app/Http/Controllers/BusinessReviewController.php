<?php

namespace App\Http\Controllers;

use App\Models\BusinessListing;
use App\Models\BusinessReview;
use Illuminate\Http\Request;

class BusinessReviewController extends Controller
{
    public function store(Request $request, $slug)
    {
        $listing = BusinessListing::where('slug', $slug)->firstOrFail();

        $isLoggedIn = auth()->check();

        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:50',
        ];

        // guest ke liye name/email required
        if (!$isLoggedIn) {
            $rules['name']  = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $data = $request->validate($rules);

        // logged-in -> user data
        if ($isLoggedIn) {
            $user = auth()->user();
            $data['name']  = $user->name ?? 'User';
            $data['email'] = $user->email ?? 'noemail@example.com';
        }

        BusinessReview::create([
            'business_id' => $listing->id,
            'user_id'     => $isLoggedIn ? auth()->id() : null,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'rating'      => (int)$data['rating'],
            'review'      => $data['review'],
            'is_approved' => true,
        ]);

        return back()->with('swal_success', 'Thanks! Your review has been submitted.');
    }
}
