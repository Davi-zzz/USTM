<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Person;
use Illuminate\Http\Request;

class GetPersonByNifController extends BaseController
{
    public function __invoke(Request $request)
    {
        $nif = $request->nif;

        $person = Person::with('city.state')
            ->where('nif', $nif)
            ->first();

        return $this->sendResponse($person);
    }
}
