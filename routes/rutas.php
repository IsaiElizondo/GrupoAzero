<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\RutasController;

Route::middleware(['auth', 'bloquear_rol_departamento'])->group(function(){

    Route::resource('clientes', ClientesController::class);
    Route::resource('unidades', UnidadesController::class);
    Route::resource('rutas', RutasController::class);


});