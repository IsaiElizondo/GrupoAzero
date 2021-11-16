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

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orders = Order::all();
        $orders = Order::where('delete', NULL)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;


        return view('orders.index', compact('orders', 'role', 'department'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders = Order::all();
        $statuses = Status::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('orders.create', compact('orders', 'statuses', 'role', 'department'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        $action = $user->name .' creó una orden';

        if($request->credit){
            $credit = 1;
            $action = $action . ' a credito';
        }else{
            $credit = 0;
        }

        $order = Order::create([
            'office' => $request->office,
            'invoice' => $request->invoice,
            'invoice_number' => $request->invoice_number,
            'client' => $request->client,
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
            PurchaseOrder::create([
                'required' => $request->ocCheck,
                'number' => $request->purchase_order,
                'document' => NULL,
                'order_id' => $order->id
            ]);
            $action = $action . ', el pedido requiere una Orden de Requisición ';
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


        $orders = Order::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('orders.index', compact('orders', 'role', 'department'));
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
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($order->purchaseorder);

        return view('orders.show', compact('order', 'role', 'department'));
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
            $file = $request->file('routeEvidence');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Embarques/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }
            $action = $action . ', pedido sale a ruta';
            if($file){
                Storage::putFileAs('/public/' . 'Embarques/', $file, $name );
                $shipment = Shipment::create([
                    'file' => $path,
                    'order_id' => $order->id,
                    'created_at' => now(),
                ]);
                $action = $action . ', se subió evidencia con extensión '. $extension;
            }else{
                $action = $action . ', no se subió evidencia';
            }
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
            $file = $request->file('debolution');
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
        }

        // Orden de Requisición
        if( $request->iscovered == true || $request->purchaseorder || $request->document ){
            $purchaseorder = $order->purchaseorder;

            if ( $request->document ){
                $hoy = date("Y-m-d H:i:s");
                $file = $request->file('document');
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'OrdenesDeCompra/' . $name;
                Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $file, $name );
                $purchaseorder->update([
                    'number' => $request->purchaseorder,
                    'document' => $path,
                    'iscovered' => 1,
                ]);
                $action = $action . ', se cubrió la Orden de Requisición';

            }else{
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
                if( $request->partImg_1 ){
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
                }
            }
            if( $request->stPartID_2 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_2);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_2
                ]);
                if( $request->partImg_2 ){
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
                }
            }
            if( $request->stPartID_3 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_3);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_3
                ]);
                if( $request->partImg_3 ){
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
                }
            }
            if( $request->stPartID_4 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_4);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_4
                ]);
                if( $request->partImg_4 ){
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
                }
            }
            if( $request->stPartID_5 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_5);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_5
                ]);
                if( $request->partImg_5 ){
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
