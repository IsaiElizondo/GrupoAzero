<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\DireccionCliente;

class ClientesController extends Controller
{
    
    public function index(){
        $clientes = Cliente::with('direcciones')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create(){
        return view('clientes.create');
    }

    public function store(Request $request){

        $request->validate([

            'nombre' => 'required|string|max:255',
            'codigo_cliente' => 'required|string|max:20|unique:clientes',
            'celular' => 'nullable|string|max:20',

        ]);

        if($request->has('direcciones')){
            foreach($request->direcciones as $dir){
                DireccionCliente::create([

                    'cliente_id' => $cliente->id,
                    'direccion' => $dir['direccion'],
                    'ciudad' => $dir['ciudad'] ?? null,
                    'estado' => $dir['estado'] ?? null,
                    'codigo_postal' => $dir['codigo_postal'] ?? null,
                    'url_mapa' => $dir['url_mapa'] ?? null,
                    'instrucciones' => $dir['intrucciones'] ?? null,

                ]);
            }
        }

         return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente');

    }

    public function edit($id){
        $cliente = Cliente::with('direcciones')->findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id){

        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_cliente' => 'requiered|string|max:50|unique:clientes,codigo_cliente,' . $cliente->id,
            'celular' => 'nullable|string|max:20',

        ]);

        $cliente->update($request->only('nombre', 'codigo_cliente', 'celular'));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente');

    }

    public function destroy($id){
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
    }

}
