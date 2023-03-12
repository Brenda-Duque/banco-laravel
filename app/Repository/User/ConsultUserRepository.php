<?php

namespace App\Repository\User;

use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;
use Auth;
use DB;

class ConsultUserRepository {

    function getUserId($id) {
        $userId = User::where('id', $id)->firstOrFail();
        return $userId;
    }

    function getTypeShopkeeperId($id) {
        $getShopkeeper = Lojista::where('user_id', $id)->firstOrFail();
        return $getShopkeeper;
    }

    function getUserDocument($cpf_cnpj) {
        $getUser = User::where('cpf_cnpj', $cpf_cnpj)->firstOrFail();
        return $getUser;
    }

    function getUserLogged() {
        $logged = auth()->user()->account()->firstOrFail();
        return $logged;
    }
}