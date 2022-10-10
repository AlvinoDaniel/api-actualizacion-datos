<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfiguracionController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'configuracion'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(ConfiguracionController::class)->group(function () {
      Route::post('/firma', 'createFirma');
    //   Route::post('/', 'store');
    //   Route::get('/{id}', 'show');
    //   Route::post('/{id}', 'update');
    //   Route::post('/departamento/{id}', 'addDepartamento');
    //   Route::delete('/{id}', 'destroy');
    //   Route::delete('/departamento/{id}', 'destroyDepartamento');
    });
  });
});
