<?php

namespace App\Http\Controllers\Admin;

use App\State;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $data = State::orderBy('title', 'asc')->paginate(10);

        return view('states.index', compact('data'));
    }
}
