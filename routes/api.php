<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
         Route::post('login/{conexion}',[AuthController::class, 'login']);        
         Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::get('/logout', [AuthController::class, 'logout']);
            // Route::get('/revoketoken', [AuthController::class, 'RevokeToken']);
            Route::post('/changepassword', [AuthController::class, 'changePassword']);
         });
   });
});