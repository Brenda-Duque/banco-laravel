<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lojista;
use Auth;

class LoginLojistaController extends Controller
{
    function loginLojista(Request $request) {

        // validação dos campos
        $request->validate([
            'cnpj' => ['required'],
            'password' => ['required'],
        ]);
        
        $credentials = ['cnpj' => $request->cnpj, 'password' => $request->password];
        
        // retorna os dados do usuário
        $lojista = Lojista::where('cnpj', $request['cnpj'])->firstOrFail();

         if (Auth::guard('api_lojista_web')->attempt($credentials, false, false)) {
                //cria um novo token
                $token = $lojista->createToken('API TOKEN')->plainTextToken;

                return response()->json(['lojista' => $lojista,'access_token' => $token]);

         } else{
                return response()->json(['message' => 'Unauthorized'], 401);
         }
    }
}
