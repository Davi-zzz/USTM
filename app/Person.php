<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nickname',
        'nif',
        'email',
        'zip_code',
        'address',
        'city_id',
        'phone'
    ];

    /**
     * The city of the people.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * The user of the people.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
