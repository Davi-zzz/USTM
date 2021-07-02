<?php

namespace App\Http\Controllers\API;

use DB;
use Hash;
use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Person;
use App\User;
use App\Client;
use App\Broker;

class PeopleController extends BaseController
{

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'people_type' => 'nullable|string',
                'email' => 'required', 'unique|users',
                'password' => 'required|string',
                'name' => 'required|string',
                'nif' => 'nullable', 'unique|peoples', 'string', 'min:11',
                'zip_code' => 'nullable|string',
                'city_id' => 'nullable', 'string', 'exists|cities',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 206);
            }

            $request->password = Hash::make($request->password);

            $person = Person::create([
                'name' => $request->name,
                'email' => $request->email,
                'lastname' => $request->lastname,
                'nif' => $request->nif,
                'zip_code' => $request->zip_code,
                'city_id' => $request->city_id,
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'person_id' => $person->id,
            ]);

            $user->assignRole('user_app');

            return $this->sendResponse($person, "People created successfully", 201);
        } catch (Exception $e) {
            return $this->sendError("", $e->getMessage(), 500);
        }
    }

}
