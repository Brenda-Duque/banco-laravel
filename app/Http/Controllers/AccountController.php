<?php

namespace App\Http\Controllers;

use App\Service\Accounts\AccountServiceAction;
use Illuminate\Http\Request;
use Auth;


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

     protected $AccountServiceAction;
 
     public function __construct(
        AccountServiceAction $accountServiceAction
     )
     {
         $this->accountServiceAction = $accountServiceAction;
 
     }

    function transfer(Request $request) {
        $request->validate([
            'value'            => ['required', 'numeric'],
            'account_transfer' => ['required', 'string']
        ]);
        $data = $this->accountServiceAction->transfer($request);
        return $data;
    }
}
