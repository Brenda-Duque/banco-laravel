<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class LoginUserController extends Controller
{
    function loginUser(Request $request) {

        // validação dos campos
        $request->validate([
            'cpf' => ['required'],
            'password' => ['required'],
        ]);
        
        // verifica o login
        if (!Auth::attempt($request->only('cpf', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // retorna os dados do usuário
        $user = User::where('cpf', $request['cpf'])->firstOrFail();

        //cria um novo token
        $token = $user->createToken('API TOKEN')->plainTextToken;

        unset($user->password);
        return response()->json(['user' => $user,'access_token' => $token]);
    }
}
