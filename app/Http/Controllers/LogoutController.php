<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    function logout(Request $request) {

        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out.'
        ]);
    }
}
