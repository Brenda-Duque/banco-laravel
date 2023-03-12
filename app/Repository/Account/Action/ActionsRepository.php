<?php

namespace App\Repository\Account\Action;

use App\Models\Transfer;
use App\Models\Account;
use App\Models\Lojista;
use App\Models\User;
use Auth;
use DB;

class ActionsRepository {

    function transfer($accountFrom, $accountTo, $value) {
        try {
            
            DB::beginTransaction();
            
            $accountFrom->balance -= $value;
            $accountFrom->save();

            $accountTo->balance += $value;
            $accountTo->save();
            
            DB::commit();

            $extract = $this->extract($accountFrom, $accountTo, $value);
                
            return response()->json([
                'status'       => '1',
                'message'      => 'Transfer successfully completed.',
                'balance'      => $accountFrom->balance
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    function extract($accountFrom, $accountTo, $value) {
        try{
    
            DB::beginTransaction();
            
            $insertExtract = Transfer::create([
                'user_payer_id' => $accountFrom->client_id,
                'user_payee_id' => $accountTo->client_id,
                'value'         => $value
                
            ]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    function deposit () {

    }
}