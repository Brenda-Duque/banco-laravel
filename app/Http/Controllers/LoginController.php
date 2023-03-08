<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lojista;
use App\Models\User;
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
    function login(Request $request) {
        try{
            // return $request;
            $request->validate([
                'cpf_cnpj' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);
            
            if (!Auth::attempt($request->only('cpf_cnpj', 'password'))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $user = User::where('cpf_cnpj', $request['cpf_cnpj'])->firstOrFail();

            if ($user->type == 'shopkeeper') {
                $lojista = Lojista::where('user_id', $user->id)->firstOrFail();
                $user->lojista = $lojista;
            }

            $token = $user->createToken('API TOKEN')->plainTextToken;
            unset($user->password, $user->created_at, $user->updated_at, $user->deleted_at);
            return response()->json([
                'message'      => 'Connected successfully.',
                'user'         => $user,
                'access_token' => $token]);
            
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return ["message" => "Login error, `$e`.", ];
        }
    }
}
