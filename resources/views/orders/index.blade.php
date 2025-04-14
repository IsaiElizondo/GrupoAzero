@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos')])

@section('content')
<?php 
$statuses = isset($statuses) ? $statuses : []; 
$rpp = isset($rpp) ? $rpp : 10;
$total = isset($total) ? $total : 0;
$pag = isset($pag) ? $pag : 1 ; 
$mensaje = isset($mensaje) ? $mensaje : "";
$fecha = isset($fecha) ? $fecha :"";
$fechaDos = isset($fechaDos) ? $fechaDos :"";
$texto = isset($texto) ? $texto :"";
?>
<link href="{{ route("welcome")."/css/paginacion.css" }}" rel="stylesheet" />
<link href="{{ route("welcome")."/css/orders.css" }}" rel="stylesheet" />

    <div class="content">
        <div class="container-fluid">
            <div class="row">

            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                            <h4 class="card-title ">Pedidos</h4>
                            <p class="card-category"> {{ auth()->user()->name }} / {{ $role->name }} / {{ $department->name }} </p>
                        </div>
                        @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" )
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">
                                        add
                                    </span>
                                    Nuevo pedido
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($mensaje)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Alerta!</strong> {{ $mensaje ?? '' }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form class="navbar-form" method="GET" action="{{ route('orders.index') }}">
                        @csrf
                        @method('get')
                        <?php 
                        $busquedas = isset($busquedas) ? $busquedas: [] ;
                        App\Libraries\Tools::valores($busquedas);
                        
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group no-border">
                                            <!-- <label class="label-control">Buscar por fecha</label> -->
                                            <input type="text" name="fecha" class="form-control datetimepicker " placeholder="Fecha o fecha inicial..." 
                                            value="<?php 
                                            //echo App\Libraries\Tools::valor("fecha","");
                                             ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-check text-center">
                                            <p> / </p>
                                        </div>
                                    </div>
                                    <div class="col-5" id="fechaDos">
                                        <div class="form-group no-border">
                                            <!-- <label class="label-control">Buscar por fecha</label> -->
                                            <input type="text" name="fechaDos" class="form-control datetimepicker" placeholder="Fecha final (opcional)..."
                                            value="<?php 
                                            //echo App\Libraries\Tools::valor("fechaDos",""); 
                                            ?>" />
                                        </div>
                                    </div>
                                    <!-- 
                                    <div class="col-1">
                                        <div class="form-check text-center">
                                            <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                                <i class="material-icons">search</i>
                                            </button>
                                        </div>
                                    </div>
                                    -->
                                    <div class="col-12">
                                        <div class="input-group no-border">
                                            <div class="col-3">
                                                <input type="text" name="busquedaOrden" class="form-control" placeholder="Buscar por folio" 
                                                value="<?php 
                                                //echo App\Libraries\Tools::valor("order",""); 
                                                ?>" />
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="busquedaFactura"  class="form-control" placeholder="Buscar por factura" style="" 
                                                 value="<?php 
                                                 //echo App\Libraries\Tools::valor("factura",""); 
                                                 ?>">
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="busquedaCliente" class="form-control" placeholder="Buscar por cliente" style=""  
                                                value="<?php 
                                                //echo App\Libraries\Tools::valor("cliente",""); 
                                                ?>">
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="busquedaSucursal" class="form-control" placeholder="Buscar por sucursal" style=""
                                                 value="<?php 
                                                 //echo App\Libraries\Tools::valor("sucursal",""); 
                                                 ?>">
                                            </div>
                                            
                                            <div class="col-3">
                                               
                                                 <select name='busquedaEstatus'>
                                                 <option value=''> Cualquier estatus</option>
                                                  @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            		@endforeach
                                                 </select>
                                                 <label>Buscar por estatus</label>
                                           </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
							
							<div class="col-2">
							<button type="submit" class="btn btn-white btn-round btn-just-icon">
                                                <i class="material-icons">search</i>
                            </button>
                            </div>
							
                        </div>
                        @if ($fecha || $texto)
                            <div class="row">
                                <div class="col-12">
                                <hr/>
                                    <h5>Resultado de busqueda:
                                        @if( $fecha )
                                            fecha: {{ $fecha ?? '' }}
                                        @endif
                                        @if( $fechaDos )
                                            al: {{ $fechaDos ?? '' }}
                                        @endif
                                        @if( $texto )
                                            Criterio: {{ $texto ?? '' }}
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        @endif
                    </form>
					
					<p>&nbsp;</p>
					
                    <div class="table-responsive ">
                    <table class="table data-table" id="orders">
                        <thead>
                            <tr>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Sucursal</th>
                                <th class="text-center">Folio</th>
                                <th class="text-center col-hd">Factura</th>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Estatus</th>
                                <th class="text-center">Acciones</th>
                                {{-- @if ( $role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Ventas" || $department->name == "Fabricación")
                                    <th width="50px">&nbsp;</th>
                                @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <?php //var_dump($order); ?>
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->created_at->format('d-m-Y') }} <br>
                                            {{ $order->created_at->format('h:i') }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->office }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->invoice }}
                                        </a>
                                    </td>
                                    <td class="col-hd">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->invoice_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->client }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            {{ $order->status->name }}
                                        </a>
                                    </td>
                                    {{-- Sección de alertas --}}
                                    <td class="text-right">
                                        {{-- Alerta de pedido fabricado, recordar subir foto al salir a ruta --}}
                                        @if ( $order->status->name ==  'Fabricado')
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm tte"  data-placement="top" title="Recuerda que para salir a ruta debes tomar evidencias">
                                                <span class="material-icons">
                                                    priority_high
                                                </span>
                                                
                                                
                                                <div class='toolTipExtra alert'>
                                                Recuerda que para salir a ruta debes tomar evidencias
                                                </div>
                                                
                                                {{-- {{ $order->partials->count() }} --}}
                                            </a>
                                        @endif
                                        {{-- Fin de alerta de pedido fabricado --}}
                                        {{-- Pedido con parciales --}}
                                        @if ( $order->partials->count() > 0 )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm tte"  data-placement="top" title="Pedido con {{ $order->partials->count() }} entregas parciales">
                                                <span class="material-icons">
                                                    alt_route
                                                </span>
                                                <div class='toolTipExtra'>
                                                <p class='em'>Pedido con {{ $order->partials->count() }} entregas parciales</p>
                                                @foreach ($order->partials as $parti) 
                                                	<p>{{$parti->invoice}}</p>
                                                @endforeach
                                                </div>
                                                
                                                {{ $order->partials->count() }}
                                            </a>
                                        @endif
                                        {{-- Fin de pedido con parciales --}}
                                        {{-- Pedido a crédito --}}
                                        @if ( $order->credit == true )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Pedido a crédito">
                                                <span class="material-icons">
                                                    credit_card
                                                </span>
                                            </a>
                                        @endif
                                        {{-- Fin de pedido a crédito --}}
                                        {{-- Pedido con orden de fabricación --}}
                                        @if ( $order->manufacturingorder )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm tte"  data-placement="top" title="Con orden de fabricación {{ $order->manufacturingorder->number }}">
                                                <span class="material-icons">
                                                    precision_manufacturing
                                                </span>
                                                
                                                <div class="toolTipExtra">
                                                <div class='em'>Con órden de fabricacion</div>
                                                <p> {{ $order->manufacturingorder->number }}</p>
                                                </div>
                                                
                                                
                                            </a>
                                        @endif
                                        {{-- Fin de pedido con orden de fabricación --}}
                                        {{-- Pedido con Orden de Requisición --}}
                                        @if ( isset($order->purchaseorder->required) )
                                            @if ( $order->purchaseorder->iscovered )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Pedido con Orden de Requisición">
                                                    <span class="material-icons">
                                                        fact_check
                                                    </span>
                                                </a>
                                            @else
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Pedido con Orden de Requisición">
                                                    <span class="material-icons">
                                                        fact_check
                                                    </span>
                                                </a>
                                            @endif
                                        @endif
                                        {{-- Fin de pedido con orden de compa --}}
                                        {{-- Subir foto en ruta --}}
                                        @if ( $order->status_id == 6 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla") && $order->pictures->count() == 0 )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Falta subir foto de entregado">
                                                <span class="material-icons">
                                                    photo_camera
                                                </span>
                                            </a>
                                        @endif
                                        @if ( $order->status_id == 5 && ($role->name == "Administrador" || $department->name == "Embarques" || $department->name == "Flotilla") )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir foto">
                                                <span class="material-icons">
                                                    photo_camera
                                                </span>
                                            </a>
                                        @endif
                                        @if ( $order->status_id == 6 && ($order->pictures->count() > 0) )
                                            <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="{{ $order->pictures->count() }} fotos">
                                                <span class="material-icons">
                                                    photo_camera
                                                </span>
                                                {{ $order->pictures->count() }}
                                            </a>
                                        @endif
                                        {{-- Fin subir foto  en ruta--}}

                                        {{-- 7- Cancelaciones --}}
                                            {{-- Subir foto de nota de devolución o crédito --}}
                                            @if ( ($order->status_id == 7) && $role->name == "Administrador" && ($order->cancelation->repayments->count() == 0) )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir foto de reembolso o  de devolucipon de crédito">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                </a>
                                            @endif
                                            @if ( ($order->status_id == 7) && $role->name == "Administrador" && ($order->cancelation->repayments->count() > 0) )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="{{$order->cancelation->repayments->count()}} Fotos">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                    {{ $order->cancelation->repayments->count() }}
                                                </a>
                                            @endif
                                            {{-- fin subir foto de nota de devolución o créditon --}}
                                            {{-- Evidencia de Cancelación --}}
                                            @if ( $order->status_id == 7 && $role->name == "Administrador" && $order->cancelation->evidences->count() == 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir evicencia cancelación">
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                </a>
                                            @endif
                                            @if ( $order->status_id == 7 && $role->name == "Administrador" && $order->cancelation->evidences->count() > 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary btn-link btn-sm tte" data-placement="top" title="Existe evicencia o razón de cancelación">
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                    <div class="toolTipExtra">
                                                    <div class="em">Existe {{ $order->cancelation->evidences->count() }} evicencia o razón de cancelación</div>
                                                    @foreach ($order->cancelation->evidences as $evid) 
                                                    	<p><img src="{{  asset('storage/'.$evid->file) }}" height="50" /> &nbsp; Cancelación # {{ $evid->cancelation_id }}</p>
                                                    @endforeach
                                                    </div>
                                                    
                                                </a>
                                            @endif
                                            {{-- Fin Evidencia de cancelacion --}}
                                        {{-- Fin cancelaciones --}}

                                        {{-- 8. Rebillings --}}
                                            {{-- Subir foto de nota de devolución o crédito --}}
                                            {{-- @if ( $order->status_id == 8 && $role->name == "Administrador" && $order->rebilling->repayments->count() == 0 ) --}}
                                            @if ( $order->status_id == 8 && $role->name == "Administrador" )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir foto de reembolso o  de devolucipon de crédito">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                </a>
                                            @endif
                                            {{-- @if ( $order->status_id == 8 && $role->name == "Administrador" && $order->rebilling->repayments->count() > 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="{{$order->rebilling->repayments->count()}} Fotos">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                    {{ $order->rebilling->repayments->count() }}
                                                </a>
                                            @endif 
                                            {{-- Fin Subir foto de nota de devolución o crédito --}}
                                            {{-- Evidencias --}}
                                            {{-- @if ( $order->status_id == 8 && $role->name == "Administrador" && $order->rebilling->evidences->count() == 0 ) --}}
                             
                                            @if ( $order->status_id == 8 && $role->name == "Administrador"  &&  (empty($order->rebilling->evidences) || $order->rebilling->evidences->count() == 0) )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir evicencia refacturación">
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                </a>
                                            @endif
                                            
                                            @if ( $order->status_id == 8 && $role->name == "Administrador" && !empty($order->rebilling->evidences) && $order->rebilling->evidences->count() > 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary btn-link btn-sm tte"  data-placement="top" title="Existe evidencia o razón de refacturación" >
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                    
                                                    <div class="toolTipExtra">
                                                    <div class="em">{{ $order->rebilling->evidences->count()  }} evidenciasde refacturación</div>
                                                    @foreach ($order->rebilling->evidences as $ev) 
                                                    	<p><img src='{{ asset("storage/".$ev->file) }}' height='50' />  &nbsp; {{ $ev->rebilling_id }}</p>
                                                    @endforeach
                                                    </div>
                                                  
                                                    
                                                    
                                                </a>
                                        	@endif  
                                            
                                            
                                            {{-- Fin evidencias --}}
                                        {{-- Fin rebillings --}}
                                        

                                        {{-- 9. Devoluciones --}}
                                            {{-- Subir foto de nota de devolución o crédito --}}
                                            @if ( $order->status_id == 9 && $role->name == "Administrador" && ($order->debolution->repayments->count() == 0) )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir foto de reembolso o de devolución de crédito 9">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                </a>
                                            @endif
                                            @if ( $order->status_id == 9 && $role->name == "Administrador" && ($order->debolution->repayments->count() > 0) )
                                                <a href="{{ route('orders.show', $order->id) }}" type="submit" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="{{$order->debolution->repayments->count()}} Fotos">
                                                    <span class="material-icons">
                                                        photo_camera
                                                    </span>
                                                    {{ $order->debolution->repayments->count() }}
                                                </a>
                                            @endif
                                            {{-- Fin Subir foto de nota de devolución o crédito --}}
                                            {{-- Evidencias --}}
                                            @if ( $order->status_id == 9 && $role->name == "Administrador" && $order->debolution->evidences->count() == 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-danger btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Subir evicencia devolución">
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                </a>
                                            @endif
                                            @if ( $order->status_id == 9 && $role->name == "Administrador" && $order->debolution->evidences->count() > 0 )
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Existe evicencia o razón de devolución">
                                                    <span class="material-icons">
                                                        description
                                                    </span>
                                                    {{ $order->debolution->evidences->count() }}
                                                </a>
                                            @endif
                                            {{-- Fin evidencias --}}
                                        {{-- Fin Devoluciones --}}

                                        {{-- Fin evidencia de cancelación --}}
                                        {{-- Ver y editar --}}
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-link btn-sm tte"  data-placement="top" title="Notas">
                                            <span class="material-icons">note</span>
                                            
                                            <div class="toolTipExtra">
                                            <p class="em">{{ $order->notes->count() }} Notas</p>
                                            @foreach ($order->notes as $tnote)
                                           	 <p>{{ $tnote->note }}</p>
                                            @endforeach
                                            </div> 
                                            
                                        </a>
                             
                                        
                                        
                                        
                                        @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" || $department->name == "Fabricación" || $department->name == "Compras")
                                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-link btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                                                <span class="material-icons">
                                                    edit
                                                </span>
                                            </a>
                                        @endif
                                        {{-- Fin de ver y editar --}}
                                    </td>
                                    {{-- Fin de sección de alertas --}}
                                    {{-- @if ( $role->name == "Administrador" || $department->name == "Ventas" || $department->name == "Embarques" || $department->name == "Fabricación")
                                        <td>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary btn-link btn-sm">
                                                <span class="material-icons">
                                                    edit
                                                </span>
                                            </a>
                                        </td>
                                    @endif --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    {{-- $orders->links() --}}
                    <?php 
                    //var_dump($pag);
                    App\Libraries\Paginacion::rpp($rpp);
                    App\Libraries\Paginacion::total($total);
                    App\Libraries\Paginacion::actual($pag);
                    App\Libraries\Paginacion::$pag_var="page";
                    
                    $qs = "?order=".App\Libraries\Tools::valor("order","")
                    ."&factura=".App\Libraries\Tools::valor("factura","")
                    ."&cliente=".App\Libraries\Tools::valor("cliente","")
                    ."&sucursal=".App\Libraries\Tools::valor("sucursal","")
                    ."&fecha=".App\Libraries\Tools::valor("fecha","")
                    ."&fechaDos=".App\Libraries\Tools::valor("fechaDos","");
                    
                    echo App\Libraries\Paginacion::render(url("orders").$qs,"multi");
                                    ?>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

<!-- <script>
    $(document).ready(function() {
        $('#orders').DataTable({
            // "serverSide": true,
            // "ajax": "{{ url('api/orders') }}",
            // "columns": [
            //     {data: 'id'},
            //     {data: 'name'},
            //     {data: 'email'},
            //     {data: 'btn'},
            // ],
            ordering: true,
            order: [[1, "desc" ]],

            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    });
</script> -->

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    

    
</script>
<script type='text/javascript' src='{{route("welcome")}}/js/tooltipExtra.js'></script>

<script type="text/javascript">
    function addInvoice() {
        element = document.getElementById("fechaDos");
        ranDate = document.getElementById("ranDate");
        if (ranDate.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script>

{{-- <script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
  </script> --}}

    <script type="text/javascript">
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
@endpush
