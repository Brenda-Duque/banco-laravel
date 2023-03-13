<?php

namespace App\Service\Accounts;

use App\Repository\Notification\NotificationRepository;
use App\Repository\Account\Action\ActionsRepository;
use App\Repository\User\ConsultUserRepository;
use App\Repository\Account\ConsultAccount;
use App\Service\Accounts\AccountQuery;
use App\Notifications\InvoicePaid;
use App\Models\Account;
use App\Models\User;
use Auth;
use DB;

class AccountServiceAction {

    protected $NotificationRepository;
    protected $ConsultUserRepository;
    protected $ActionsRepository;
    protected $ConsultAccount;
 
 
    public function __construct(
        NotificationRepository $notificationRepository,
       ConsultUserRepository $consultUserRepository,
       ActionsRepository $actionsRepository,
       ConsultAccount $consultAccount

    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->consultUserRepository = $consultUserRepository;
        $this->actionsRepository = $actionsRepository;
        $this->consultAccount = $consultAccount;

    }

    function transfer($request) {

        try{
            // payer
            $accountFrom = $this->consultUserRepository->getUserLogged();
                
            if ($accountFrom->type == 'shopkeeper') return response()->json([
                'message' => 'This type of user is not allowed to perform transfers.'
            ], 402);
                
            if ($accountFrom->balance < $request->value) return response()->json([
                'message' => 'Insufficient funds.'
            ], 422);
    
            // payee
            $accountTo = $this->consultAccount->getDataAccount($request->account_transfer);

            $transfer = $this->actionsRepository->transfer($accountFrom, $accountTo, $request->value);
    
            $user = $this->consultUserRepository->getUserId($accountFrom->client_id);
            $user->value = $request->value;

            $this->notificationRepository->notificationTransferEmail($user);
            
            return $transfer;
           
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'message' => "Error when transferring. `$error`"
            ],400);
        }
    }
}
    