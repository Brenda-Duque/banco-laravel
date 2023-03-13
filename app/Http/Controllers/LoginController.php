<?php

namespace App\Http\Controllers;

use App\Service\Users\UserServiceSing;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign in",
     * description="Login by cpf/cnpj, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"cpf/cnpj","password"},
     *       @OA\Property(property="cpf/cnpj", type="string", format="cpf/cnpj", example="29713750055"),
     *       @OA\Property(property="password", type="string", format="password", example="testandoA@0")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", 
     *                    type="string", 
     *                    example=
     *                      {
     *                        "message": "Connected successfully.",
     *                        "user": {
     *                          "id": 2,
     *                          "name": "Teste com usuário 7",
     *                          "email": "testeuser7@gmail.com",
     *                          "cpf_cnpj": "23415352080",
     *                          "type": "common"
     *                        },
     *                        "access_token": "11|n7KGUoRPMbekio2MePI6E9uBuJzAZEL2d3OyMYja"
     *                      }
     *              )
     *        )
     *     )
     * )
     */

     protected $UserServiceSing;
 
     public function __construct(
        UserServiceSing $userServiceSing
     )
     {
         $this->userServiceSing = $userServiceSing;
     }

    function login(Request $request) {
        $request->validate([
            'cpf_cnpj' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $data = $this->userServiceSing->userLogin($request);
        return $data;
    }
}
