<?php

namespace App\Http\Controllers\API;

use Exception;
use App\User;
use App\Person;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProfileController extends BaseController
{
    public function show()
    {
        $id = auth()->id();
        $data = User::with('people')->findOrFail($id);

        return $this->sendResponse($data);
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:F,J',
                'email' => 'required|max:89|email|unique:users,email,' .$request->user()->id,
                'nif' => 'required|max:20|unique:people,nif,'.$request->user()->person_id,
                'name' => 'required|string',
                'nickname' => 'nullable|string',
                'address' => 'required|string',
                'zip_code' => 'required|string',
                'city_id' => ['required', 'exists:cities,id'],
                'phone' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erro de Validação.', $validator->errors(), 422);
            }

            $user = User::find($request->user()->id);

            DB::transaction(function () use ($request, $user) {
                $inputs = $request->all();
                Person::updateOrCreate(['id' => $request->user()->person_id], $inputs);
                $user->fill($inputs)->save();
            });

            return $this->sendResponse([], "Perfil atualizado com sucesso.");
        } catch (Exception $e) {
            return $this->sendError("", $e->getMessage(), 500);
        }
    }
}
