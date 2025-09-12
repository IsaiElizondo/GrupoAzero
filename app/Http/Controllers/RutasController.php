<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta;
use App\Models\Cliente;
use App\Models\Unidad;
use App\Models\RutaPedido;

class RutasController extends Controller
{
    public function index(){

        $rutas = Ruta::with(['cliente', 'unidad', 'chofer', 'pedidos'])->get();
        return view('rutas.index', compact('rutas'));

    }

    public function create(){
        $clientes = Cliente::all();
        $unidades = Unidad::all();
        $choferes = User::where('deparment_id', 6)->get();
        return view('rutas.create', compact('clientes', 'unidades', 'choferes'));
    }

    public function store(Request $request){
        
        $request->validate([

            'cliente_id' => 'required|exists:clientes,id',
            'unidad_id' => 'required|exists:unidades,id',
            'chofer_id' => 'required|exists:users,id',
            'estatus_pago' => 'required|string',
            'estatus_entrega' => 'nullable|numeric',
            'motivo' => 'nullable|string',
            'pedidos' => 'required|array|min:1',
            'pedidos.*' => 'string',

        ]);

        $lastId = Ruta::max('id') +1;
        $numero_ruta = str_pad($lastId, 6, '0', STR_PAD_LEFT);

        $ruta = Ruta::create([

            'numero_ruta' => $numero_ruta,
            'cliente _id' => $request->cliente_id,
            'unidad_id' => $request->unidad_id,
            'chofer_id' => $request->chofer_id,
            'estaus_pago' => $request->estatus_pago,
            'monto_por_cobrar' => $request->monto_por_cobrar,
            'fecha_hora' => $request->fecha_hora,
            'estatus_entrega' => $request->estatus_entrega,
            'motivo' => $request->motivo,

        ]);

        foreach($request->pedidos as $invoice){
            RutaPedido::create([
                'ruta_id' => $ruta->id,
                'order_id' => $invoice,
            ]);
        }

        return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente');

    }

    public function show($id){

        $ruta = Ruta::with([
            'cliente',
            'unidad',
            'chofer',
            'orders'
        ])->findOrFail($id);

        return view('rutas.show', compact('ruta'));

    }

    public function edit($id){

        $ruta = Ruta::with('pedidos')->findOrFail($id);
        $clientes = Cliente::all();
        $unidades = Unidad::all();
        $choferes = User::where('department_id', 6)->get();

        return view('rutas.edit', compact('ruta', 'clientes', 'unidades', 'choferes'));

    }

    public function update(Request $request, $id){

        $ruta = Ruta::findOrFail($id);

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'unidad_id' => 'required|exists:unidades,id',
            'chofer_id' => 'required|exists:users,id',
            'estatus_pagp' => 'required|string',
            'monto_por_cobrar' => 'required|string',
            'fecha_hora' => 'required|numeric',
            'fecha_hora' => 'required|date',
            'estatus_entrega' => 'nullable|numeric',
            'motico' => 'nullable|string',
            'pedidos' => 'required|arra|min:1',
            'pedidos.*' => 'string',
        ]);

        RutaPedido::where('ruta_id', $ruta->id)->delete();
        foreach($request->pedidos as $invoice){
            RutaPedido::create([
                'ruta_id' => $ruta->id,
                'order_id' => $invoice,
            ]);
        }

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente');

    }


    public function destroy($id){
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();

        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente');
        
    }

}
