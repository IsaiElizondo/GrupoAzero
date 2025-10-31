<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\DireccionCliente;
use Throwable;

class ClientesController extends Controller
{
    
    public function index(){

        $clientes = Cliente::with('direcciones')->get();
        return view('clientes.index', compact('clientes'));
    
    }


    public function create(){
        $activePage = 'clientes';
        $titlePage = 'Registrar nueva etiqueta';
        return view('clientes.create', compact('activePage', 'titlePage'));
    }

    
    public function store(Request $request){

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo_cliente' => ['nullable', 'string', 'max:50'],
            'nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:30'],
            'celular' => ['nullable', 'string', 'max:20'],
            'nombre_recibe' => ['nullable', 'string', 'max:50'],
            'url_mapa' => ['nullable', 'string', 'max:500'],
            'instrucciones' => ['nullable', 'string', 'max:500'],
            'direcciones' => ['nullable', 'array'],
            'direcciones.*.nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direcciones.*.direccion' => ['required_with:direcciones', 'string', 'max:255'],
            'direcciones.*.ciudad' => ['nullable', 'string', 'max:100'],
            'direcciones.*.estado' => ['nullable', 'string', 'max:100'],
            'direcciones.*.codigo_postal' => ['nullable', 'string', 'max:100'],
            'direcciones.*.celular' => ['nullable', 'string', 'max:20'],
            'direcciones.*.nombre_recibe' => ['nullable', 'string', 'max:50'],
            'direcciones.*.url_mapa' => ['nullable', 'string', 'max:500'],
            'direcciones.*.instrucciones' => ['nullable', 'string', 'max:500']
        ]);

        DB::beginTransaction();
            try{
                $cliente = Cliente::create([
                    'nombre' => $validated['nombre'],
                    'codigo_cliente' => $validated['codigo_cliente'] ?? null,
                ]);

                if(!empty($validated['direcciones']) && is_array($validated['direcciones'])){
                    foreach($validated['direcciones'] as $dir){
                        DireccionCliente::create([
                            'cliente_id' => $cliente->id,
                            'nombre_direccion' => $dir['nombre_direccion'],
                            'direccion' => $dir['direccion'] ?? null,
                            'ciudad' => $dir['ciudad'] ?? null,
                            'estado' => $dir['estado'] ?? null,
                            'codigo_postal' => $dir['codigo_postal'] ?? null,
                            'celular' => $dir['celular'] ?? null,
                            'nombre_recibe' => $dir['nombre_recibe'] ?? null,
                            'url_mapa' => $dir['url_mapa'] ?? null,
                            'instrucciones' => $dir['instrucciones'] ?? null,
                        ]);
                    }
                }else{
                    if($validated['nombre_direccion'] || $validated['direccion'] || $validated['ciudad'] || $validated['estado'] || $validated['codigo_postal'] || $validated['celular'] || $validated['nombre_recibe'] || $validated['url_mapa'] || $validated['instrucciones']){
                        DireccionCliente::create([
                            'cliente_id' => $cliente->id,
                            'nombre_direccion' => $validated['nombre_direccion'] ?? null,
                            'direccion' => $validated['direccion'] ?? null,
                            'ciudad' => $validated['ciudad'] ?? null,
                            'estado' => $validated['estado'] ?? null,
                            'codigo_postal' => $validated['codigo_postal'] ?? null,
                            'celular' => $validated['celular'] ?? null,
                            'nombre_recibe' => $validated['nombre_recibe'] ?? null,
                            'url_mapa' => $validated['url_mapa'] ?? null,
                            'instrucciones' => $validated['instrucciones'] ?? null,
                        ]);
                    }
                }

                DB::commit();
                    return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
            }catch(Throwable $e){
                DB::rollback();
                report($e);
                return bacK()->withErrors('Ocurrio un error al crear el cliente')->withInput();
            }

    }


    public function show($id){
        $cliente = Cliente::with('direcciones')->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }


    public function edit($id){
        $cliente = Cliente::with('direcciones')->findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }


    public function update(Request $request, $id){

        $validated = $request->validate([

            'nombre' => ['required', 'string', 'max:255'],
            'codigo_cliente' => ['nullable', 'string', 'max:50'],
            'nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:100'],
            'codigo_postal'=> ['nullable', 'string', 'max:30'],
            'celular' => ['nullable', 'string', 'max:20'],
            'nombre_recibe' => ['nullable', 'string', 'max:50'],
            'url_mapa' => ['nullable', 'string', 'max:500'],
            'instrucciones' => ['nullable', 'string', 'max:50'],
            'direcciones' => ['nullable', 'array'],
            'direcciones.*.id' => ['nullable', 'integer'],
            'direcciones.*.nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direcciones.*.direccion' => ['required_with:direcciones', 'string', 'max:255'],
            'direcciones.*.ciudad' => ['nullable', 'string', 'max:100'],
            'direcciones.*.estado' => ['nullable', 'string', 'max:100'],
            'direcciones.*.codigo_postal' => ['nullable', 'string', 'max:20'],
            'direcciones.*.celular' => ['nullable', 'string', 'max:20'],
            'direcciones.*.nombre_recibe' => ['nullable', 'string', 'max:50'],
            'direcciones.*.url_mapa' => ['nullable', 'string', 'max:500'],
            'direcciones.*.instrucciones' => ['nullable', 'string', 'max:500']

        ]);

        DB::beginTransaction();
            try{
                $cliente = Cliente::findOrFail($id);
                $cliente->updated([
                    'nombre'=> $validated['nombre'],
                    'codigo_cliente' => $validated['codigo_cliente'] ?? null,
                    ]);

                if(!empty($validated['direcciones']) && is_array($validated['direcciones'])){
                    $idsEnviados = [];
                    foreach($validated['direcciones'] as $dir){
                        $payload = [
                            'nombre_direccion' => $dir['nombre_direccion'] ?? null,
                            'direccion' => $dir['direccion'] ?? null,
                            'ciudad' => $dir['ciudad'] ?? null,
                            'estado' => $dir['estado'] ?? null,
                            'codigo_postal' => $dir['codigo_postal'] ?? null,
                            'celular' => $dir['celular'] ?? null,
                            'nombre_recibe' => $dir['nombre_recibe'] ?? null,
                            'url_mapa' => $dir['url_mapa'] ?? null,
                            'instrucciones' => $dir['instrucciones'] ?? null, 
                        ];

                        if(!empty($dir['id'])){
                            DireccionCliente::where('id', $dir['id'])
                                ->where('cliente_id', $cliente->id)
                                ->update($payload);
                                $idsEnviados[] = (int)$dir['id'];
                        }else{
                            $nueva = DireccionCliente::create(array_merge($payload,[
                                'cliente_id' => $cliente->id,
                            ]));
                            $idsEnviados[] = $nueva->id;
                        }
                    }

                    DireccionCliente::where('cliente_id', $cliente->id)
                        ->whereNotIn('id', $idsEnviados)
                        ->delete();
                }else{
                    $dir = DireccionCliente::firstOrNew(['cliente_id' => $cliente->id]);
                    $dir->nombre_direccion = $validated['nombre_direccion'] ?? null;
                    $dir->direccion = $validated['direccion'] ?? null;
                    $dir->ciudad = $validated['ciudad'] ?? null;
                    $dir->estado = $validated['estado'] ?? null;
                    $dir->codigo_postal = $validated['codigo_postal'] ?? null;
                    $dir->celular = $validated['celular'] ?? null;
                    $dir->nombre_recibe = $validated['nombre_recibe'] ?? null;
                    $dir->url_mapa = $validated['url_mapa'] ?? null;
                    $dir->instrucciones = $validated['instrucciones'] ?? null;

                    if($dir->nombre_direccion || $dir->direccion || $dir->ciudad || $dir->estado || $dir->codigo_postal|| $dir->celular || $dir->nombre_recibe || $dir->url_mapa || $dir->instrucciones){
                        $dir->save();
                    }elseif($dir->exists){
                        $dir->delete();
                    }
                }

                DB::commit();
                    return redirect()->route('clientes.edit', $id)->with('success', 'Cliente actualizado correctamente.');

            }catch(Throwable $e){
                DB::rollBack();
                report($e);
                return back()->withErrors("Ocurrio un error al actualizar cliente")->withInput();
            }

    }



    public function destroy($id){
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
    }


    public function buscar(Request $request){
        $q = $request->q;
        $clientes = Cliente::where('nombre', 'like', "%$q%")
                    ->orWhere('codigo_cliente', 'like', "%$q%")
                    ->get(['id','nombre','codigo_cliente']);
        return response()->json($clientes);
    }

    public function direcciones($id){
        $direcciones = DireccionCliente::where('cliente_id',$id)
                        ->get(['id','nombre_direccion','direccion']);
        return response()->json($direcciones);
    }


    public function storeAjax(Request $request){

        $this->store($request);
        $cliente = Cliente::latest()->first();
        $direccion = $cliente->direcciones()->latest()->first();

        return response()->json([
            'status' => 1,
            'cliente_id' => $cliente->id,
            'direccion_id' => $direccion->id ?? null,
            'cliente_nombre' => $cliente->nombre,
        ]);

    }

    public function storeDireccion(Request $request){
        
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'nombre_direccion' => ['required', 'string', 'max:100'],
            'direccion' => ['required', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'celular' => ['nullable', 'string', 'max:20'],
            'nombre_recibe' => ['nullable', 'string', 'max:100'],
            'url_mapa' => ['nullable', 'string', 'max:500'],
            'instrucciones' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $direccion = DireccionCliente::create($validated);

            return response()->json([
                'status' => 1,
                'direccion' => $direccion
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error al guardar la dirección'
            ], 500);
        }
    }


}
