<?php

namespace App\Repository\User;

use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;
use Auth;
use DB;

class ConsultUserRepository {

    function getUserById($id) {
        $user = User::where('id', $id)->firstOrFail();
        return $user;
    }

    function getTypeShopkeeperById($id) {
        $getShopkeeper = Lojista::where('user_id', $id)->firstOrFail();
        return $getShopkeeper;
    }

    function getUserDocument($cpf_cnpj) {
        $getUser = User::where('cpf_cnpj', $cpf_cnpj)->firstOrFail();
        return $getUser;
    }

    function getUserLoggedAccount() {
        $loggedAccount = auth()->user()->account()->firstOrFail();
        return $loggedAccount;
    }
}