<?php

namespace App\Service\Users;

use App\Service\Validations\identificationDocument;
use App\Service\Users\UserQuery;
use Auth;


class UserServiceRegister {

    protected $UserQuery;
    protected $identificationDocument;
 
 
    public function __construct(
       UserQuery $userQuery,
       identificationDocument $identificationDocument

    )
    {
        $this->userQuery = $userQuery;
        $this->identificationDocument = $identificationDocument;

    }

    function registerUser($request) {

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
    
        try {
            
            $user = $this->userQuery->createUser($request);

            return $user;
        
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return ["message" => "Error when registering, `$e`."];
        }
    }
}