<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    function register(Request $request) {
        try{
            $user = new User();
            
            if($request) $this->validateFields($request);
            return $request;
    
            /*$user = User::create([
                "name" => "Brenda", 
                "email" => "brenda@brenda.com",
                "cpf" => "d",
                "password" => "123456"]);
    
        */
        } catch(Exception $e){
            return $e;
        }
   }
   
   function validateFields($request) {
       $allUsers = User::all();
    if ($request->name == '' || $request->name == null) {
        throw new Exception('O campo name é obrigatório.');
    } else if ($request->email == '' || $request->email == null) {
        throw new Exception('O campo email é obrigatório.');
    } else if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido.');
    } else if ($request->cpf == '' || $request->cpf == null) {
        throw new Exception('O CPF é obrigatório.');
    } else if ($this->validaCPF == false) {
        throw new Exception('Insira um CPF válido.');
    } else if ($request->password == '' || $request->password == null) {
        throw new Exception('Insira uma senha.');
    } else if ($request->confirmaSenha == '' || $request->confirmaSenha == null) {
        throw new Exception('Confirme a senha.');
    } else if ($request->senha != $request->confirmaSenha) {
        throw new Exception('Senhas diferentes.');
    }
    foreach($allUsers as $key => $value) {
        if ($request->email == $value->email) {
            throw new Exception('Email já cadastrado.');
        } else if ($request->cpf == $value->emaicpfl) {
            throw new Exception('CPF já cadastrado.');
        }
    }
   }

   function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se há uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // calculo para validar o CPF
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
    function validarSenha($senha) {
        $regexDigito = '^(?=.*\d)$';
        $regexLetraMinuscula = `^(?=.*[a-z])$`;
        $regexLetraMaiscula = `^(?=.*[A-Z])$`;
        $regexCaracter = `^(?=.*[$*&@#])$`;
        $regexMinimo = `^[0-9a-zA-Z$*&@#]{8,}$`;

        if (!$regexDigito.$senha) {
            throw new Exception('A senha deve conter ao menos um dígito.');
        } else if (!$regexLetraMinuscula.$senha) {
            throw new Exception('A senha deve conter ao menos uma letra minúscula.');
        } else if (!$regexLetraMaiscula.$senha){
            throw new Exception('A senha deve conter ao menos uma letra maiuscula.');
        } else if (!$regexCaracter.$senha) {
            throw new Exception('A senha deve conter ao menos um caracter especial.');
        } else if (!$regexMinimo.$senha) {
            throw new Exception('A senha deve conter ao menos 8 caracteres.');
        } else {
            return true;
        }

    }
}
