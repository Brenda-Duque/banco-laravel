<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use Auth;
use DB;

class AccountController extends Controller
{
    function transfer(Request $request) {
       
        try{
            $request->validate([
                'value'            => ['required', 'numeric'],
                'account_transfer' => ['required', 'string']
            ], 400);
    
            $accountFrom = auth()->user()->account()->firstOrFail();
                
            if ($accountFrom->type == 'shopkeeper') return response()->json([
                'message' => 'This type of user is not allowed to perform transfers.'
            ], 402);
                
            if ($accountFrom->balance < $request->value) return response()->json([
                'message' => 'Insufficient funds.'
            ], 422);
    
            $accountTo = Account::where('account', $request['account_transfer'])->firstOrFail();
                
            DB::beginTransaction();
    
            $accountFrom->balance -= $request->value;
            $accountFrom->save();

            $accountTo->balance += $request->value;
            $accountTo->save();

            DB::commit();
    
            return response()->json([
                'status'       => '1',
                'message'      => 'Transfer successfully completed.',
                'balance'      => $accountFrom->balance,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            $error = $e->getMessage();
            return response()->json([
                'message' => "Error when transferring. `$error`"
            ],400);
        }
    }
}
