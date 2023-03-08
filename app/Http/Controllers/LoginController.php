<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lojista;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    function login(Request $request) {
        try{
    
            $request->validate([
                'cpf_cnpj' => ['required', 'string'],
                'password' => ['required', 'string'],
            ], 400);
            
            if (!Auth::attempt($request->only('cpf_cnpj', 'password'))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $user = User::where('cpf_cnpj', $request['cpf_cnpj'])->firstOrFail();

            if ($user->type == 'shopkeeper') {
                $lojista = Lojista::where('user_id', $user->id)->firstOrFail();
                $user->lojista = $lojista;
            }

            $token = $user->createToken('API TOKEN')->plainTextToken;
    
            return response()->json([
                'message'      => 'Connected successfully.',
                'user'         => $user,
                'access_token' => $token]);
            
        } catch (\Exception $e) {
            return ["message" => "Login error,  `$e->message`."];
        }
    }
}
