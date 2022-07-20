<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'grupos'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(GrupoController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('/', 'store');
      // Route::post('/{id}', 'update');
      // Route::delete('/{id}', 'delete');
    });   
  });
});