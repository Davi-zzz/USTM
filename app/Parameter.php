<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = [
        'name', 'type', 'value', 'description'
    ];


    public static function opTypes($option = null)
    {
        $options=  [
            1 => 'Alfanumérico',
                'Numérico',
                'Lógico',
        ];

        if (!$option)
            return $options;
        
        return $options[$option];
    }

}
