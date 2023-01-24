<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;



Route::group([
    'middleware'  => 'api',
    'prefix'      => 'personal'
], function () {

  Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(PersonalController::class)->group(function () {
      Route::get('/', 'index');
      Route::post('/', 'store');
      Route::post('/{id}', 'update');
      Route::delete('/{id}', 'destroy');
      Route::get('/search', 'search');
    });
  });
});
