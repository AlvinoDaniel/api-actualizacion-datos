<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'departamentos'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DepartamentoController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('/', 'store');
    //   Route::get('/{id}', 'show');
      Route::post('/{id}', 'update');
      Route::delete('/{id}', 'destroy');
      Route::get('/list/redactar', 'departamentsWrite');
    });
  });
});

Route::group([
	'middleware'  => 'api',
  'prefix'      => 'nucleo'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DepartamentoController::class)->group(function () {
      Route::get('/', 'allNucleos');
      Route::get('/byDepartamentos', 'departamentsByNucleo');
    });
  });
});
