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
    /**
     * @OA\Post(
     * path="/register",
     * summary="Sign up",
     * description="Register user",
     * operationId="authRegister",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass your personal or business data",
     *    @OA\JsonContent(
     *       required={"type","name", "email", "cpf_cnpj", "password"},
     *       @OA\Property(property="type", type="string", example="common"),
     *       @OA\Property(property="nome", type="string", example="Brenda Duque"),
     *       @OA\Property(property="email", type="string", format="email", example="brenda@hotmail.com"),
     *       @OA\Property(property="cpf_cnpj", type="string", format="cpf", example="03754666061"),
     *       @OA\Property(property="password", type="string", format="password", example="testandoA@0")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", 
     *                    type="json", 
     *                    example=
     *                         {
     *                            "message": "Registration sdone successfully.",
     *                            "data": {
     *                              "name": "Teste com usuário 8",
     *                              "email": "testeuser00@gmail.com",
     *                              "cpf_cnpj": "92710459043",
     *                              "type": "common",
     *                              "updated_at": "2023-03-08T10:31:18.000000Z",
     *                              "created_at": "2023-03-08T10:31:18.000000Z",
     *                              "id": 5
     *                            },
     *                            "account": {
     *                              "client_id": 5,
     *                              "type": "common",
     *                              "agency": "0001",
     *                              "account": "8433981",
     *                              "updated_at": "2023-03-08T10:31:18.000000Z",
     *                              "created_at": "2023-03-08T10:31:18.000000Z",
     *                              "id": 5
     *                            },
     *                            "access_token": "7|srbcVwAECShMYCh9ClgqPfOFAz6GrptHZ0FQSoXs"
     *                          }
     *                 ),
     *        )
     *     )
     * )
     */
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
                return response()->json(['message'=>'Invalid CPF.'], 422);
            }

        } else if ($request->type == 'shopkeeper') {
            $request->validate([
                'company_name' => ['required', 'min:7', 'max:255', 'string']
            ]);

            if (!$this->validateCNPJ($request->cpf_cnpj)) {
                return response()->json(['message'=>'Invalid cnpj.'], 422);
            }
        }

        DB::beginTransaction();

        try {
        
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'cpf_cnpj' => $request->cpf_cnpj,
                'password' => bcrypt($request->password), //encrypt the password
                'type'     => $request->type,
            ]);

            // generates an account number and checks if it already exists
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
                    'company_name' => $request->company_name
                ]);

                $user->lojista = $lojista;
            }
                
            DB::commit();

            $token = $user->createToken('token')->plainTextToken;
            unset($user->password);
            return response()->json([
                'message'      => 'Registration done successfully.', 
                'data'         => $user, 
                'account'      => $account, 
                'access_token' => $token]);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            $error = $e->getMessage();
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
