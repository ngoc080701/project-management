<?php

use App\Http\Controllers\Controller;
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
Route::get('/', [Controller::class, "test"]);

Route::post('auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
Route::post('email/verification/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'logout'])
        ->name('verification.verify');
