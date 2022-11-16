<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;



Route::group([
	'middleware'  => 'api',
  'prefix'      => 'documento'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(DocumentoController::class)->group(function () {
      Route::post('/enviar', 'store');
      Route::post('/crear-temporal', 'storeTemporal');
      Route::get('/{id}', 'show');
      Route::post('/actualizar/{id}', 'update');
      Route::post('/eliminar-anexo/{id}', 'destroyAnexo');
      Route::post('/agregar-anexo', 'addAnexo');
      Route::get('/descargar-anexo/{id}', 'downloadAnexo');
    });
  });
});
