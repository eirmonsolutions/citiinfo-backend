<?php

namespace App\Services;

use App\Mail\BusinessEnquiryMail;
use App\Mail\EnquiryReplyMail;
use App\Models\BusinessReview;
use App\Models\BusinessEnquiry;
use App\Models\BusinessListing;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class BusinessEnquiryService
{
    public function syncFromReview(BusinessReview $review, ?User $sender = null): BusinessEnquiry
    {
        if ($review->id && BusinessEnquiry::where('business_review_id', $review->id)->exists()) {
            return BusinessEnquiry::where('business_review_id', $review->id)->first();
        }

        $listing = BusinessListing::findOrFail($review->business_id);
        $sender = $sender ?? ($review->user_id ? User::find($review->user_id) : null);

        return $this->submit([
            'name'    => $review->name,
            'email'   => $review->email,
            'phone'   => 'Not provided',
            'message' => $review->review,
            'rating'  => $review->rating,
        ], $listing, $sender, $review->id);
    }

    public function submit(array $data, BusinessListing $listing, ?User $sender = null, ?int $businessReviewId = null): BusinessEnquiry
    {
        $enquiry = BusinessEnquiry::create([
            'business_listing_id' => $listing->id,
            'user_id'             => $listing->user_id,
            'sender_user_id'      => $sender?->id,
            'name'                => $data['name'],
            'email'               => $data['email'],
            'phone'               => $data['phone'],
            'message'             => $data['message'] ?? null,
            'rating'              => $data['rating'] ?? null,
            'business_review_id'  => $businessReviewId,
        ]);

        $listing->loadMissing('contacts');

        $businessEmail = $listing->contacts->first()->email ?? null;

        $emails = array_filter([
            $businessEmail,
            'vishaleirmon15896@gmail.com',
        ]);

        if (! empty($emails)) {
            Mail::to($emails)->send(new BusinessEnquiryMail($data, $listing));
        }

        return $enquiry;
    }

    public function reply(BusinessEnquiry $enquiry, User $replier, string $replyText): BusinessEnquiry
    {
        $enquiry->update([
            'admin_reply' => $replyText,
            'replied_at'  => now(),
            'replied_by'  => $replier->id,
            'is_read'     => true,
        ]);

        Mail::to($enquiry->email)->send(new EnquiryReplyMail($enquiry->fresh(['listing', 'replier'])));

        return $enquiry;
    }

    public function listingIdsForOwner(int $userId): array
    {
        return BusinessListing::where('user_id', $userId)->pluck('id')->all();
    }

    public function canManageEnquiry(BusinessEnquiry $enquiry, User $user): bool
    {
        if (($user->role ?? '') === 'superadmin') {
            return true;
        }

        return in_array($enquiry->business_listing_id, $this->listingIdsForOwner($user->id), true);
    }

    public function canViewSentEnquiry(BusinessEnquiry $enquiry, User $user): bool
    {
        if ($enquiry->sender_user_id && (int) $enquiry->sender_user_id === (int) $user->id) {
            return true;
        }

        return strcasecmp($enquiry->email, $user->email) === 0;
    }
}
