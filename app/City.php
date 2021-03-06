<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state_id', 'title', 'iso', 'iso_ddd', 'status',
        'slug', 'population', 'lat', 'long', 'income_per_capita'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function peoples()
    {
        return $this->hasMany(People::class);
    }
}
