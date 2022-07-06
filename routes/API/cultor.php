<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CultorController;


Route::group([
	'middleware' => 'api'
], function () {
  Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/cultor/destroy_massive', [CultorController::class, 'destroy_massive'])->name('cultor.destroy_massive');
    Route::get('/cultor/cedula/{ced}', [CultorController::class, 'SearchCedula'])->name('cultor.cedula');
    Route::post('/cultor/{id}', [CultorController::class, 'update']);
    Route::apiResource('cultor', CultorController::class)->except(['update']);
    Route::get('/cultores/filtros', [CultorController::class, 'dataFiltros']);

    // Route::post('/parroquia/{id}', [ParroquiaController::class, 'update']);
    // Route::apiResource('parroquia', ParroquiaController::class)->except(['show','update']);
    
    // Route::post('/pueblo/{id}', [PuebloController::class, 'update']);
    // Route::apiResource('pueblo', PuebloController::class)->except(['show','update']);

    // Route::post('/actividad/{id}', [ActividadController::class, 'update']);
    // Route::apiResource('actividad', ActividadController::class)->except(['show','update']);
  });
});