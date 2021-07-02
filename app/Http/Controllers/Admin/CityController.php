<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $data = City::with('state')->orderBy('title', 'asc')->paginate(10);

        return view('cities.index', compact('data'));
    }
}
