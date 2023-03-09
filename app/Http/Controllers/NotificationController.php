<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;
use DB;

class NotificationController extends Controller
{
    /**
     * @OA\Post(
     * path="/notification",
     * summary="notification",
     * description="Notification setting",
     * operationId="authNotification",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass type notification",
     *    @OA\JsonContent(
     *       required={"token", "notification"},
     *       @OA\Property(property="token", type="string", example="2|aw47QBCpWZDy8lFumqk4aGbuNAb9rnBJBMApsGCL"),
     *       @OA\Property(property="notification", type="string", example="email")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", 
     *                    type="string", 
     *                    example=
     *                          {
     *                            "message": "Notification setting successfully saved.",
     *                            "data": {
     *                              "client_id": 1,
     *                              "notification": "email",
     *                              "updated_at": "2023-03-09T16:34:17.000000Z",
     *                              "created_at": "2023-03-09T16:34:17.000000Z",
     *                              "id": 2
     *                            }
     *                          }
     *              )
     *        )
     *     )
     * )
     */
    function notification(Request $request) {
        try{
            $request->validate([
                "notification" => ['required','string']
            ]);
            
            $account = auth()->user()->account()->firstOrFail();
            
            DB::beginTransaction();
            
            $notification = Notification::create([
                'client_id'    => $account->id,
                'notification' => $request->notification
            ]);

            DB::commit();

            return response()->json([
                'message'      => 'Notification setting successfully saved.', 
                'data'         => $notification
            ]);

        } catch (\Exception $e) {

            DB::rollback();
            $error = $e->getMessage();
            return ["message" => "Error saving notification settings, `$e`."];
        }
    }
}
