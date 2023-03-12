<?php

namespace App\Repository\Account;

use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;
use Auth;
use DB;

class ConsultAccount {

    function getDataAccount ($account) {
        $account = Account::where('account', $account)->firstOrFail();
        return $account;
    }
}