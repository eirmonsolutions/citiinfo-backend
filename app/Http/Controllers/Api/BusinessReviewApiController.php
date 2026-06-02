<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessReview;
use Illuminate\Http\Request;

class BusinessReviewApiController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first to submit your review.',
            ], 401);
        }

        $data = $request->validate([
            'business_id' => 'required|exists:business_listings,id',
            'rating'      => 'required|integer|min:1|max:5',
            'review'      => 'required|string|min:50',
        ]);

        BusinessReview::create([
            'business_id' => $data['business_id'],
            'user_id'     => auth()->id(),
            'name'        => auth()->user()->name,
            'email'       => auth()->user()->email,
            'rating'      => $data['rating'],
            'review'      => $data['review'],
            'status'      => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted successfully.',
        ]);
    }
}