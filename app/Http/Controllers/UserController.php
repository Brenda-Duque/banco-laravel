<?php

namespace App\Http\Controllers;

use App\Service\Validations\identificationDocument;
use App\Service\Users\UserServiceRegister;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

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

     protected $identificationDocument;
     protected $UserServiceRegister;
 
     public function __construct(
        identificationDocument $identificationDocument,
        UserServiceRegister $userServiceRegister
     )
     {
        $this->identificationDocument = $identificationDocument;
         $this->userServiceRegister = $userServiceRegister;
     }

    function register(Request $request) {
        $request->validate([
            'type'     => ['required', 'string', 'in:common,shopkeeper'],
            'name'     => ['required', 'min:7', 'max:255', 'string'],
            'email'    => ['required', 'email', 'unique:users', 'string'],
            'cpf_cnpj' => ['required', 'unique:users', 'string'],
            'password' => ['required', 'min:8', 'max:32', 'string'],
        ]);

        if ($request->type == 'common') {
            if (!$this->identificationDocument->validateCPF($request->cpf_cnpj)) {
                return response()->json(['message'=>'Invalid CPF.'], 422);
            }
    
        } else if ($request->type == 'shopkeeper') {
            $request->validate([
                'company_name' => ['required', 'min:7', 'max:255', 'string']
            ]);
    
            if (!$this->identificationDocument->validateCNPJ($request->cpf_cnpj)) {
                return response()->json(['message'=>'Invalid cnpj.'], 422);
            }
        }
        $data = $this->userServiceRegister->registerUser($request);
        return $data;
   }
}
