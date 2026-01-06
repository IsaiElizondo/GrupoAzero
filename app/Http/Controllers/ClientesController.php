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
        $titlePage = 'Registrar nuevo cliente';
        $RequerimientosEspeciales = DB::table('requerimientos_especiales')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('clientes.create', compact('activePage', 'titlePage', 'RequerimientosEspeciales'));
    }

    
    public function store(Request $request){

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo_cliente' => ['nullable', 'string', 'max:50'],
            'direcciones' => ['nullable', 'array'],
            'direcciones.*.nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direcciones.*.tipo_residencia' => ['required', 'string', 'max:50'],
            'direcciones.*.direccion' => ['required', 'string', 'max:255'],
            'direcciones.*.colonia' => ['nullable', 'string', 'max:100'],
            'direcciones.*.ciudad' => ['nullable', 'string', 'max:100'],
            'direcciones.*.estado' => ['nullable', 'string', 'max:100'],
            'direcciones.*.codigo_postal' => ['nullable', 'string', 'max:20'],
            'direcciones.*.celular' => ['nullable', 'digits:10'],
            'direcciones.*.telefono' => ['nullable', 'digits:10'],
            'direcciones.*.nombre_recibe' => ['nullable', 'string', 'max:50'],
            'direcciones.*.url_mapa' => ['nullable', 'string', 'max:500'],
            'direcciones.*.instrucciones' => ['nullable', 'string', 'max:500'],
            'direcciones.*.requerimientos' => ['nullable', 'array'],
            'direcciones.*.requerimientos.*' => ['integer', 'exists:requerimientos_especiales,id'],
        ]);

        DB::beginTransaction();
            try{
                $cliente = Cliente::create([
                    'nombre' => $validated['nombre'],
                    'codigo_cliente' => $validated['codigo_cliente'] ?? null,
                ]);

                if (!empty($validated['direcciones']) && is_array($validated['direcciones'])) {
                    foreach ($validated['direcciones'] as $dir) {
                        $direccion = DireccionCliente::create([
                            'cliente_id' => $cliente->id,
                            'nombre_direccion' => $dir['nombre_direccion'] ?? null,
                            'tipo_residencia' => $dir['tipo_residencia'],
                            'direccion' => $dir['direccion'],
                            'colonia' => $dir['colonia'] ?? null,
                            'ciudad' => $dir['ciudad'] ?? null,
                            'estado' => $dir['estado'] ?? null,
                            'codigo_postal' => $dir['codigo_postal'] ?? null,
                            'celular' => $dir['celular'] ?? null,
                            'telefono' => $dir['telefono'] ?? null,
                            'nombre_recibe' => $dir['nombre_recibe'] ?? null,
                            'url_mapa' => $dir['url_mapa'] ?? null,
                            'instrucciones' => $dir['instrucciones'] ?? null,
                        ]);
                        if(!empty($dir['requerimientos']) && is_array($dir['requerimientos'])){
                            foreach ($dir['requerimientos'] as $ReqId){
                                DB::table('direccion_requerimiento')->insert([
                                    'cliente_direccion_id' => $direccion->id,
                                    'requerimiento_especial_id' => $ReqId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }

                DB::commit();
                return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
            }catch(Throwable $e){
                DB::rollBack();
                report($e);
                return back()->withErrors('Ocurrió un error al crear el cliente')->withInput();
            }

    }


    public function show($id){
        $cliente = Cliente::with('direcciones')->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }


    public function edit($id){
        $cliente = Cliente::with('direcciones')->findOrFail($id);

        $requerimientos = DB::table('requerimientos_especiales')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $requerimientosPorDireccion = DB::table('direccion_requerimiento')
            ->whereIn(
                'cliente_direccion_id',
                $cliente->direcciones->pluck('id')
            )
            ->get()
            ->groupBy('cliente_direccion_id')
            ->map(function ($rows) {
                return $rows->pluck('requerimiento_especial_id')->toArray();
            })
            ->toArray();

        return view('clientes.edit', compact(
            'cliente',
            'requerimientos',
            'requerimientosPorDireccion'
        ));
    }
    

    public function update(Request $request, $id){

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo_cliente' => ['nullable', 'string', 'max:50'],
            'direcciones' => ['nullable', 'array'],
            'direcciones.*.id' => ['nullable', 'integer'],
            'direcciones.*.nombre_direccion' => ['nullable', 'string', 'max:100'],
            'direcciones.*.tipo_residencia' => ['required', 'string', 'max:50'],
            'direcciones.*.direccion' => ['required', 'string', 'max:255'],
            'direcciones.*.colonia' => ['nullable', 'string', 'max:100'],
            'direcciones.*.ciudad' => ['nullable', 'string', 'max:100'],
            'direcciones.*.estado' => ['nullable', 'string', 'max:100'],
            'direcciones.*.codigo_postal' => ['nullable', 'string', 'max:20'],
            'direcciones.*.celular' => ['nullable', 'digits:10'],
            'direcciones.*.telefono' => ['nullable', 'digits:10'],
            'direcciones.*.nombre_recibe' => ['nullable', 'string', 'max:50'],
            'direcciones.*.url_mapa' => ['nullable', 'string', 'max:500'],
            'direcciones.*.instrucciones' => ['nullable', 'string', 'max:500'],
            'direcciones.*.requerimientos' => ['nullable', 'array'],
            'direcciones.*.requerimientos.*' => ['integer', 'exists:requerimientos_especiales,id'],
        ]);
        DB::beginTransaction();
            try{
                $cliente = Cliente::findOrFail($id);
                $cliente->update([
                    'nombre' => $validated['nombre'],
                    'codigo_cliente' => $validated['codigo_cliente'] ?? null,
                ]);
                $idsDirecciones = [];
                if (!empty($validated['direcciones']) && is_array($validated['direcciones'])) {
                    foreach ($validated['direcciones'] as $dir) {
                        $payload = [
                            'nombre_direccion' => $dir['nombre_direccion'] ?? null,
                            'tipo_residencia' => $dir['tipo_residencia'],
                            'direccion' => $dir['direccion'],
                            'colonia' => $dir['colonia'] ?? null,
                            'ciudad' => $dir['ciudad'] ?? null,
                            'estado' => $dir['estado'] ?? null,
                            'codigo_postal' => $dir['codigo_postal'] ?? null,
                            'celular' => $dir['celular'] ?? null,
                            'telefono' => $dir['telefono'] ?? null,
                            'nombre_recibe' => $dir['nombre_recibe'] ?? null,
                            'url_mapa' => $dir['url_mapa'] ?? null,
                            'instrucciones' => $dir['instrucciones'] ?? null,
                        ];

                        if(!empty($dir['id'])){
                            DireccionCliente::where('id', $dir['id'])
                                ->where('cliente_id', $cliente->id)
                                ->update($payload);

                            $DireccionId = (int) $dir['id'];
                        }else{
                            $nueva = DireccionCliente::create(array_merge($payload,[
                                'cliente_id' => $cliente->id,
                            ]));

                            $DireccionId = $nueva->id;
                        }

                        $idsDirecciones[] = $DireccionId;

                        DB::table('direccion_requerimiento')
                            ->where('cliente_direccion_id', $DireccionId)
                            ->delete();

                        if(!empty($dir['requerimientos']) && is_array($dir['requerimientos'])){
                            foreach ($dir['requerimientos'] as $ReqId){
                                DB::table('direccion_requerimiento')->insert([
                                    'cliente_direccion_id' => $DireccionId,
                                    'requerimiento_especial_id' => $ReqId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }

                    DireccionCliente::where('cliente_id', $cliente->id)
                        ->whereNotIn('id', $idsDirecciones)
                        ->delete();
                }
                DB::commit();
                return redirect()->route('clientes.edit', $id)
                    ->with('success', 'Cliente actualizado correctamente.');
            }catch (Throwable $e){
                DB::rollBack();
                report($e);
                return back()->withErrors('Ocurrió un error al actualizar el cliente')->withInput();
            }
    }



    public function destroy($id){
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
    }


    public function buscar(Request $request){
        //\Log::info('Buscando cliente', ['q' => $request->q]);
        $q = trim($request->q);
        $clientes = Cliente::where('nombre', 'like', "%$q%")
                    ->orWhere('codigo_cliente', 'like', "%$q%")
                    ->limit(20)
                    ->get();
        return view('clientes.lista_busqueda.lista_clientes', compact('clientes'));
    }


    public function direcciones($id){
        $direcciones = DireccionCliente::where('cliente_id',$id)
                        ->get(['id','nombre_direccion','direccion']);
        return response()->json($direcciones);
    }


    public function storeAjax(Request $request){

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo_cliente' => ['nullable', 'string', 'max:50'],
            'estado_direccion' => ['required', 'in:completa,pendiente,recoge'],
            'nombre_direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:30'],
            'celular' => ['nullable', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'nombre_recibe' => ['nullable', 'string', 'max:50'],
            'url_mapa' => ['nullable', 'string', 'max:50'],
            'instrucciones' => ['nullable', 'string', 'max:500'],
        ]);

        try{
            $cliente = Cliente::create([
                'nombre' => $validated['nombre'],
                'codigo_cliente' => $validated['codigo_cliente'] ?? null,
            ]);
            
            $estadoDireccion = $validated['estado_direccion'];
            $direccion = null;

            if($estadoDireccion == 'completa'){
                $direccion = DireccionCliente::create([
                    'cliente_id' => $cliente->id,
                    'estado_direccion' => 'completa',
                    'nombre_direccion' => $validated['nombre_direccion'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'ciudad' => $validated['ciudad'] ?? null,
                    'estado' => $validated['estado'] ?? null,
                    'codigo_postal' => $validated['codigo_postal'] ?? null,
                    'celular' => $validated['celular'] ?? null,
                    'telefono' => $validated['telefono'] ?? null,
                    'nombre_recibe' => $validated['nombre_recibe'] ?? null,
                    'url_mapa' => $validated['url_mapa'] ?? null,
                    'instrucciones' => $validated['instrucciones'] ?? null,
                ]);
            }else{
                $direccion = DireccionCliente::create([
                    'cliente_id' => $cliente->id,
                    'estado_direccion' => $estadoDireccion,
                    'nombre_direccion' => null,
                    'direccion' => null,
                ]);
            }

            return response()->json([
                'status' => 1,
                'cliente_id' => $cliente->id,
                'direccion_id' => $direccion->id ?? null,
                'estado_direccion' => $estadoDireccion,
                'cliente_nombre' => $cliente->nombre,
            ]);
        }catch(\Throwable $e){
            report($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error al crear el cliente o la dirección'
            ],500);
        }

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
            'telefono' => ['nullable', 'string', 'max:20'],
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
