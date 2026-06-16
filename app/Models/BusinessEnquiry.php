<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessEnquiry extends Model
{
    protected $fillable = [
        'business_listing_id',
        'user_id',
        'sender_user_id',
        'name',
        'email',
        'phone',
        'message',
        'rating',
        'business_review_id',
        'admin_reply',
        'replied_at',
        'replied_by',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(BusinessListing::class, 'business_listing_id');
    }

    /** Listing owner (business_listings.user_id) */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** User who sent the enquiry */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function hasReply(): bool
    {
        return ! empty($this->admin_reply);
    }
}
