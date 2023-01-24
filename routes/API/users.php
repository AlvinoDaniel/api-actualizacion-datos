<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::group([
	'middleware' => 'api'
], function () {
	Route::group([
		'prefix'=>'users'],function(){
			Route::middleware(['auth:sanctum'])->group(function () {
				Route::get('/', [UserController::class, 'index']);
            Route::get('backup', [UserController::class, 'backupDownload']);
            Route::post('/', [UserController::class, 'store']);
            Route::post('/{id}/personal/{personal}', [UserController::class, 'update']);
            Route::post('/actualizar/{id}/contrasena', [UserController::class, 'update_password']);
            Route::get('/roles', [UserController::class, 'roles']);
            Route::get('/niveles', [UserController::class, 'nivel']);
            Route::get('/show/{id}', [UserController::class, 'show']);
            // Route::get('/{id}/cerrar', [UserController::class, 'cerrar_sesion']);
            Route::delete('/{id}', [UserController::class, 'delete']);
			});
		});
});
