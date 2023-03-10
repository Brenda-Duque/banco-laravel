<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use App\Notifications\InvoicePaid;
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

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/transfer', [AccountController::class, 'transfer']);

Route::middleware('auth:sanctum')->post('/notification', [NotificationController::class, 'notification']);

Route::get('/invoice', [InvoicePaid::class, 'show']);

