<?php

namespace App;

class Role extends \Spatie\Permission\Models\Role
{
    public static function types()
    {
        return [
            1 =>'Plataforma',
                'Proprietário',
                'App',
        ];
    }


}

