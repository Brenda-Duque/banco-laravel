<?php

namespace App\Repository\User;

use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;
use Auth;
use DB;

class UserRepository {

    function createUser($request){
        try{

            DB::beginTransaction();

            $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'cpf_cnpj' => $request->cpf_cnpj,
                    'password' => bcrypt($request->password), //encrypt the password
                    'type'     => $request->type,
                ]);

            if ($request->type == "shopkeeper") {
                
                $lojista = Lojista::create([
                    'user_id'      => $user->id,
                    'company_name' => $request->company_name
                ]);
                $user->lojista = $lojista;
            }
                            
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

                                $token = $user->createToken('token')->plainTextToken;
                                unset($user->password);
        
            DB::commit();

            return response()->json([
                'message'      => 'Registration done successfully.', 
                'data'         => $user, 
                'account'      => $account, 
                'access_token' => $token
            ]);

        }catch (\Exception $e) {
            DB::rollback();
            $error = $e->getMessage();
            return ["message" => "Error when registering, `$e`."];
        }
    }

    function uptadeUser() {

    }

    function deleteUser() {
        
    }
}
