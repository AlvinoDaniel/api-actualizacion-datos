<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarpetaController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'carpetas'
], function () {

  Route::controller(CarpetaController::class)->group(function () {
    Route::get('/', 'index');
  });

  // Route::middleware(['auth:sanctum'])->group(function () {
  //   Route::post('/cultor/destroy_massive', [CultorController::class, 'destroy_massive'])->name('cultor.destroy_massive');
  //   Route::get('/cultor/cedula/{ced}', [CultorController::class, 'SearchCedula'])->name('cultor.cedula');
  //   Route::post('/cultor/{id}', [CultorController::class, 'update']);
  //   Route::apiResource('cultor', CultorController::class)->except(['update']);
  //   Route::get('/cultores/filtros', [CultorController::class, 'dataFiltros']);
  // });
});