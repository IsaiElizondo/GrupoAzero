<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\DevController;

Route::get('/dev/hola', [DevController::class, 'hola']);
Route::get('dev/respaldo', [DevController::class, 'respaldo']);
Route::get('dev/borra_archivos', [DevController::class, 'borra_archivos']);
Route::get('dev/borra_archivos2', [DevController::class, 'borra_archivos2']);

