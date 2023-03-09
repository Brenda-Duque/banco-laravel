<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use Auth;
use DB;

class AccountController extends Controller
{
    /**
     * @OA\Post(
     * path="/transfer",
     * summary="Transfer",
     * description="Transfer",
     * operationId="authTransfer",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="transfer between accounts, only regular type users",
     *    @OA\JsonContent(
     *       required={"Token", "value", "account"},
     *       @OA\Property(property="token", type="string", example="14|YbQD8XXY4zSIOtR27xPj9jrM8w1GKekuDNKBx7OD"),
     *       @OA\Property(property="value", type="float", example="5"),
     *       @OA\Property(property="account", type="string", example="3383585")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", 
     *                    type="string", 
     *                    example=
     *                      {
     *                        "status": "1",
     *                        "message": "Transfer successfully completed.",
     *                        "balance": 5
     *                      }
     *              )
     *        )
     *     )
     * )
     */
    function transfer(Request $request) {
       
        try{
            $request->validate([
                'value'            => ['required', 'numeric'],
                'account_transfer' => ['required', 'string']
            ]);
    
            $accountFrom = auth()->user()->account()->firstOrFail();
            $notification = auth()->user()->notification()->firstOrFail();
                
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

            if ($notification) {
                $msg = "You have just performed a transfer from `$request->value`";
                if ($notification->notification == 'email') {
                    mail($accountFrom->email, "Transaction",$msg);
                }
            }

            DB::commit();
            
            return response()->json([
                'status'       => '1',
                'message'      => 'Transfer successfully completed.',
                'balance'      => $accountFrom->balance
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
