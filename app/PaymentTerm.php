<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $fillable = [
        'description', 'condition',
        'type', 'is_enabled', 'out_day'
    ];

    public static function types($option = null)
    {
        $options = [
            1 => 'Compra',
            'Venda',
            'Ambos'
        ];

        if (!$option)
            return $options;

        return $options[$option];
    }
}
