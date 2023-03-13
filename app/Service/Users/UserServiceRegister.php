<?php

namespace App\Service\Users;

use App\Repository\User\UserRepository;
use Auth;


class UserServiceRegister {

    protected $UserRepository;
 
 
    public function __construct(
       UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    function registerUser($request) {
        try {
            $user = $this->userRepository->createUser($request);
            return $user;
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return ["message" => "Error when registering, `$error`."];
        }
    }
}