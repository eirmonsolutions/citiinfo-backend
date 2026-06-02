<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
        'listing_name',
        'title',
        'location',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'description',
        'ticket_platform',
        'ticket_url',
        'featured_image',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function listing()
    {
        return $this->belongsTo(\App\Models\BusinessListing::class, 'listing_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
