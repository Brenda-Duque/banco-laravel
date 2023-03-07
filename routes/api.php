<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLojistaController;
use App\Http\Controllers\LoginUserController;
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

// create new common user
Route::post('/registerUser', [UserController::class, 'register']);

// create new lojista user
Route::post('/registerLojista', [UserLojistaController::class, 'registerLojista']);

// login user
Route::post('/loginUser', [LoginUserController::class, 'loginUser']);

// login lojista
Route::post('/loginLojista', [LoginLojistaController::class, 'loginLojista']);

// alterar usuário
Route::post('/updateUser', function() {

});

// deletar usuário
Route::post('/deleteUser', function() {

});