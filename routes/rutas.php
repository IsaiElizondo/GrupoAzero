<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\RutasController;
use App\Http\Controllers\SepomexController;

Route::middleware(['auth', 'bloquear_rol_departamento'])->group(function(){

    //dd('RUTAS CARGADAS');


    //RUTAS PARA AGREGAR O CREAR UN PEDIDO CON CLIENTE
    Route::get('clientes/buscar', [ClientesController::class, 'buscar']);
    Route::get('clientes/{id}/direcciones', [ClientesController::class, 'direcciones']);
    Route::post('clientes/storeDireccion', [ClientesController::class, 'storeDireccion']);
    Route::post('clientes/storeAjax', [ClientesController::class, 'storeAjax']);

    //RUTAS GENERALES CRUD
    Route::resource('clientes', ClientesController::class);
    Route::resource('unidades', UnidadesController::class);
    Route::resource('rutas', RutasController::class);

    //RUTAS PARA EL MULTIPEDIDO DE RUTAS
    Route::get('rutas/multie', [RutasController::class, 'multie'])->name('rutas.multie');
    Route::post('rutas/multie/lista', [RutasController::class, 'multie_lista'])->name('rutas.multie_lista');
    Route::post('rutas/multie/set', [RutasController::class, 'set_multiruta'])->name('rutas.set_multiruta');
    Route::post('rutas/pedido/pago', [RutasController::class, 'updatePago'])->name('rutas.pedido.pago');

});
                