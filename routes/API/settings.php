<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\PuebloController;
use App\Http\Controllers\ActividadController;


Route::group([
	'middleware' => 'api'
], function () {
	Route::group([
		'prefix'=>'setting'
  ],function(){
    Route::middleware(['auth:sanctum'])->group(function () {
      Route::post('/municipio/{id}', [MunicipioController::class, 'update']);
      Route::apiResource('municipio', MunicipioController::class)->except(['show','update']);

      Route::post('/parroquia/destroy_massive', [ParroquiaController::class, 'destroy_massive'])->name('parroquia.destroy_massive');
      Route::post('/parroquia/{id}', [ParroquiaController::class, 'update']);
      Route::apiResource('parroquia', ParroquiaController::class)->except(['show','update']);
      
      Route::post('/pueblo/destroy_massive', [PuebloController::class, 'destroy_massive'])->name('pueblo.destroy_massive');
      Route::post('/pueblo/{id}', [PuebloController::class, 'update']);
      Route::apiResource('pueblo', PuebloController::class)->except(['show','update']);

      Route::post('/actividad/destroy_massive', [ActividadController::class, 'destroy_massive'])->name('actividad.destroy_massive');
      Route::post('/actividad/{id}', [ActividadController::class, 'update']);
      Route::apiResource('actividad', ActividadController::class)->except(['show','update']);
    });
  });
});