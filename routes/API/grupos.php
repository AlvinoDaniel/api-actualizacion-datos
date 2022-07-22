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
      Route::get('/{id}', 'show');
      Route::post('/{id}', 'update');
      Route::post('/departamento/{id}', 'addDepartamento');
      Route::delete('/{id}', 'destroy');
      Route::delete('/departamento/{id}', 'destroyDepartamento');
    });   
  });
});