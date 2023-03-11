<?php

namespace App\Service\Users;

use App\Models\Lojista;
use App\Models\User;
use Auth;

class UserServiceSing {

    function userLogin($request) {
        try{

            $request->validate([
                'cpf_cnpj' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);
            
            if (!Auth::attempt($request->only('cpf_cnpj', 'password'))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $user = User::where('cpf_cnpj', $request['cpf_cnpj'])->firstOrFail();

            if ($user->type == 'shopkeeper') {
                $lojista = Lojista::where('user_id', $user->id)->firstOrFail();
                $user->lojista = $lojista;
            }

            $token = $user->createToken('API TOKEN')->plainTextToken;
            unset($user->password, $user->created_at, $user->updated_at, $user->deleted_at);
            return response()->json([
                'message'      => 'Connected successfully.',
                'user'         => $user,
                'access_token' => $token]);
            
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return ["message" => "Login error, `$e`.", ];
        }
    }

    function userLogout($request) {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out.'
        ]);
    }
}