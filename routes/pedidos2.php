<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pedidos2Controller;
Route::middleware('auth')->group(function () {

    Route::get('pedidos2/', [Pedidos2Controller::class, 'index']);
	Route::get('pedidos2/index', [Pedidos2Controller::class, 'index']);
	Route::get('pedidos2/lista', [Pedidos2Controller::class, 'lista']);

	Route::get('pedidos2/nuevo', [Pedidos2Controller::class, 'nuevo']);
	Route::post('pedidos2/guardar/{id}', [Pedidos2Controller::class, 'guardar']);
	Route::post('pedidos2/crear', [Pedidos2Controller::class, 'crear']);

	Route::get('pedidos2/pedido/{id}', [Pedidos2Controller::class, 'pedido']);
	Route::get('pedidos2/masinfo/{id}', [Pedidos2Controller::class, 'masinfo']);
	Route::get('pedidos2/historial/{id}', [Pedidos2Controller::class, 'historial']);
	Route::get('pedidos2/fragmento/{id}/{cual}', [Pedidos2Controller::class, 'fragmento']);

	Route::get('pedidos2/entregar_edit/{id}', [Pedidos2Controller::class, 'entregar_edit']);
	Route::get('pedidos2/set_accion_entregar/{id}', [Pedidos2Controller::class, 'set_accion_entregar']);
	Route::get('pedidos2/set_parcial_status/{id}', [Pedidos2Controller::class, 'set_parcial_status']);
	Route::get('pedidos2/unset_entregado/{id}', [Pedidos2Controller::class, 'unset_entregado']);

	Route::get('pedidos2/parcial_nuevo/{id]', [Pedidos2Controller::class, 'parcial_nuevo']);
	Route::post('pedidos2/parcial_crear/{id}', [Pedidos2Controller::class, 'parcial_crear']);
	Route::get('pedidos2/parcial_edit/{id}', [Pedidos2Controller::class, 'parcial_edit']);
	Route::post('pedidos2/parcial_update/{id}', [Pedidos2Controller::class, 'parcial_update']);
	Route::get('pedidos2/parcial_lista/{id}', [Pedidos2Controller::class, 'parcial_lista']);

	Route ::get('pedidos2/subproceso_nuevo/{id}', [Pedidos2Controller::class, 'subproceso_nuevo']);
	Route::post('pedidos2/smaterial_crear/{id}', [Pedidos2Controller::class, 'smaterial_crear']);
	Route::get('pedidos2/smaterial_edit/{id}', [Pedidos2Controller::class, 'smaterial_edit']);
	Route::post('pedidos2/smaterial_update/{id}', [Pedidos2Controller::class, 'smaterial_update']);
	Route::get('pedidos2/smaterial_lista/{id}', [Pedidos2Controller::class, 'smaterial_lista']);
	Route::get('pedidos2/set_smaterial_status/{id}', [Pedidos2Controller::class, 'set_smaterial_status']);

	Route::post('pedidos2/ordenf_crear/{id}', [Pedidos2Controller::class, 'ordenf_crear']);
	Route::get('pedidos2/ordenf_edit/{id}', [Pedidos2Controller::class, 'ordenf_edit']);
	Route::post('pedidos2/ordenf_update/{id}', [Pedidos2Controller::class, 'ordenf_update']);
	Route::get('pedidos2/ordenf_lista/{id}', [Pedidos2Controller::class, 'ordenf_lista']);

	Route::post('pedidos2/requisicion_crear/{id}', [Pedidos2Controller::class, 'requisicion_crear']);
	Route::get('pedidos2/requisicion_edit/{id}', [Pedidos2Controller::class, 'requisicion_edit']);
	Route::post('pedidos2/requisicion_update/{id}', [Pedidos2Controller::class, 'requisicion_update']);
	Route::get('pedidos2/requisicion_lista/{id}', [Pedidos2Controller::class, 'requisicion_lista']);

	Route::get('pedidos2/devolucion_lista/{id}', [Pedidos2Controller::class, 'devolucion_lista']);
	Route::get('pedidos2/devolucion_edit/{id}', [Pedidos2Controller::class, 'devolucion_edit']);
	Route::post('pedidos2/devolucion_update/{id}', [Pedidos2Controller::class, 'devolucion_update']);
	Route::get('pedidos2/devolucion_nuevo/{order_id}', [Pedidos2Controller::class, 'devolucion_nuevo']);
	Route::post('pedidos2/devolucion_crear/{id}', [Pedidos2Controller::class, 'devolucion_crear']);

	Route::get('pedidos2/refacturacion_lista/{id}', [Pedidos2Controller::class, 'refacturacion_lista']);
	Route::get('pedidos2/refacturacion_edit/{id}', [Pedidos2Controller::class, 'refacturacion_edit']);
	Route::post('pedidos2/refacturacion_update/{id}', [Pedidos2Controller::class, 'refacturacion_update']);
	Route::get('pedidos2/refacturacion_nuevo/{order_id}', [Pedidos2Controller::class, 'refacturacion_nuevo']);
	Route::post('pedidos2/refacturacion_crear/{id}', [Pedidos2Controller::class, 'refacturacion_crear']);

	Route::get('pedidos2/stockreq_edit/{id}', [Pedidos2Controller::class, 'stockreq_edit']);
	Route::post('pedidos2/stockreq_update/{id}', [Pedidos2Controller::class, 'stockreq_update']);
	Route::post('pedidos2/set_accion_surters/{id}', [Pedidos2Controller::class, 'set_accion_surters']);
	
	Route::get('pedidos2/shipment_edit/{id}', [Pedidos2Controller::class, 'shipment_edit']);
	Route::post('pedidos2/shipment_update/{id}', [Pedidos2Controller::class, 'shipment_update']);

	Route::get('pedidos2/accion/{id}', [Pedidos2Controller::class, 'accion']);
	Route::post('pedidos2/set_accion/{id}', [Pedidos2Controller::class, 'set_accion']);

	Route::get('pedidos2/attachlist', [Pedidos2Controller::class, 'attachlist']);
	Route::post('pedidos2/attachpost', [Pedidos2Controller::class, 'attachpost']);
	Route::get('pedidos2/attachdelete', [Pedidos2Controller::class, 'attachdelete']);

	Route::get('pedidos2/cancelar/{id}', [Pedidos2Controller::class, 'cancelar']);
	Route::get('pedidos2/descancelar/{id}', [Pedidos2Controller::class, 'descancelar']);

	Route::get('pedidos2/multie', [Pedidos2Controller::class, 'multie']);
	Route::get('pedidos2/multie_lista', [Pedidos2Controller::class, 'multie_lista']);
	Route::get('pedidos2/set_status/{id}', [Pedidos2Controller::class, 'set_status']);
	Route::post('pedidos2/set_multistatus', [Pedidos2Controller::class, 'set_multistatus']);
	Route::post('pedidos2/set_accion_desauditoria/{id}', [Pedidos2Controller::class, 'set_accion_desauditoria']);
	Route::post('pedidos2/add_nota/{id}', [Pedidos2Controller::class, 'add_nota']);
	
	Route::post('pedidos2/set-acccion_audita/{id}', [Pedidos2Controller::class, 'set_accion_audita']); 
	
	Route::post('pedidos2/add_nota/{id}', [Pedidos2Controller::class]);

	Route::get('pedidos2/set_follow/{id_pedido}/{id_usuarios}', [Pedidos2Controller::class, 'setclass_follow']);
	Route::get('pedidos2/set_followno/{id_pedido}/{id_usuario}', [Pedidos2Controller::class, 'set_followno']);

	Route::get('pedidos2/smaterial2/smaterial_desestatus/{id}/{status_id}', [Pedidos2Controller::class]);
	Route::get('pedidos2/parcial_desestatus/{id}/{status_id}', [Pedidos2Controller::class, 'parcial_desestatus']);
	Route::get('pedidos2/ordenf_desestatus/{id}/{status_id}', [Pedidos2Controller::class]);

	Route::post('/pedido/{id}/etiquetas', [Pedidos2Controller::class, 'guardarEtiquetas'])->name('pedido.etiquetas.guardar');

	//CRUD ETIQUETAS
	Route::middleware(['auth'])->group(function () {
		Route::get('/etiquetas', [Pedidos2Controller::class, 'indexEtiquetas'])->name('etiquetas.index');
		Route::get('/etiquetas/create', [Pedidos2Controller::class, 'createEtiqueta'])->name('etiquetas.create');
		Route::post('/etiquetas/store', [Pedidos2Controller::class, 'storeEtiqueta'])->name('etiquetas.store');
		Route::get('/etiquetas/edit/{id}', [Pedidos2Controller::class, 'editEtiqueta'])->name('etiquetas.edit');
		Route::post('/etiquetas/update/{id}', [Pedidos2Controller::class, 'updateEtiqueta'])->name('etiquetas.update');
		Route::post('/etiquetas/{id}/delete', [Pedidos2Controller::class, 'deleteEtiqueta'])->name('etiquetas.delete');
	});


});
	
