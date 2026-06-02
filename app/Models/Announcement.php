<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'listing_id',
        'icon',
        'title',
        'description',
        'button_text',
        'button_link',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function listing()
    {
        return $this->belongsTo(\App\Models\BusinessListing::class, 'listing_id');
    }

    public function scopeActiveNow($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }


}
