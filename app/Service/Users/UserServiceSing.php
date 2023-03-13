<?php

namespace App\Service\Users;

use App\Repository\User\ConsultUserRepository;
use App\Models\Lojista;
use App\Models\User;
use Auth;

class UserServiceSing {

    protected $ConsultUserRepository;
 
 
    public function __construct(
       ConsultUserRepository $consultUserRepository

    )
    {
        $this->consultUserRepository = $consultUserRepository;

    }

    function userLogin($request) {
        try{
            
            if (!Auth::attempt($request->only('cpf_cnpj', 'password'))) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $user = $this->consultUserRepository->getUserDocument($request['cpf_cnpj']);

            if ($user->type == 'shopkeeper') {
                $lojista = $this->consultUserRepository->getTypeShopkeeperId($user->id);
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
            return ["message" => "Login error, `$error`.", ];
        }
    }

    function userLogout($request) {
      try{
          Auth::user()->tokens()->delete();
  
          return response()->json([
              'message' => 'You have successfully logged out.'
          ]);
      } catch (\Exception $e) {
        $error = $e->getMessage();
        return ["message" => "Logout error, `$error`.", ];
      }
    }
}