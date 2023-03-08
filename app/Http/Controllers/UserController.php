<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lojista;
use App\Models\Account;
use App\Models\User;
use Auth;
use DB;

class UserController extends Controller
{
    function register(Request $request) {
        $request->validate([
            'type'     => ['required', 'string', 'in:common,shopkeeper'],
            'name'     => ['required', 'min:7', 'max:255', 'string'],
            'email'    => ['required', 'email', 'unique:users', 'string'],
            'cpf_cnpj' => ['required', 'unique:users', 'string'],
            'password' => ['required', 'min:8', 'max:32', 'string'],
        ]);

        if ($request->type == 'common') {
            if (!$this->validateCPF($request->cpf_cnpj)) {
                return response()->json(['message'=>'Invalid CPF.'], 400);
            }
        } else if ($request->type == 'shopkeeper') {
            $request->validate([
                'company_name' => ['required', 'min:7', 'max:255', 'string'],
                'trading_name' => ['required', 'min:7', 'max:255', 'string'],
            ]);

            if (!$this->validateCNPJ($request->cpf_cnpj)) {
                return response()->json(['message'=>'Invalid cnpj.'], 400);
            }
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'cpf_cnpj' => $request->cpf_cnpj,
                'password' => bcrypt($request->password),
                'type'     => $request->type,
            ]);

            do {    
                $number = rand(1, 9999999);
                $number_account = str_pad($number, 4, 0, STR_PAD_LEFT);
            } while (Account::where('account', $number_account)->count() > 0);

            $account = Account::create([
                'client_id' => $user->id,
                'type'      => $request->type,
                'agency'    => '0001',
                'account'   => $number_account,
            ]);

            if ($request->type == "shopkeeper") {
                $lojista = Lojista::create([
                    'user_id'      => $user->id,
                    'company_name' => $request->company_name,
                    'trading_name' => $request->trading_name,
                ]);

                $user->lojista = $lojista;
            }
                
            DB::commit();

            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'message'      => 'Registration done successfully.', 
                'data'         => $user, 
                'account'      => $account, 
                'access_token' => $token]);
        
        } catch (\Exception $e) {
            
            DB::rollback();

            return ["message" => "Error when registering, `$e`."];
        }
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
