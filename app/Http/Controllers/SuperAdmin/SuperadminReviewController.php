<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Models\BusinessReview;
use Illuminate\Http\Request;

class SuperadminReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $reviews = BusinessReview::query()
            ->with(['business', 'user'])
            ->when($filter === 'visible', fn ($q) => $q->where('is_approved', true))
            ->when($filter === 'hidden', fn ($q) => $q->where('is_approved', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all'     => BusinessReview::count(),
            'visible' => BusinessReview::where('is_approved', true)->count(),
            'hidden'  => BusinessReview::where('is_approved', false)->count(),
        ];

        return view('superadmin.review.index', compact('reviews', 'filter', 'counts'));
    }

    public function edit(BusinessReview $review)
    {
        $review->load(['business', 'user']);

        return view('superadmin.review.edit', compact('review'));
    }

    public function update(Request $request, BusinessReview $review)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        $review->update($data);

        BusinessEnquiry::where('business_review_id', $review->id)->update([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'rating'  => $data['rating'],
            'message' => $data['review'],
        ]);

        return redirect()
            ->route('superadmin.review.index')
            ->with('success', 'Review updated successfully.');
    }

    public function toggleVisibility(BusinessReview $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        $status = $review->is_approved ? 'visible on listing pages' : 'hidden from listing pages';

        return back()->with('success', "Review is now {$status}.");
    }

    public function destroy(BusinessReview $review)
    {
        BusinessEnquiry::where('business_review_id', $review->id)->delete();
        $review->delete();

        return redirect()
            ->route('superadmin.review.index')
            ->with('success', 'Review deleted successfully.');
    }
}
