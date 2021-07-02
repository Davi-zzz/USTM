<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends BaseController
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string|min:8'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->toArray(), 422);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken($user->email . '-' . now());

            $data = User::with('people')->find($user->id);

            return $this->sendResponse([
                'user' => $data,
                'token' => $token->accessToken
            ], "");
        } else {
            return $this->sendError('Email ou senha inválidos', [], 401);
        }
    }

    public function destroy(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 201);
    }
}
