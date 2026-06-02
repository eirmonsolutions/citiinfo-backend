<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = ['name', 'iso2', 'iso3', 'phonecode'];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
