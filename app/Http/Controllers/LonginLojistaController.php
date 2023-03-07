<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lojista;
use Auth;

class LonginLojistaController extends Controller
{
    function loginLojista(Request $request) {

        // validação dos campos
        $request->validate([
            'cnpj' => ['required'],
            'password' => ['required'],
        ]);
        
        // verifica o login
        if (!Auth::attempt($request->only('cnpj', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // retorna os dados do usuário
        $lojista = Lojista::where('cnpj', $request['cnpj'])->firstOrFail();

        //cria um novo token
        $token = $lojista->createToken('API TOKEN')->plainTextToken;

        return response()->json(['lojista' => $lojista,'access_token' => $token]);
    }
}
