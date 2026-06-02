<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessReview extends Model
{
    protected $table = 'business_reviews';

    protected $fillable = [
        'business_id',
        'user_id',
        'name',
        'email',
        'rating',
        'review',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
