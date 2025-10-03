<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Facades\DB;
use App\Models\Ruta;
use App\Models\Cliente;
use App\Models\Unidad;
use App\Models\RutaPedido;
use App\User;
use Throwable;

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
        
        $validated = $request->validate([

            'cliente_id' => ['required', 'exists:clientes,id'],
            'estatus_pago' => ['required', Rule::in(['pendiente', 'pagado', 'cancelado'])],
            'monto_por_cobrar' => ['rquired', 'numeric', 'between:0,999999999999999999.99'],
            'fecha_hora' => ['nullable', 'date'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'chofer_id' => ['required', 'exists:users,id'],            
            'estatus_entrega' => ['nullable', 'integer', 'min:0'],
            'motivo' => ['nullable', 'string', 'max:500'],
            'pedidos' => ['required', 'array', 'min:1'],
            'pedidos' => ['integer', 'distinc', 'exists:order,id'],

        ]);

        $maxAttemps = 3;

        DB::beginTransaction();
        try{
            for($attemp = 1; $attemp <= $maxAttemps; $attemp++){
                try{

                    $next = (int)Ruta::max('id') + 1;
                    $numeroRuta = str_pad((string) $next, 6, '0', STR_PAD_LEFT);

                    $ruta = new Ruta();
                    $ruta->numero_ruta = $numeroRuta;
                    $ruta->client_id = $validated['cliente_id'];
                    $ruta->estatus_pago = $validated['estatus_pago'];
                    $ruta->monto_por_cobrar = $validated['monto_por cobrar'];
                    $ruta->fecha_hora = $validated['fecha_hora'] ?? null;
                    $ruta->unidad_id =$validated['unidad_id'] ?? null;
                    $ruta->chofer_id = $validated['chofer_id'] ?? null;
                    $ruta->estatus_entrega = $validated['estatus_entrega'] ?? null;
                    $ruta->motivo = $validated['motivo'] ?? null;
                    $ruta->save();
                    break;

                }catch(QueryException $qe){
                    if($attemp < $maxAttemps){
                        continue;
                    }
                    throw $qe;
                }    

            }

            $pedidoIds = array_values(array_unique($validated['pedidos']));
            
            foreach($pedidoIds as $orderId){
                RutaPedido::create([
                    'ruta_id' => $ruta->id,
                    'order_id' => $orderId,
                ]);
            }

            DB::commit();
            return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente.');
        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrio un error al crear la ruta')->withInput();
        }

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

        $validated = $request->validate([

            'cliente_id' => ['required', 'exists:clientes,id'],
            'estatus_pago' => ['required', Rule::in(['pendiente', 'pagado', 'cancelado'])],
            'monto_por_cobrar' => ['required', 'numeric', 'between:0,99999999999.99'],
            'fecha_hora' => ['nullable', 'date'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'chofer_id' => ['nullable', 'exists:users,id'],
            'estatus_entrega' => ['nullable', 'integer', 'min:0'],
            'motivo' => ['nullable', 'string'],
            'pedidos' => ['required', 'array', 'min:1'],
            'pedidos.*' => ['integer', 'distinc', 'exists:orders,id'],

        ]);

        DB::beginTransaction();
        try{

            $ruta = Ruta::findOrFail($id);
            $ruta->cliente_id = $validated['cliente_id'];
            $ruta->estatus_pago = $validated['estatus_pago'];
            $ruta->monto_por_cobrar = $validated['monto_por_cobrar'];
            $ruta->fecha_hora = $validated['fecha_hora'] ?? null;
            $ruta->unidad_id = $validated['unidad_id'] ?? null;
            $ruta->chofer_id = $validated['chofer_id'] ?? null;
            $ruta->estatus_entrega = $validated['estatus_entrega'] ?? null;
            $ruta->motivo = $validated['motivo'] ?? null;
            $ruta->save();

            RutaPedido::where('ruta_id', $ruta->id)->delete();

            $pedidoIds = array_values(array_unique($validated['pedidos']));
            foreach($pedidoIds as $orderId){
                RutaPedido::create([
                    'ruta_id' => $ruta->id,
                    'order_id' => $orderId,
                ]);
            }

            DB::commit();
            return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente');

        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrio un error al actualizar la ruta')->withInput();
        }

    }


    public function destroy($id){
       
        DB::beginTransaction();
        try{
            RutaPedido::where('ruta_id', $id)->delete();
            $ruta = Ruta::findOrFail($id);
            $ruta->delete();

            DB::commit();
            return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente');
        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrio un error al eliminar la ruta');
        }

    }

}
