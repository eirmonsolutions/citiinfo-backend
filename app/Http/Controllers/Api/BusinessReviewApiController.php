<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessReview;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class BusinessReviewApiController extends Controller
{
    public function store(Request $request, BusinessEnquiryService $enquiryService)
    {
        $user = $request->user();

        $data = $request->validate([
            'business_id' => 'required|exists:business_listings,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $existing = BusinessReview::where('business_id', $data['business_id'])
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'ok' => false,
                'success' => false,
                'message' => 'You have already reviewed this listing.',
            ], 422);
        }

        $review = BusinessReview::create([
            'business_id' => $data['business_id'],
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'rating' => $data['rating'],
            'review' => $data['review'],
            'is_approved' => true,
        ]);

        $enquiryService->syncFromReview($review, $user);

        return response()->json([
            'ok' => true,
            'success' => true,
            'message' => 'Thank you! Your review has been submitted. Check Messages for replies.',
        ]);
    }
}
