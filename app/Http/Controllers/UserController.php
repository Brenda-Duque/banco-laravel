<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    function register(Request $request) {

        $request->validate([
            'name' => ['required', 'min:7', 'max:255', string],
            'email' => ['required', 'email', 'unique:users', string],
            'cpf' => ['required', 'unique:users', string],
            'password' => ['required', 'min:8', 'max:32', string],
        ]);

        // Validate CPF
        if (!$this->validateCPF($request->cpf)) {
            return response()->json(['message'=>'Invalid CPF.'], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => bcrypt($request->password),
            'status' => 1
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['data' => $user,'access_token' => $token]);
   }

   function validateCPF($cpf) {
        // Extract only the numbers
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        
        // Checks if all digits were entered correctly
        if (strlen($cpf) != 11) {
            return false;
        }

        // Checks for a sequence of repeating digits. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // calculation to validate the CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
