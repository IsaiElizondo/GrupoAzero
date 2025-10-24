<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Ruta;
use App\Models\Cliente;
use App\Models\Unidad;
use App\Models\RutaPedido;
use App\User;
use Throwable;

class RutasController extends Controller
{
    public function index(){
        $rutas = Ruta::with(['cliente', 'unidad', 'chofer', 'orders'])->get();
        return view('rutas.index', compact('rutas'));
    }

    public function create(){

        $clientes = Cliente::all();
        $unidades = Unidad::all();
        $choferes = User::where('department_id', 6)->get();
        return view('rutas.create', compact('clientes', 'unidades', 'choferes'));

    }

    public function store(Request $request){

        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_hora'=> ['nullable', 'date'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'chofer_id' => ['required', 'exists:users,id'],
            'estatus_entrega' => ['nullable', 'integer', 'min:0'],
            'motivo' => ['nullable', 'string', 'max:500'],
            'pedidos'=> ['required', 'array', 'min:1'],
            'pedidos.*' => ['integer', 'distinct', 'exists:orders,id'],
            'estatus_pago' => ['required', 'array'],
            'estatus_pago.*' => [Rule::in(['pendiente', 'pagado', 'cancelado'])],
            'monto_por_cobrar' => ['required', 'array'],
            'monto_por_cobrar.*' => ['numeric', 'between:0,99999999999.99'],
        ]);

        DB::beginTransaction();
        try{
            $nextId = (int) Ruta::max('id') + 1;
            $numeroRuta = str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);

            $ruta = new Ruta();
            $ruta->numero_ruta = $numeroRuta;
            $ruta->cliente_id = $validated['cliente_id'];
            $ruta->fecha_hora = $validated['fecha_hora'] ?? null;
            $ruta->unidad_id = $validated['unidad_id'] ?? null;
            $ruta->chofer_id = $validated['chofer_id'];
            $ruta->estatus_entrega = $validated['estatus_entrega'] ?? null;
            $ruta->motivo = $validated['motivo'] ?? null;
            $ruta->save();

            foreach ($validated['pedidos'] as $i => $orderId){
                RutaPedido::create([
                    'ruta_id'=> $ruta->id,
                    'order_id' => $orderId,
                    'estatus_pago' => $validated['estatus_pago'][$i] ?? 'pendiente',
                    'monto_por_cobrar' => $validated['monto_por_cobrar'][$i] ?? 0,
                ]);
            }

            DB::commit();

            LogRuta::create([

                'status' => 'creado',
                'action' => 'Se creo la ruta ' . $ruta->numero_ruta,
                'ruta_id' => $ruta->id,
                'order_id' => null,
                'user_id' => Auth::id(),
                'department_id' => Auth::user()->department_id ?? null,

            ]);

            return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente.');

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrió un error al crear la ruta.')->withInput();
        }
    }

    public function show($id){
        $ruta = Ruta::with(['cliente', 'unidad', 'chofer', 'orders'])->findOrFail($id);
        return view('rutas.show', compact('ruta'));
    }

    public function edit($id){
        $ruta = Ruta::with(['orders'])->findOrFail($id);
        $clientes = Cliente::all();
        $unidades = Unidad::all();
        $choferes = User::where('department_id', 6)->get();

        return view('rutas.edit', compact('ruta', 'clientes', 'unidades', 'choferes'));
    }

    public function update(Request $request, $id){

        $validated = $request->validate([

            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_hora' => ['nullable', 'date'],
            'unidad_id' => ['nullable', 'exists:unidades,id'],
            'chofer_id' => ['required', 'exists:users,id'],
            'estatus_entrega' => ['nullable', 'integer', 'min:0'],
            'motivo' => ['nullable', 'string', 'max:500'],
            'pedidos' => ['required', 'array', 'min:1'],
            'pedidos.*' => ['integer', 'distinct', 'exists:orders,id'],
            'estatus_pago' => ['required', 'array'],
            'estatus_pago.*' => [Rule::in(['pendiente', 'pagado', 'cancelado'])],
            'monto_por_cobrar' => ['required', 'array'],
            'monto_por_cobrar.*' => ['numeric', 'between:0,99999999999.99'],
        ]);

        DB::beginTransaction();
        try{
            $ruta = Ruta::findOrFail($id);
            $ruta->cliente_id = $validated['cliente_id'];
            $ruta->fecha_hora = $validated['fecha_hora'] ?? null;
            $ruta->unidad_id = $validated['unidad_id'] ?? null;
            $ruta->chofer_id = $validated['chofer_id'];
            $ruta->estatus_entrega = $validated['estatus_entrega'] ?? null;
            $ruta->motivo = $validated['motivo'] ?? null;
            $ruta->save();

            // Limpiar pedidos anteriores
            RutaPedido::where('ruta_id', $ruta->id)->delete();

            foreach ($validated['pedidos'] as $i => $orderId){
                RutaPedido::create([
                    'ruta_id' => $ruta->id,
                    'order_id' => $orderId,
                    'estatus_pago' => $validated['estatus_pago'][$i] ?? 'pendiente',
                    'monto_por_cobrar'=> $validated['monto_por_cobrar'][$i] ?? 0,
                ]);
            }

            DB::commit();

            LogRuta::create([
                'status' => 'actualizado',
                'action' => 'Se actualizo la ruta ' . $ruta->numero_ruta,
                'ruta_id' => $ruta->id,
                'order_id' => null,
                'user_id' => Auth::id(),
                'department_id' => Auth::user()->department_id ?? null,
            ]);

            return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente.');

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrió un error al actualizar la ruta.')->withInput();
        }

    }

    public function destroy($id){

        DB::beginTransaction();
        try {
            RutaPedido::where('ruta_id', $id)->delete();
            $ruta = Ruta::findOrFail($id);
            $ruta->delete();

            DB::commit();

            LogRuta::create([

                'status' => 'eliminado', 
                'action' => 'Se elimino la ruta ' . $ruta->numero_ruta,
                'ruta_id' => $ruta->id,
                'order_id' => null,
                'user_id' => Auth::id(),
                'department_id' => Auth::user()->department_id ?? null, 

            ]);

            return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente.');

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Ocurrió un error al eliminar la ruta.');
        }
    }

   
    public function multie(Request $request){

        $user = auth()->user();
        $role = $user->role;

        $rutas = Ruta::with(['cliente', 'unidad', 'chofer'])
            ->orderByDesc('id')
            ->get();

        $estatus_ocultos = implode(',', [6,7,8,9,10]);
        $query = "
            SELECT id, invoice_number, invoice, office, origin, client, created_at, status_id
            FROM orders
            WHERE status_id NOT IN ($estatus_ocultos)
            ORDER BY created_at DESC
            LIMIT 50
        ";
        $shipments = DB::select($query);

        return view('rutas.multie.index', compact('user', 'role', 'rutas', 'shipments'));

    }

    public function multie_lista(Request $request){

        $term = trim($request->get('term', ''));
        $wseg = "";

        if (strlen($term) > 1){
            $term = addslashes($term);
            $wseg = "AND (invoice_number LIKE '%{$term}%' OR invoice LIKE '%{$term}%' OR client LIKE '%{$term}%')";
        }

        $estatus_ocultos = implode(',', [6, 7, 8, 9, 10]);
        $query = "
            SELECT id, invoice_number, invoice, office, origin, client, created_at, status_id
            FROM orders
            WHERE status_id NOT IN ($estatus_ocultos) $wseg
            ORDER BY created_at DESC
            LIMIT 50
        ";
        $shipments = DB::select($query);

        return view('rutas.multie.lista', compact('shipments'));

    }

    public function set_multiruta(Request $request){
        
        $user = auth()->user();
        $ruta_id = (int) $request->get('ruta_id');
        $lista = $request->get('lista', []);

        if(empty($ruta_id) || empty($lista)){
            return response()->json(['status' => 0, 'error' => 'Faltan pedidos o ruta.']);
        }

        $ruta = Ruta::find($ruta_id);
        if(!$ruta){
            return response()->json(['status' => 0, 'error' => 'Ruta no encontrada.']);
        }

        DB::beginTransaction();
        try{
            foreach ($lista as $order_id){
                DB::table('ruta_pedido')->insertOrIgnore([

                    'ruta_id' => $ruta->id,
                    'order_id' => (int) $order_id,
                    'estatus_pago' => 'pendiente',
                    'monto_por_cobrar' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),

                ]);
            }

            DB::commit();

            foreach($lista as $order_id){
                LogRuta::create([

                    'status' => 'pedido_agregado',
                    'action' => 'Se agregó el pedido ' . ' a la ruta ' . $ruta->numero_ruta,
                    'ruta_id' => $ruta->id,
                    'order_id' => $order_id,
                    'user_id' => Auth::id(),
                    'department_id' => Auth::user()->department_id ?? null, 

                ]);
            }

            return response()->json(['status' => 1, 'message' => 'Pedidos asignados correctamente.']);

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return response()->json(['status' => 0, 'error' => 'Ocurrió un error al asignar pedidos.']);
        }

    }
}
