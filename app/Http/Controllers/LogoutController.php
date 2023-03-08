<?php

namespace App\Http\Controllers;

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
    function logout(Request $request) {

        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out.'
        ]);
    }
}
