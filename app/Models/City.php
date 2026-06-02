<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;

    protected $fillable = ['country_id', 'state_id', 'name'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function listings()
    {
        return $this->hasMany(\App\Models\BusinessListing::class, 'city'); // city = city_id in business_listings
    }
}
