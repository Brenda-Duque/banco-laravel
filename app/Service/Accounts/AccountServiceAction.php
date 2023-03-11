<?php

namespace App\Service\Accounts;

use App\Service\Accounts\AccountQuery;
use App\Models\Account;
use App\Models\User;
use Auth;
use DB;

class AccountServiceAction {

    protected $AccountQuery;
 
 
    public function __construct(
       AccountQuery $accountQuery

    )
    {
        $this->accountQuery = $accountQuery;

    }

    function transfer($request) {

        try{
            $request->validate([
                'value'            => ['required', 'numeric'],
                'account_transfer' => ['required', 'string']
            ]);
    
            $accountFrom = auth()->user()->account()->firstOrFail();
                
            if ($accountFrom->type == 'shopkeeper') return response()->json([
                'message' => 'This type of user is not allowed to perform transfers.'
            ], 402);
                
            if ($accountFrom->balance < $request->value) return response()->json([
                'message' => 'Insufficient funds.'
            ], 422);
    
            $accountTo = Account::where('account', $request->account_transfer)->firstOrFail();

            $transfer = $this->accountQuery->transfer($accountFrom, $accountTo, $request->value);
                
            return $transfer;
           
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => "Error when transferring. `$e`"
            ],400);
        }
    }
}
    