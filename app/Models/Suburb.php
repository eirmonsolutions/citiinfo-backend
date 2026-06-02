<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suburb extends Model
{
    protected $table = 'sub_areas';
      protected $fillable = [
        'name',
        'city_id',
        'state_id',
        'country_id',
        'postcode',
        'latitude',
        'longitude',
        'slug',
        'status'
    ];


    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
