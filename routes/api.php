<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// criar novo usuário
Route::post('/register', [UserController::class, 'register']);

// fazer login
Route::post('/login', [LoginController::class, 'login']);

// alterar usuário
Route::post('/updateUser', function() {

});

// deletar usuário
Route::post('/deleteUser', function() {

});