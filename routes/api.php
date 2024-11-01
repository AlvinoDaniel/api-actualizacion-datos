<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::group([
	'middleware' => 'api'
], function () {
   Route::group([
      'prefix'=>'auth'],function(){
        Route::post('login',[AuthController::class, 'login']);
        Route::post('/register', [UserController::class, 'store']);
        Route::get('/search/{cedula}', [UserController::class, 'searchWorker']);
        //  Route::post('/reset-password',[AuthController::class, 'sendResetPasswordEmail']);
         Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::get('/logout', [AuthController::class, 'logout']);
            // Route::get('/revoketoken', [AuthController::class, 'RevokeToken']);
            Route::post('/changepassword', [AuthController::class, 'changePassword']);
         });
   });
});
