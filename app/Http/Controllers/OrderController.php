<?php

namespace App\Http\Controllers;

use App\Cancelation;
use App\Debolution;
use App\Evidence;
use App\Follow;
use App\Log;
use App\ManufacturingOrder;
use App\Note;
use App\Order;
use App\Partial;
use App\Picture;
use App\PurchaseOrder;
use App\Reason;
use App\Rebilling;
use App\Shipment;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Libraries\Paginacion;
use App\Libraries\Tools;
//use App\Paginacion;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dd($request->all());
        $textos = [];
        $busquedaOrden = trim($request->busquedaOrden);
        $busquedaFactura = trim($request->busquedaFactura);
        $busquedaCliente = trim($request->busquedaCliente);
        $busquedaSucursal = trim($request->busquedaSucursal);
        $busquedaEstatus = trim($request->busquedaEstatus);

        $fecha = $request->fecha;
        $fechaDos = $request->fechaDos;
        $mensaje = NULL;
        
        $statuses = Status::all();
  
        //$orders = Order::where('delete', NULL)->paginate(15);
        //$orders=[];
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        
        $pag = $request->get("page");
        $pag = !empty($pag) ? intval($pag) : 1 ;
  
        
        $ordersbuilder = Order::where('delete', NULL);
        
        if(!empty($busquedaOrden)){
            $textos []=$busquedaOrden;
            $ordersbuilder->where('invoice', 'LIKE', '%'.$busquedaOrden.'%');
        }
        if(!empty($busquedaFactura)){
            $textos []=$busquedaFactura;
            $ordersbuilder->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%');
        }
        if(!empty($busquedaCliente)){
            $textos []=$busquedaCliente;
            $ordersbuilder->where('client', 'LIKE', '%'.$busquedaCliente.'%');
        }
        if(!empty($busquedaSucursal)){
            $textos []=$busquedaSucursal;
            $ordersbuilder->where('office', 'LIKE', '%'.$busquedaSucursal.'%');
        }
        
        if(!empty($busquedaEstatus)){
            $textos []=$busquedaEstatus;
            $ordersbuilder->where('office', 'LIKE', '%'.$busquedaEstatus.'%');
        }
        
        if(!empty($fecha) && empty($fechaDos)){
            $ordersbuilder->whereDate('created_at', Carbon::parse($request->fecha)->toDateString());
        }
        else if(!empty($fecha) && !empty($fechaDos)){
            $ordersbuilder->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()]);
        }
        
        $texto = implode(", ",$textos);
        
        $ini = ($pag < 2 ) ? 0 : $pag - 1;
        $rpp = 15;
        
        $ordersbuildertot = $ordersbuilder;
        $total = $ordersbuildertot->count();
        //echo $ordersbuildertot->toSql();
        
        $ordersbuilder->orderBy('created_at', 'desc')
        ->skip($ini)->take($rpp);
        
        $orders = $ordersbuilder->get();
        
        //$total = 1500;
  
      
        $busquedas = ["order"=>$busquedaOrden, "factura"=>$busquedaFactura, "cliente"=>$busquedaCliente,
            "sucursal"=>$busquedaSucursal, "fecha"=>$fecha, "fechaDos"=>$fechaDos, "estatus"=>$busquedaEstatus
        ];
        
        return view('orders.index', compact('orders', 'role', 'department', 'texto', 'fecha', 'fechaDos', 'mensaje','pag','rpp','total',"busquedas","statuses"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$orders = Order::all();
        $orders=[];
        $statuses = Status::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $mensaje = NULL;

        return view('orders.create', compact('orders', 'statuses', 'role', 'department', 'mensaje'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cliente_id = null;
        $cliente_direccion_id = null;
        $nombre_cliente = null;
        $direccion = null;
        $ciudad = null;
        $estado = null;
        $codigo_postal = null;
        $celular = null;
        $nombre_recibe = null;

        $tipo_cliente = $request->input('tipo_cliente');

        if($tipo_cliente == 'general'){
            $nombre_cliente = "Cliente general";
        }
        
        elseif($tipo_cliente == 'existente'){
            $cliente = Cliente::find($request->cliente_id);
            $direccionCliente = DireccionCliente::find($request->cliente_direccion_id);

            if($cliente && $direccionCliente){
                $cliente_id = $cliente->id;
                $cliente_direccion_id = $direccionCliente->id;
                $nombre_cliente = $cliente->nombre;
                $direccion = $direccionCliente->direccion;
                $ciudad = $direccionCliente->ciudad;
                $estado = $direccionCliente->estado;
                $codigo_postal = $direccionCliente->codigo_postal;
                $celular = $direccionCliente->celular;
                $nombre_recibe = $direccionCliente->nombre_recibe;
            }
        }

        elseif($tipo_cliente == 'nuevo'){
            $cliente = Cliente::find($request->cliente_id);
            $direccionCliente = DireccionCliente::find($request->cliente_direccion_id);

            if($cliente && $direccionCliente){
                $cliente_id = $cliente->id;
                $cliente_direccion_id = $direccionCliente->id;
                $nombre_cliente = $cliente->nombre;
                $ciudad = $direccionCliente->ciudad;
                $estado = $direccionCliente->estado;
                $codigo_postal = $direccionCliente->codigo_postal;
                $celular = $direccionCliente->celular;
                $nombre_recibe = $direccionCliente->nombre_recibe;
            }
        }


        // dd($request->all());
        $registro = Order::where('invoice', 'like', $request->invoice)->count();
        // dd($registro);
        if($registro == 0 ){
            $mensaje = 'Órden guardada con éxito!';
        }else{
            $mensaje = 'La orden que almacenaste ya existe';
        }

        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        $action = $user->name .' creó una orden';

        if($request->credit){
            $credit = 1;
            $action = $action . ' a credito';
        }else{
            $credit = 0;
        }
        
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $order = Order::create([
            'office' => $userOffice,
            'invoice' => $request->invoice,
            'invoice_number' => $request->invoice_number,
            'client' => $request->client,
            //NUEVOS CAMPOS PARA LA COPIA
            'cliente_id' => $cliente_id,
            'cliente_direccion_id' => $cliente_direccion_id,
            'nombre_cliente' => $nombre_cliente,
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'estado' => $estado,
            'codigo_postal' => $codigo_postal,
            'celular' => $celular,
            'nombre_recibe' => $nombre_recibe,
            //FIN DE LOS CAMPOS NUEVOS
            'credit' => $credit,
            'status_id' => $request->status_id,
            'created_at' => now(),
        ]);
        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'created_at' => now()
            ]);
            $action = $action . ',  se añadió una nota';
        }
        // Ver si va con Orden de Requisición
        if($request->ocCheck == 1){
            if ( $request->documentReq || $request->requisition){
                $hoy = date("Y-m-d H:i:s");
                $path = NULL;
                $pathReq = NULL;
                $action = $action . ', se cubrió la Orden de Requisición';

                $po = PurchaseOrder::create([
                    'required' => $request->ocCheck,
                    'number' => $request->purchase_order,
                    'document' => NULL,
                    'requisition' => NULL,
                    'iscovered' => 1,
                    'order_id' => $order->id
                ]);
                $action = $action . ', se añadió factura y requisición';

                if ($request->documentReq){
                    $file = $request->file('documentReq');
                    $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                    $path = 'OrdenesDeCompra/' . $name;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $file, $name );
                    $po->update([
                        'number' => $request->purchase_order,
                        'document' => $path,
                        'iscovered' => 1,
                    ]);
                }
                if ($request->requisition){
                    $fileReq = $request->file('requisition');
                    $nameReq = str_replace(' ','-', $request->invoice .'-'.$fileReq->getClientOriginalName());
                    $pathReq = 'OrdenesDeCompra/' . $nameReq;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $fileReq, $nameReq );
                    $po->update([
                        'number' => $request->purchase_order,
                        'requisition' => $pathReq,
                        'iscovered' => 1,
                    ]);
                }

            }
        }

        $folios = 0;
        if($request->folio1){
            Partial::create([
                'invoice' => $request->folio1,
                'order_id' => $order->id,
                'status_id' => $request->fol_status1
            ]);
            $folios++;
        }
        if($request->folio2){
            Partial::create([
                'invoice' => $request->folio2,
                'order_id' => $order->id,
                'status_id' => $request->fol_status2
            ]);
            $folios++;
        }
        if($request->folio3){
            Partial::create([
                'invoice' => $request->folio3,
                'order_id' => $order->id,
                'status_id' => $request->fol_status3
            ]);
            $folios++;
        }
        if($request->folio4){
            Partial::create([
                'invoice' => $request->folio4,
                'order_id' => $order->id,
                'status_id' => $request->fol_status4
            ]);
            $folios++;
        }
        if($request->folio5){
            Partial::create([
                'invoice' => $request->folio5,
                'order_id' => $order->id,
                'status_id' => $request->fol_status5
            ]);
            $folios++;
        }
        if($request->folio1){
            $action = $action . ', se añadió ' . $folios . ' entregas parciales';
        }

        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        // dd($registro, $request->invoice);
        $fecha = '';
        $texto = '';
        $orders = Order::where('delete', NULL)->paginate(15);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        return view('orders.index', compact('orders', 'role', 'department', 'fecha', 'texto', 'mensaje'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $logs = Log::where('order_id', $id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($logs);

        return view('orders.show', compact('order', 'role', 'department', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $statuses = Status::all();
        $reasons = Reason::all();

        $fav = Follow::where('user_id', auth()->user()->id)
        ->where('order_id', $order->id)
        ->first();

        return view('orders.edit', compact('order', 'statuses', 'role', 'department', 'fav', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $order = Order::find($id);
        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);

        if($request->status_id == 5 && $request->credit == true && !isset($request->invoice_number) ){
            return redirect()->back();
        }

        $action = $user->name . ' actualizó la orden';

        // Verificar crédito
        if($request->credit){
            $credit = 1;
        }else{
            $credit = 0;
        }
        // Verificar si hay cambio en estatus de crédito
        if( $order->credit != $credit){
            if($request->credit == true){
                $credit = 1;
                $action = $action . ', orden a credito';
            }else{
                $credit = 0;
                $action = $action . ', orden sin credito';
            }
        }
        // Actualización de orden
        $order->update([
            'office' => $request->office,
            'invoice' => $request->invoice,
            'invoice_number' => $request->invoice_number,
            'client' => $request->client,
            'credit' => $credit,
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        // Ver si va con Orden de Requisición
        if($request->ocCheck == 1){
            PurchaseOrder::create([
                'required' => $request->ocCheck,
                'number' => $request->purchase_order,
                'document' => NULL,
                'requisition' => NULL,
                'order_id' => $order->id
            ]);
            $action = $action . ', el pedido requiere una Orden de Requisición ';
        }

        // Orden de fabricación
        if( $request->status_id == 3 && !isset( $order->manufacturingorder ) ){
            $file = $request->file('manufacturingFile');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Fabricaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                Storage::putFileAs('/public/' . 'Fabricaciones/', $file, $name );
                ManufacturingOrder::create([
                    'required' => 1,
                    'number' => $request->manufacturingOrder,
                    'document' => $path,
                    'iscovered' => NULL,
                    'order_id'=> $order->id
                ]);
                $action = $action . ', se añadió orden de fabricación ' . $request->manufacturingOrder . ' y se añadió un archivo';
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
                ManufacturingOrder::create([
                    'required' => 1,
                    'number' => $request->manufacturingOrder,
                    'document' => $path,
                    'iscovered' => NULL,
                    'order_id'=> $order->id
                ]);
                $action = $action . ', se añadió orden de fabricación ' . $request->manufacturingOrder . ', no se añadió un archivo';
            }
        }elseif( $request->status_id == 3 && !isset( $order->manufacturingorder->document ) ){
            $file = $request->file('manufacturingFile');
            $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
            $path = 'Fabricaciones/' . $name;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            Storage::putFileAs('/public/' . 'Fabricaciones/', $file, $name );
            $order->manufacturingorder->update([
                'required' => 1,
                'number' => $request->manufacturingOrder,
                'document' => $path,
                'iscovered' => NULL,
                'order_id'=> $order->id
            ]);
            $action = $action . ', se actualizó orden de fabricación ' . $request->manufacturingOrder . ', se añadió un archivo';
        }

        
        
        

        
        
        // Evidencia para salida a ruta
        if($request->status_id == 5){
        $numev = 0 ;    
        //$numev += $this->StoreRouteEvidence($request, $order,  "1");   
        //$numev += $this->StoreRouteEvidence($request, $order,  "2");  
        //$numev += $this->StoreRouteEvidence($request, $order,  "3");  
        //$numev += $this->StoreRouteEvidence($request, $order,  "4");  
        //$numev += $this->StoreRouteEvidence($request, $order,  "5");  
    
        $action .= ', pedido sale a ruta , ';
        $action .= !empty( $numev ) ? 'se subieron '. $numev .' evidencias.' : 'no se subieron evidencias.';
        
        }
        

        // Cancelación de orden
        if( $request->status_id == 7 ){
            $file = $request->file('cancelation');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->cancel_reason_id);
            $razon = $razon->reason;
            $cancelation = Cancelation::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->cancel_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'cancelation_id' => $cancelation->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }

        }
        // Refacturación de orden
        if( $request->status_id == 8){
            $file = $request->file('rebilling');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Refacturaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->refact_reason_id);
            $razon = $razon->reason;
            $rebilling = Rebilling::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->refact_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Refacturaciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'rebilling_id' => $rebilling->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }
        }
        // Devolución de orden
        if( $request->status_id == 9){
            /*
            $file = $request->file('debolution1');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Devoluciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->debolution_reason_id);
            $razon = $razon->reason;
            $debolution = Debolution::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->debolution_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Devoluciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'debolution_id' => $debolution->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }
            */
            
            //CREATE DEVOLUCTION
            $debolution = Debolution::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->debolution_reason_id,
                'created_at' => now(),
            ]);
            
            //$devolutionId = intval($debolution->id);
            
            $numDevo = 0 ;
            //$numDevo += $this->StoreDebolution( $request, $order, $devolutionId, "1"); 
            //$numDevo += $this->StoreDebolution( $request, $order, $devolutionId, "2");
            //$numDevo += $this->StoreDebolution( $request, $order, $devolutionId, "3");
            //$numDevo += $this->StoreDebolution( $request, $order, $devolutionId, "4");
            //$numDevo += $this->StoreDebolution( $request, $order, $devolutionId, "5");
            
            $razon = Reason::find($request->debolution_reason_id);
            $razon = $razon->reason;
            $action .= ', se devolvió debido a un '. $razon;
            $action .= !empty($numDevo) ? ', se subieron '.$numDevo.' archivos' : ', no se subió archivo';
            
            //*****************************************************************************
        }

        // Orden de Requisición
        if( $request->iscovered == true || $request->purchaseorder || $request->document ){
            $purchaseorder = $order->purchaseorder;

            if ( $request->document || $request->requisition){
                $hoy = date("Y-m-d H:i:s");
                $path = NULL;
                $pathReq = NULL;

                if ($request->document){
                    $file = $request->file('document');
                    $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                    $path = 'OrdenesDeCompra/' . $name;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $file, $name );
                    $purchaseorder->update([
                        'number' => $request->purchaseorder,
                        'document' => $path,
                        'iscovered' => 1,
                    ]);
                }
                if ($request->requisition){
                    $fileReq = $request->file('requisition');
                    $nameReq = str_replace(' ','-', $request->invoice .'-'.$fileReq->getClientOriginalName());
                    $pathReq = 'OrdenesDeCompra/' . $nameReq;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $fileReq, $nameReq );
                    $purchaseorder->update([
                        'number' => $request->purchaseorder,
                        'requisition' => $pathReq,
                        'iscovered' => 1,
                    ]);
                }

                $action = $action . ', se cubrió la Orden de Requisición';

            }
            else{
                $purchaseorder->update([
                    'number' => $request->purchaseorder,
                    'iscovered' => 1,
                ]);
                $action = $action . ', se cubrió la Orden de Requisición';
            }
        }

        // Hacer favorito
        if($request->favorite){
            Follow::create([
                'user_id' => auth()->user()->id,
                'order_id' => $id
            ]);
        }
        // Añadir nota
        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
            $action = $action . ', '.auth()->user()->name.' añadió una nota';
        }
        // Verificar cambio de estatus
        if($request->status_id != $request->statAnt){
            $status = Status::find($request->status_id);
            $user = User::find(auth()->user()->id);
            $action = $action . ', se actualizó estatus a "'. $status->name . '"';
            Note::create([
                'note' => $user->name . ' cambió el estatus a '. $status->name,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
        }
        // Nuevos Parciales
        $folios = 0;
        if($request->folio1){
            Partial::create([
                'invoice' => $request->folio1,
                'order_id' => $order->id,
                'status_id' => $request->fol_status1
            ]);
            $folios++;
        }
        if($request->folio2){
            Partial::create([
                'invoice' => $request->folio2,
                'order_id' => $order->id,
                'status_id' => $request->fol_status2
            ]);
            $folios++;
        }
        if($request->folio3){
            Partial::create([
                'invoice' => $request->folio3,
                'order_id' => $order->id,
                'status_id' => $request->fol_status3
            ]);
            $folios++;
        }
        if($request->folio4){
            Partial::create([
                'invoice' => $request->folio4,
                'order_id' => $order->id,
                'status_id' => $request->fol_status4
            ]);
            $folios++;
        }
        if($request->folio5){
            Partial::create([
                'invoice' => $request->folio5,
                'order_id' => $order->id,
                'status_id' => $request->fol_status5
            ]);
            $folios++;
        }
        if($request->folio1){
            $action = $action . $user->name . ', añadió ' . $folios . ' parciales';
        }
        // Actualización de parciales
        if ( $order->partials->count() > 0 ){
            // dd('el pedido tiene '. $order->partials->count() .' parciales');
            if( $request->stPartID_1 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_1);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_1
                ]);
                if( !empty($request->partImg_1_1) || !empty($request->partImg_1_2) || !empty($request->partImg_1_3) || !empty($request->partImg_1_4) || !empty($request->partImg_1_5)  ){
                    $this->StorePartialImg($request, $partialID->id, "partImg_1");
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    $action.=" img parcial 1 ";
                    /*
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_1');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    */
                }
            }
            if( $request->stPartID_2 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_2);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_2
                ]);
                if( !empty($request->partImg_2_1) || !empty($request->partImg_2_2) || !empty($request->partImg_2_3) || !empty($request->partImg_2_4) || !empty($request->partImg_2_5)  ){
                    $this->StorePartialImg($request, $partialID->id, "partImg_2");
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    $action.=" img parcial 2 ";
                    /*
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_2');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    */
                }
            }
            if( $request->stPartID_3 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_3);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_3
                ]);
                if( !empty($request->partImg_3_1) || !empty($request->partImg_3_2) || !empty($request->partImg_3_3) || !empty($request->partImg_3_4) || !empty($request->partImg_3_5) ){
                    $this->StorePartialImg($request, $partialID->id, "partImg_3");
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    $action.=" img parcial 3 ";
                    /*
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_3');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    */
                }
            }
            if( $request->stPartID_4 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_4);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_4
                ]);
                if( !empty($request->partImg_14_1) || !empty($request->partImg_4_2) || !empty($request->partImg_4_3) || !empty($request->partImg_4_4) || !empty($request->partImg_4_5)  ){
                    $this->StorePartialImg($request, $partialID->id, "partImg_4");
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    $action.=" img parcial 4 ";
                    /*
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_4');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    */
                }
            }
            if( $request->stPartID_5 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_5);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_5
                ]);
                if( !empty($request->partImg_5_1) || !empty($request->partImg_5_2) || !empty($request->partImg_5_3) || !empty($request->partImg_5_4) || !empty($request->partImg_5_5)  ){
                    $this->StorePartialImg($request, $partialID->id, "partImg_5");
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    $action.=" img parcial 5 ";
                    
                    /*
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_5');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                    */
                }
            }
            $action = $action . '. Actualización de parciales';
        }
        // Creación de un log
        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        if( $request->status_id == 7 || $request->status_id == 8 ){
            // dd('cancelar para refacturación.');
        }

        return redirect()->route('orders.index');
    }
    
    
    
    
    function StoreRouteEvidence(Request $request,  $order, string $num="") : int {
        $file = $request->file('routeEvidence'.$num);
        $numSubidos = 0 ;
        
        if($file){
            $name = str_replace(' ','-', $request->invoice .'_'.$num.'-'.$file->getClientOriginalName());
            $path = 'Embarques/' . $name;
            //$extension = pathinfo($path, PATHINFO_EXTENSION);
        }else{
            $name = NULL;
            $path = NULL;
            //$extension = NULL;
        }

        if($file){

            Storage::putFileAs('/public/' . 'Embarques/', $file, $name );
            Shipment::create([
                'file' => $path,
                'order_id' => $order->id,
                'created_at' => now(),
            ]);
            $numSubidos=1;
        }
        
        return $numSubidos;
    }
    
    
    
    function StoreDebolution(Request $request, $order, int $devolutionId, string $num) : int {
        $number=0;      
        $path="";
        $name="";
        //SAVE FILE
        $file = $request->file("debolution".$num);

        if($file){
            $name = str_replace(' ','-', $request->invoice .'-'.$num.'-'.$file->getClientOriginalName());
            $path = 'Devoluciones/' . $name;
        }else{
            return 0;
        }     

        $isvalid = is_file($file->getRealPath());
        
        if($file && $isvalid && !empty($path) && !empty($name)){      
            //Storage::putFileAs('/public/' . 'Devoluciones/', $file, $name );
      
            $file->storeAs('/public/Devoluciones',$name);
            Evidence::create([
                'file' => $path,
                'debolution_id' => $devolutionId
            ]);
            $number=1;
        }
        
        return $number;
    }
    
    
    function StorePartialImg(Request $request, int $partialID, string $fieldName) : void {
        $hoy = date("Ymd_His");
        $max=5;
        for($m=0; $m < $max ; $m++){

            $file = $request->file($fieldName . "_". $m);
            if(empty($file)){continue;}
            $name = $hoy . '-' . $m . '-'. $file->getClientOriginalName();
            $path = 'Images/' . $name;
            Storage::putFileAs('/public/' . 'Images/', $file, $name );
            Picture::create([
                'picture' => $path,
                'user_id' => auth()->user()->id,
                'partial_id' => $partialID
            ]);    
            //echo "picture: ".$path." $name"."_".$m." partial_id: ".$partialID."|";
        }

    }
    
    
    
    
    
    public function attachdev(Request $request){
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        $rel = $request->rel;
        
        $urlParams = [];
        $urlParams["rel"]=$rel;
        $urlParams["catalog"]=$catalog;
        if(!empty($order_id)){$urlParams["order_id"]=$order_id;}
        if(!empty($partial_id)){$urlParams["partial_id"]=$partial_id;}
        if(!empty($cancelation_id)){$urlParams["cancelation_id"]=$cancelation_id;}
        if(!empty($rebilling_id)){$urlParams["rebilling_id"]=$rebilling_id;}
        if(!empty($debolution_id)){$urlParams["debolution_id"]=$debolution_id;}
        
        return view('orders.attachdev', compact('catalog','urlParams','rel') );
    }
    
    
    public function attachlist(Request $request){
        
        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();
        $list=[];
        
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        $rel = $request->rel;


        if(empty($catalog)){return "?";}
        
        if($catalog==="evidence"){
            if(!empty($cancelation_id)){
                $list = Evidence::where("cancelation_id",$cancelation_id)->get();
            }
            elseif(!empty($rebilling_id)){
                $list = Evidence::where("rebilling_id",$rebilling_id)->get();
            }
            elseif(!empty($debolution_id)){
                $list = Evidence::where("debolution_id",$debolution_id)->get();
            }
            else{
                return "?r";
            }
        }
        elseif($catalog==="pictures"){
            if(!empty($order_id)){
                $list = Picture::where("order_id",$order_id)->get();
            }
            elseif(!empty($partial_id)){
                $list = Picture::where("partial_id",$partial_id)->get();
            }
            else{
                return "?r";
            }
        }
        elseif($catalog==="shipments"){
            if(!empty($order_id)){
                $list = Shipment::where("order_id",$order_id)->get();
            }
            else{
                return "?r";
            }
        }
        else{
            return "?c";
        }      
        
        $urlParams = [];
        $urlParams["rel"]=$rel;
        $urlParams["catalog"]=$catalog;
        if(!empty($order_id)){$urlParams["order_id"]=$order_id;}
        if(!empty($partial_id)){$urlParams["partial_id"]=$partial_id;}
        if(!empty($cancelation_id)){$urlParams["cancelation_id"]=$cancelation_id;}
        if(!empty($rebilling_id)){$urlParams["rebilling_id"]=$rebilling_id;}
        if(!empty($debolution_id)){$urlParams["debolution_id"]=$debolution_id;}
        $url = url('order/attachlist?'.http_build_query($urlParams));
     
        return view('orders.attachlist', compact('list','catalog','url','rel','urlParams'));
    }
    
    
    
    public function attachpost(Request $request){
       // $order = Order::find($id);
       // $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        
        $ahora = date("Y-m-d H:i:s");
        
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        
        $identfield = "?";
        $ident=0;
        $identt="x";
        $folder="xx";
        
        if(!empty($order_id)){
            $identfield="order_id";
            $ident = $order_id;
            $identt="o";
            $folder="Images";
        }
        elseif(!empty($partial_id)){
            $identfield="partial_id";
            $ident = $partial_id;
            $identt="p";
            $folder="Images";
        }
        elseif(!empty($cancelation_id)){
            $identfield="cancelation_id";
            $ident = $cancelation_id;
            $identt="c";
            $folder="Cencelaciones";
        }
        elseif(!empty($rebilling_id)){
            $identfield="rebilling_id";
            $ident = $rebilling_id;
            $identt="r";
            $folder="Refacturaciones";
        }
        elseif(!empty($debolution_id)){
            $identfield="debolution_id";
            $ident = $debolution_id;
            $identt="d";
            $folder="Devoluciones";
        }
        
        
        $RE=new \stdClass();
        $RE->value="";
        $RE->status=0;
        $RE->error="";
        
        if(empty($ident)){
            $RE->error="Falta valor de orden o parcial.";
            return json_encode($RE); 
        }
        
        if($catalog === "pictures"){            
          
            $file = $request->file("upload");
            if(empty($file)){
                $RE->error="No se recibió imagen";
                $RE->status=0;
                return json_encode($RE);
            }

            $name = $identt . "-" . $ident . '-' . date("dHis") .".". $file->getClientOriginalExtension() ;   
            
            $numExists=0;
            if($identfield=="order_id"){
                $numExists = Order::where("id", intval($ident))->count();
            }elseif($identfield=="partial_id"){
                $numExists = Partial::where("id", intval($ident))->count();
            }
            
            
            if($numExists == 0){
                $RE->status = 0 ;
                $RE->error="El registro $identfield = $ident no existe";
                return json_encode($RE); 
            }
            
            Storage::putFileAs('/public/'.$folder.'/', $file, $name );            
            
            Picture::create([
                'picture' => $folder.'/' . $name,
                'user_id' => intval($user->id),
                $identfield => intval($ident),
                "created_at" => $ahora,
                "updated_at" => $ahora
            ]);  
            
            $RE->status=1;
            $RE->value=$name;
            return json_encode($RE);  
        }
        
        else if($catalog === "evidence"){
            
            $file = $request->file("upload");
            if(empty($file)){$RE->error="No se recibió imagen o documento";}
            //$file->getClientOriginalName()
            $name = $identt . "-" . $ident . '-' .date("dHis") .".". $file->getClientOriginalExtension() ;
            
            $numExists=0;
            switch($identfield){
                case "cancelation_id": 
                    $numExists = Cancelation::where("id",$ident)->count();
                    break;
                case "rebilling_id":
                    $numExists = Rebilling::where("id",($ident))->count();
                    break;
                case "debolution_id":
                    $numExists = Debolution::where("id",($ident))->count();
                    break;
            }
            
            
            if($numExists == 0){
                $RE->status = 0 ;
                $RE->error="El registro $identfield = $ident no existe";
                return json_encode($RE);
            }
            
            Storage::putFileAs('/public/'.$folder.'/', $file, $name );
            
            Evidence::create([
                'file' => $folder.'/' . $name,
                'user_id' => intval($user->id),
                $identfield => intval($ident),
                "created_at" => $ahora,
                "updated_at" => $ahora
            ]);
            
            $RE->status=1;
            $RE->value=$name;
            return json_encode($RE);  
            
        }
        
        else if($catalog === "shipments"){
            $folder="Embarques";
            $file = $request->file("upload");
            if(empty($file)){$RE->error="No se recibió imagen o documento";}
            //$file->getClientOriginalName()
            $name = $identt . "-" . $ident . '-' . date("dHis").".".$file->getClientOriginalExtension();
            
            $numExists = Order::where("id", $ident)->count();     
            
            
            if($numExists == 0){
                $RE->status = 0 ;
                $RE->error="El registro $identfield = $ident no existe";
                return json_encode($RE);
            }
            
            Storage::putFileAs('/public/'.$folder.'/', $file, $name );
            
            //$shipmentExists = Shipment::where($identfield,$ident)->get();
            Shipment::create([
                'file' => $folder.'/' . $name,
                $identfield => intval($ident),
                "created_at" => $ahora,
                "updated_at" => $ahora
            ]);
                       
            
            $RE->status=1;
            $RE->value=$name;
            return json_encode($RE);
            
        }

      
          
    }
    
    public function debolutioncreatefor(Request $request){
        $ahora = date("Y-m-d H:i:s");
        
        $order_id = $request->order_id;
        $reason_id = $request->reason_id;
        
        $RE= new \stdClass();
        
        $existeNum = Debolution::where("order_id",$order_id)->get();
        if(count($existeNum) > 0 ){
            $elId = $existeNum[0]->id;
            $RE->value=$elId;
            $RE->status=1;
            return json_encode($RE);
        }
        
        $newId = Debolution::create([
            'order_id'=> $order_id,
            'reason_id'=> $reason_id,
            'created_at'=>$ahora,
            'updated_at'=>$ahora
        ])->id;
        
        
        
        if(empty($newId)){
            $RE->status = 0;
            $RE->value= 0;
        }else{
            $RE->status = 1;
            $RE->value = $newId;
        }

        return json_encode($RE);
    }
    
    
    public function partialcreatefor(Request $request){
        $ahora = date("Y-m-d H:i:s");
        
        $order_id = $request->order_id;
        $status_id = $request->status_id;
        $invoice = $request->folio;
        
        $RE= new \stdClass();   
        
        $newId = Partial::create([
            'order_id'=> $order_id,
            'status_id'=> $status_id,
            "invoice" => $invoice,
            'created_at'=>$ahora,
            'updated_at'=>$ahora
        ])->id;        
        
        if(empty($newId)){
            $RE->status = 0;
            $RE->value= 0;
        }else{
            $RE->status = 1;
            $RE->value = $newId;
        }
        
        return json_encode($RE);
    }

    
    
    public function attachdelete(Request $request, $id=0){
        // $order = Order::find($id);
        // $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        if(empty($user->id)){return "";}
        
        $catalog = $request->catalog;
        $id = $request->id;
       // $order_id = $request->order_id;
        //$partial_id = $request->partial_id;
       // $ident = !empty($order_id) ? $order_id : $partial_id;
        //$identfield = !empty($order_id) ? "order_id" : "partial_id";
        $id = intval($id);
        
        $RE=new \stdClass();
        $RE->value=$id;
        $RE->status=0;
        $RE->error="";
        
        if($catalog =="pictures"){
        $img = Picture::find($id);
        if(empty($img)){
            $RE->status=0;
            $RE->error="Imagen no encontrada en base de datos";
            return json_encode($RE);
        }
        $img->delete($id);
        $RE->status=1;
        return json_encode($RE);  
        }
        elseif($catalog =="evidence"){
            $img = Evidence::find($id);
            if(empty($img)){
                $RE->status=0;
                $RE->error="Evidencia no encontrada en base de datos";
                return json_encode($RE);
            }
            $img->delete($id);
            $RE->status=1;
            return json_encode($RE);
        }
        elseif($catalog =="shipments"){
            $img = Shipment::find($id);
            if(empty($img)){
                $RE->status=0;
                $RE->error="Evidencia de embarque no encontrada en base de datos";
                return json_encode($RE);
            }
            $img->delete($id);
            $RE->status=1;
            return json_encode($RE);
        }
        
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        // dd($id, $order->status->name);
        $user = User::find(auth()->user()->id);

        $action = $user->name . ' archivó la orden';

        $order->update([
            'delete' => 1,
            'updated_at' => now()
        ]);

        // Creación de un log
        Log::create([
            'status' => $order->status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        return redirect()->route('orders.index');
    }
}
