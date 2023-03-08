<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// create new user
Route::post('/register', [UserController::class, 'register']);

// login user
Route::post('/login', [LoginController::class, 'login']);

// logout 
Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

// transfer
Route::middleware('auth:sanctum')->post('/transfer', [AccountController::class, 'transfer']);

