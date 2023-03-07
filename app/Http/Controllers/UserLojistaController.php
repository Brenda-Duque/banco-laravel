<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lojista;
use Auth;

class UserLojistaController extends Controller
{
    function registerLojista(Request $request) {

        $request->validate([
            'name' => ['required', 'min:7', 'max:255', string],
            'email' => ['required', 'email', 'unique:lojistas', string],
            'cnpj' => ['required', 'unique:lojistas', string],
            'password' => ['required', 'min:8', 'max:32', string],
        ]);

        // Validate CNPJ
        if (!$this->validateCNPJ($request->cnpj)) {
            return response()->json(['message'=>'Invalid cnpj.'], 400);
        }

        $userLojista = Lojista::create([
            'name' => $request->name,
            'email' => $request->email,
            'cnpj' => $request->cnpj,
            'password' => bcrypt($request->password),
        ]);

        $token = $userLojista->createToken('token')->plainTextToken;

        return response()->json(['data' => $userLojista,'access_token' => $token]);
   }

   function validateCNPJ($cnpj) {
      
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
        // Validate size
        if (strlen($cnpj) != 14)
            return false;

        // Checks if all digits are the same
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Validates first check digit
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Validate second check digit
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
