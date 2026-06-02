<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $table = 'business_hours';

    protected $fillable = [
        'business_id',
        'day_of_week',
        'is_closed',
        'open_time',
        'close_time',
        'break_start',
        'break_end'
    ];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
