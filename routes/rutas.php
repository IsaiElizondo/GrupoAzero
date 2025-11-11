<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\RutasController;

Route::middleware(['auth', 'bloquear_rol_departamento'])->group(function(){

    //RUTAS GENERALES CRUD
    Route::resource('clientes', ClientesController::class);
    Route::resource('unidades', UnidadesController::class);
    Route::resource('rutas', RutasController::class);

    //RUTAS PARA EL MULTIPEDIDO DE RUTAS
    Route::get('rutas/multie', [RutasController::class, 'multie'])->name('rutas.multie');
    Route::post('rutas/multie/lista', [RutasController::class, 'multie_lista'])->name('rutas.multie_lista');
    Route::post('rutas/multie/set', [RutasController::class, 'set_multiruta'])->name('rutas.set_multiruta');

    //RUTAS PARA AGREGAR O CREAR UN PEDIDO CON CLIENTE
    Route::get('clientes/buscar', [App\Http\Controllers\ClientesController::class, 'buscar']);
    Route::get('clientes/{id}/direcciones', [App\Http\Controllers\ClientesController::class, 'direcciones']);
    Route::post('clientes/storeDireccion', [App\Http\Controllers\ClientesController::class, 'storeDireccion']);
    Route::post('clientes/storeAjax', [App\Http\Controllers\ClientesController::class, 'storeAjax']);



});