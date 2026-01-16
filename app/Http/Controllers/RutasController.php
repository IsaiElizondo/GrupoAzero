<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Ruta;
use App\Models\Cliente;
use App\Models\Unidad;
use App\Models\RutaPedido;
use App\Models\UnidadChofer;
use App\Order;
use App\User;
use Throwable;

class RutasController extends Controller
{

    private function generarNumeroRuta(){
        
        $ultima = Ruta::orderBy('id', 'desc')->value('numero_ruta');

        if(!$ultima){
            return '000001';
        }

        preg_match('/^([A-Z]?)([0-9]{6})$/', $ultima, $parts);
        $letra = $parts[1] ?? '';
        $num = intval($parts[2] ?? 0);
        $num++;

        if($num > 999999){
            $num = 1;
            if($letra == ''){
                $letra = 'A';
            }else{
                $letra = chr(ord($letra)+1);
            }
        }

        return $letra . str_pad($num, 6, '0', STR_PAD_LEFT);

    }


    private function correlativoPedidoRuta($rutaId){
        
        return RutaPedido::where('ruta_id', $rutaId)->count() + 1;
    }



    private function registrarUnidadChofer($unidadId, $choferId){
        
        if(!$unidadId || !$choferId) return;

        $rel = UnidadChofer::firstOrCreate([
            'unidad_id' => $unidadId,
            'chofer_id' => $choferId,
        ]);

        $rel->uso_count += 1;
        $rel->last_used_at = now();
        $rel->save();

    }


    public function index(){
        $rutas = Ruta::with(['unidad', 'chofer'])->get();
        return view('rutas.index', compact('rutas'));
    }

    public function create(){

        $unidades = Unidad::all();
        $choferes = User::where('department_id', 6)->get();
        return view('rutas.create', compact('unidades', 'choferes'));

    }

    public function store(Request $request){

        $validated = $request->validate([
            'fecha_hora'=> ['nullable', 'date'],
            'unidad_id' => ['nullable','exists:unidades,id'],
            'chofer_id' => ['required','exists:users,id'],
            'estatus_entrega' => ['nullable',Rule::in(['enrutado','entregado','entrega_no_exitosa'])],
            'motivo'=> ['nullable','string','max:500'],
            'pedidos'=> ['required','array','min:1'],
            'pedidos.*'=> ['integer','exists:orders,id'],
            'estatus_pago'=> ['required','array'],
            'estatus_pago.*'=> [Rule::in(['pagado','por_cobrar','credito'])],
            'monto_por_cobrar'=> ['required','array'],
            'monto_por_cobrar.*'=> ['numeric','between:0,99999999999.99'],
        ]);

        DB::beginTransaction();
        try {

            $ruta = new Ruta();
            $ruta->numero_ruta = $this->generarNumeroRuta();
            $ruta->numero_dia = Ruta::whereDate('created_at', today())->count() + 1;
            $ruta->fecha_hora = $validated['fecha_hora'] ?? now();
            $ruta->unidad_id = $validated['unidad_id'] ?? null;
            $ruta->chofer_id = $validated['chofer_id'];
            $ruta->save();

            $this->registrarUnidadChofer($ruta->unidad_id, $ruta->chofer_id);

            foreach ($validated['pedidos'] as $i => $orderId){
                $pedido = DB::table('orders')->where('id', $orderId)->first();

                $clienteCodigo = $pedido->client ?? null;
                $clienteNombre = $pedido->nombre_cliente ?? null;

                RutaPedido::create([
                    'ruta_id' => $ruta->id,
                    'order_id' => $orderId,
                    'estatus_pago' => $validated['estatus_pago'][$i],
                    'estatus_entrega' => 'enrutado',
                    'monto_por_cobrar' => $validated['monto_por_cobrar'][$i],
                    'numero_pedido_ruta' => $this->correlativoPedidoRuta($ruta->id),
                    'cliente_codigo' => $clienteCodigo,
                    'cliente_nombre' => $clienteNombre,
                    'partial_folio' => $request->partial_folio[$i],
                    'smaterial_folio' => $request->smaterial_folio[$i],
                    'partial_id' => $request->partial_id[$i] ?: null,
                    'smaterial_id' => $request->smaterial_id[$i] ?: null,
                    'tipo_subproceso' => $request->partial_id[$i] ? 'sp' : ($request->smaterial_id[$i] ? 'sm' : 'pedido'),
                    'subproceso_id' => $request->partial_id[$i] ?: ($request->smaterial_id[$i] ?: null),
                ]);
            }

            DB::commit();

        return redirect()->route('rutas.index')->with('success','Ruta creada correctamente.');

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Error al crear la ruta')->withInput();
        }
    }



    public function show($id){
        $ruta = Ruta::with(['unidad','chofer'])->findOrFail($id);
        return view('rutas.show', compact('ruta'));
    }




    public function edit($id){
        $ruta = Ruta::with(['pedidos.order'])->findOrFail($id);
        $unidades = Unidad::all();
        $choferes = User::where('department_id', 6)->get();

        return view('rutas.edit', compact('ruta','unidades','choferes'));
    }

    public function update(Request $request, $id){

        $validated = $request->validate([

            'fecha_hora'=> ['nullable', 'date'],
            'unidad_id'=> ['nullable','exists:unidades,id'],
            'chofer_id'=> ['required','exists:users,id'],
            'estatus_entrega'=> ['nullable',Rule::in(['enrutado','entregado','entrega_no_exitosa'])],
            'motivo'=> ['nullable','string','max:500'],
            'pedidos'=> ['required','array','min:1'],
            'pedidos.*'=> ['integer','exists:orders,id'],
            'estatus_pago'=> ['required','array'],
            'estatus_pago.*'=> [Rule::in(['pagado','por_cobrar','credito'])],
            'monto_por_cobrar'=> ['required','array'],
            'monto_por_cobrar.*'=> ['numeric','between:0,99999999999.99'],
        
        ]);

        DB::beginTransaction();
        try{
            $ruta = Ruta::findOrFail($id);
            $ruta->fecha_hora = $validated['fecha_hora'] ?? $ruta->fecha_hora;
            $ruta->unidad_id = $validated['unidad_id'] ?? null;
            $ruta->chofer_id = $validated['chofer_id'];
            $ruta->save();

            //Recordar combinación unidad–chofer
            $this->registrarUnidadChofer($ruta->unidad_id, $ruta->chofer_id);

            //Limpiar pedidos anteriores
            RutaPedido::where('ruta_id', $ruta->id)->delete();

            //Registrar nuevos pedidos
            foreach ($validated['pedidos'] as $i => $orderId){

                $pedido = DB::table('orders')->where('id', $orderId)->first();

                $clienteCodigo = $pedido->client ?? null;
                $clienteNombre = $pedido->nombre_cliente ?? null;

                RutaPedido::create([
                    'ruta_id' => $ruta->id,
                    'order_id' => $orderId,
                    'estatus_pago' => $validated['estatus_pago'][$i],
                    'estatus_entrega' => 'enrutado',
                    'monto_por_cobrar' => $validated['monto_por_cobrar'][$i],
                    'numero_pedido_ruta' => $this->correlativoPedidoRuta($ruta->id),
                    'cliente_codigo' => $clienteCodigo,
                    'cliente_nombre' => $clienteNombre,
                    'partial_folio' => $request->partial_folio[$i] ?? null,
                    'smaterial_folio' => $request->smaterial_folio[$i] ?? null,
                    'partial_id' => $request->partial_id[$i] ?? null,
                    'smaterial_id' => $request->smaterial_id[$i] ?? null,
                    'tipo_subproceso' => !empty($request->partial_id[$i]) ? 'sp' : (!empty($request->smaterial_id[$i]) ? 'sm' : 'pedido'),
                    'subproceso_id' => $request->partial_id[$i] ?? ($request->smaterial_id[$i] ?? null),
                ]);


            }

            DB::commit();


            return redirect()->route('rutas.index')->with('success','Ruta actualizada correctamente.');

        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Error al actualizar la ruta')->withInput();
        }
    }

    public function destroy($id){

        DB::beginTransaction();
        try{
            RutaPedido::where('ruta_id',$id)->delete();
            $ruta = Ruta::findOrFail($id);
            $ruta->delete();

            DB::commit();

            return redirect()->route('rutas.index')->with('success','Ruta eliminada correctamente.');

        }catch(Throwable $e){
            DB::rollBack();
            report($e);
            return back()->withErrors('Error al eliminar la ruta.');
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
        (
            SELECT
                o.id AS order_id,
                o.id AS id_real,
                CAST(o.invoice_number AS CHAR) AS folio,
                CAST(o.client AS CHAR) AS client,
                CAST(o.origin AS CHAR) AS origin,
                CAST(o.office AS CHAR) AS office,
                o.created_at,
                o.status_id,
                'order' AS tipo
            FROM orders o
            WHERE o.status_id NOT IN (6,7,8,9,10)
            AND o.estado_direccion = 'completa'
            AND (
                    o.invoice_number LIKE '%{$term}%'
                OR  o.invoice LIKE '%{$term}%'
                OR  o.client LIKE '%{$term}%'
            )
        )

        UNION ALL

        (
            SELECT
                p.order_id AS order_id,
                p.id AS id_real,
                CAST(p.invoice AS CHAR) AS folio,
                NULL AS client,
                'P' AS origin,
                NULL AS office,
                p.created_at,
                p.status_id,
                'partial' AS tipo
            FROM partials p
            INNER JOIN orders o ON o.id = p.order_id
            WHERE (p.status_4 = 1 OR p.status_5 = 1)
            AND o.status_id NOT IN (6,7,8,9,10)
            AND o.estado_direccion = 'completa'
            AND p.invoice LIKE '%{$term}%'
        )

        UNION ALL

        (
            SELECT
                s.order_id AS order_id,
                s.id AS id_real,
                CAST(s.code AS CHAR) AS folio,
                NULL AS client,
                'SM' AS origin,
                NULL AS office,
                s.created_at,
                s.status_id,
                'material' AS tipo
            FROM smaterial s
            INNER JOIN orders o ON o.id = s.order_id
            WHERE (s.status_4 = 1 OR s.status_5 = 1)
            AND o.status_id NOT IN (6,7,8,9,10)
            AND o.estado_direccion = 'completa'
            AND s.code LIKE '%{$term}%'
        )

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
                    'estatus_pago' => 'pagado',
                    'estatus_entrega' => 'enrutado',
                    'monto_por_cobrar' => 0,
                    'partial_folio' => $request->partial_folio[$k] ?? null,
                    'smaterial_folio' => $request->smaterial_folio[$k] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),

                ]);
            }

            DB::commit();


            return response()->json(['status' => 1, 'message' => 'Pedidos asignados correctamente.']);

        }catch (Throwable $e){
            DB::rollBack();
            report($e);
            return response()->json(['status' => 0, 'error' => 'Ocurrió un error al asignar pedidos.']);
        }

    }

    public function updatePago(Request $request){
        $validated = $request->validate([
            'ruta_pedido_id' => ['required','exists:ruta_pedido,id'],
            'estatus_pago' => ['required', Rule::in(['pagado','por_cobrar','credito'])],
            'monto_por_cobrar' => ['required','numeric','between:0,99999999999.99'],
        ]);

        RutaPedido::where('id', $validated['ruta_pedido_id'])->update([
            'estatus_pago' => $validated['estatus_pago'],
            'monto_por_cobrar' => $validated['monto_por_cobrar'],
        ]);

        return response()->json(['ok' => true]);
    }

}
