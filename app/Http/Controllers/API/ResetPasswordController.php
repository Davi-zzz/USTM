<?php

namespace App\Http\Controllers\API;


use App\User;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação.', $validator->errors()->toArray(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return $this->sendError('Email não cadastrado.');

        $token = app('auth.password.broker')->createToken($user);

        DB::table(config('auth.passwords.users.table'))->insert([
            'email' => $user->email,
            'token' => $token
        ]);

        $user->notify(
            new ResetPassword($token)
        );


        return $this->sendResponse([], 'O link de resetar senha foi enviado no seu email.');
    }
}
