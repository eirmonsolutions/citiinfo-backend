<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    public $timestamps = false;

    protected $fillable = ['country_id', 'name'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
