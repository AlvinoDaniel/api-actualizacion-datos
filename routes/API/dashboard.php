<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::group([
	'middleware' => 'api'
], function () {
	Route::group([
		'prefix'=>'dashboard'],function(){
			Route::middleware(['auth:sanctum'])->group(function () {
				Route::get('/', [DashboardController::class, 'index']);
            // Route::post('/crear', [UserController::class, 'store']);
            // Route::post('/actualizar/{id}', [UserController::class, 'update']);
            // Route::post('/actualizar/{id}/contrasena', [UserController::class, 'update_password']);
            // Route::get('/roles', [UserController::class, 'roles']);
            // // Route::get('/{id}/cerrar', [UserController::class, 'cerrar_sesion']);
            // Route::delete('/{id}', [UserController::class, 'delete']);
			});
		});
});