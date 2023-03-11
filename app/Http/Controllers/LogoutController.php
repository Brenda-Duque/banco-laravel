<?php

namespace App\Http\Controllers;

use App\Service\Users\UserServiceSing;
use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     * path="/logout",
     * summary="Logout",
     * description="Logout",
     * operationId="authLogout",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass token",
     *    @OA\JsonContent(
     *       required={"token"},
     *       @OA\Property(property="token", type="string", example="11|n7KGUoRPMbekio2MePI6E9uBuJzAZEL2d3OyMYja")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", 
     *                    type="string", 
     *                    example=
     *                         {
     *                           "message": "You have successfully logged out."
     *                         }
     *              )
     *        )
     *     )
     * )
     */

     protected $UserServiceSing;
 
 
     public function __construct(
        UserServiceSing $userServiceSing
 
     )
     {
         $this->userServiceSing = $userServiceSing;
 
     }

    function logout(Request $request) {
        $data = $this->userServiceSing->userLogout($request);

        return $data;

    }
}
