<?php
use App\Pedidos2;
use App\Libraries\Tools;
use App\Follow;


?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

$follow = Follow::where( ["user_id"=> $user->id, "order_id" => $pedido->id] )->first();
//var_dump($follow);

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/attachlist.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css?x='.rand(0,999)) }}" />

<link rel="stylesheet" href="{{ asset('css/etiquetas/etiquetas.css?x='.rand(0,999)) }}" />

<main class="content">


<!--
<?php
var_dump($pedido);
?>
-->


    <div class="card Fila">

        <center> <a class="regresar" href="{{ url('pedidos2') }}">&laquo; Regresar a Pedidos</a> </center>
        
    </div>
    <div class="card Fila">

        <center><a class="regresar" href="{{ url('pedidos2/dashboard') }}">&laquo;Regresar a Mis Pendientes</a>
        
    </div>


    <div class="card">

        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Pedido {{ !empty($pedido->invoice_number) ? $pedido->invoice_number : $pedido->invoice }}</h4>
            </div>
        </div>

        <div>&nbsp;</div>

    <form action="{{ url('pedidos2/guardar/'.$pedido->id) }}" method="post" enctype="multipart/form-data">
        @csrf

            <fieldset class='MainInfo'>
                @if ($pedido->origin != "R" && ($user->role->id == 1 || in_array($user->department->id, [2,3,4,7,9]) ) )

                    
                <div class='FormRow'>
                    <label>Folio Cotización</label>
                    @if (empty($pedido->invoice) || $user->role->id == 1)
                        <input type="text" class="form-control" name="invoice" value="{{$pedido->invoice}}" />
                    @else
                        <span title="Sólo un administrador puede cambiar este dato">{{$pedido->invoice}}</span>
                    @endif
                </div>
                    

                <div class='FormRow'>
                    <label>Archivo Cotización</label>
                    <div class="flex-start">
                        @if (empty($quote->document) || $user->role->id == 1)
                        <input type="file" class="form-control" name="cotizacion"  />  
                        @endif
                        
                        @if (!empty($quote->document))
                        <span> &nbsp; &nbsp; Actual: 
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$quote->document) }}"></a>
                        </span>
                        @endif 
                        
                    </div>
                </div>


                <div class='FormRow'>
                    <label># Factura</label>
                    @if (empty($pedido->invoice_number) || $user->role->id == 1)
                    <input type="text" class="form-control" name="invoice_number" value="{{$pedido->invoice_number}}" />
                    @else 
                    <span title="Sólo un administrador puede cambiar este dato">{{$pedido->invoice_number}}</span>
                    @endif
                </div>

                <div class='FormRow'>
                    <label>Archivo Factura</label>
                    
                        <div class="flex-start">
                        @if (empty($pedido->invoice_document)|| $user->role_id == 1 )
                        <input type="file" class="form-control" name="factura" />
                        @endif


                        @if (!empty($purchaseOrder) && !empty($purchaseOrder->document) )
                        <span>  &nbsp; &nbsp; Actual: 
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$purchaseOrder->document) }}"></a>
                        </span> 
                        @elseif (!empty($pedido->invoice_document))
                        <span>  &nbsp; &nbsp; Actual: 
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$pedido->invoice_document) }}"></a>
                        </span>              
                        @endif
                        
                        </div>
                </div>
                @endif 



                
                <div class='FroRow'>
                    <label> Cliente </label>
                    <span style="padding-left:100px;">{{$pedido->client}}</span>
                </div>
                
                
                @if ($pedido->origin == "R")
                <div class='FormRow'>
                    <label>&nbsp;</label>
                    <span>Los pedidos con origen Requisición Stock no tienen información principal que cambiar.</span>
                </div>
                @endif



                <div class='FormRow'>
                    <label></label>
                    <span>
                        @if ($pedido->origin != "R")
                        <input type="submit" name="sb" class="form-control" value="Guardar" /> 
                        @endif

                        <input type="button" name="cn" class="form-control" value="Cancelar" onclick="EsconderMainInfo()" />
                    </span>
                </div>


            </fieldset>

            {{--/////////////////////////////--}}
            {{--// MODIFICACION DE DIRECCION --}} 
            {{--/////////////////////////////--}}

                <fieldset class="DireccionInfo" style="display:none;">
                    <legend> Direccion del pedido </legend>
                        <div id="formDireccionPedido" data-url="{{ route('guardar_direccion', $pedido->id) }}">
                            <div class="FormRow">
                                <label> Tipo de entrega</label>
                                <select name="estado_direccion" id="estado_direccion" class="form-control">
                                    <option value="pendiente" {{ $pedido->estado_direccion == 'pendiente' ? 'selected' : ''}}>Direccion Pendiente </option>
                                    <option value="completa" {{ $pedido->estado_direccion == 'completa' ? 'selected' : ''}}> Direccion Completa </option>
                                    <option value="recoge" {{ $pedido->estado_direccion == 'recoge' ? 'selected' : ''}}> Cliente Recoge </option>
                                </select>
                            </div>

                            @if(!empty($pedido->cliente_id))
                                <div class="FormRow">
                                    <label> Direccion del cliente </label>
                                    <select name="modo_direccion" id="modo_direccion" class="form-control">
                                        <option value="">-- Selecciona una opcion --</option>
                                        <option value="existente"> Usar una direccion existente </option>
                                        <option value="nueva"> Agregar nueva direccion nueva </option>
                                    </select>
                                </div>

                                <div id="bloqueDireccionExistente" style="display:none;">
                                    <div class="FormRow">
                                        <label> Direcciones registradas </label>
                                        <select name="cliente_direccion_id" id="cliente_direccion_id" class="form-control">
                                            <option value="">-- Seleccione una direccion --</option>
                                            @foreach($DireccionesCliente as $dir)
                                                <option value="{{ $dir->id }}"> 
                                                    {{ $dir->direccion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div id="bloqueDireccionCompleta" style="display:none;">
                                <div class="FormRow">
                                    <label> Nombre de la direccion </label>
                                    <input type="text" name="nombre_direccion" class="form-control" value="{{ $pedido->nombre_direccion }}">
                                </div>

                                <div class="FormRow">
                                    <label> Tipo de residencia </Label>
                                    <select name="tipo_residencia" class="form-control">
                                        <option value=""> -- Seleccione -- </option>
                                        <option value="residencial" {{ $pedido->tipo_residencia == 'residencial' ? 'selected' : '' }}> Residencial </option>
                                        <option value="obra" {{ $pedido->tipo_residencia == 'obra' ? 'selected' : ''}}> Obra </option>
                                        <option value="taller" {{ $pedido->tipo_residencia == 'taller' ? 'selected' : '' }}> Taller </option>
                                        <option value="industria" {{ $pedido->tipo_residencia == 'industria' ? 'selected' : ''}}> Industria </option>
                                    </select>
                                </div> 
                                
                                <div class="FormRow">
                                    <label> Direccion </label>
                                    <input type="text" name="direccion" class="form-control" value="{{ $pedido->direccion }}">
                                </div>

                                <div class="FormRow">
                                    <label> Colonia </label>
                                    <input type="text" name="colonia" class="form-control" value="{{ $pedido->colonia }}">
                                </div>
                                
                                <div class="FormRow">
                                    <label> Ciudad </label>
                                    <input type="text" name="ciudad" class="form-control" value="{{ $pedido->ciudad }}">
                                </div>

                                <div class="FormRow">
                                    <label> Estado </label>
                                    <input type="text" name="estado" class="form-control" value="{{ $pedido->estado }}">
                                </div>

                                <div class="FormRow">
                                    <label> Codigo Postal </label>
                                    <input type="text" name="codigo_postal" class="form-control" value="{{ $pedido->codigo_postal }}">
                                </div>

                                <div class="FormRow">
                                    <label> Celular </label>
                                    <input type="text" name="celular" class="form-control" value="{{ $pedido->celular }}">
                                </div>

                                <div class="FormRow">
                                    <label> Telefono </label>
                                    <input type="text" name="telefono" class="form-control" value="{{ $pedido->telefono }}">
                                </div>

                                <div class="FormRow">
                                    <label>¿Quién recibe?</label>
                                    <input type="text" name="nombre_recibe" class="form-control" value="{{ $pedido->nombre_recibe }}">
                                </div>
                                
                                <div class="FormRow">
                                    <label> URL Mapa</label>
                                    <input type="text" name="url_mapa" class="form-control" value="{{ $pedido->url_mapa }}"> 
                                </div>

                                <div class="FormRow">
                                    <label>Requerimientos especiales</label>
                                    @foreach($RequerimientosEspeciales as $Req)
                                        <label style="display:block">
                                            <input type="checkbox"
                                                name="requerimientos[]"
                                                value="{{ $Req->id }}"
                                                {{ in_array($Req->id, $RequerimientosDireccion ?? []) ? 'checked' : '' }}>
                                            {{ $Req->nombre }}
                                        </label>
                                    @endforeach
                                </div>  

                                <div class="FormRow">
                                    <label> Instrucciones </label>
                                    <textarea name="instrucciones" class="form-control">{{ $pedido->instrucciones }}</textarea>
                                </div>
                            </div>
                            
                            <button type="button" id="btnGuardarDireccion" class="form-control">
                                Guardar direccion
                            </button>
                            <div class="block">
                                <input type="button" class="form-control" value="Cancelar" onclick="EsconderDireccion()"/>
                            </div>
                        </div>
                </fieldset>
            
            
            {{--///////////////////////////////////--}}
            {{--//FIN DE MODIFICACION DE DIRECCION --}}
            {{--///////////////////////////////////--}}


            <fieldset class='MiniInfo'>

                @if ($pedido->origin != "R")
                    <div>
                        <label># Factura</label><span>{{$pedido->invoice_number}}
                        <?php 
                        //var_dump($purchaseOrder) 
                        ?>
                        @if (!empty($purchaseOrder->document))
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$purchaseOrder->document) }}"></a>
                        @elseif (!empty($pedido->invoice_document))
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$pedido->invoice_document) }}"></a>
                        @endif 

                    </span>
                    </div>

                    <div>
                        <label>Folio Cotización</label>
                        <span>{{$pedido->invoice}} 
                        @if (!empty($quote->document))
                        &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$quote->document) }}"></a>
                        @endif 
                        </span>
                    </div>
            

                    <div><label>Cliente</label><span>{{$pedido->client}}</span></div>
                    @if($pedido->nombre_cliente)
                        <div><label>Nombre Cliente</label><span>{{$pedido->nombre_cliente}}</span></div>
                    @endif

                    @if($pedido->nombre_direccion)
                        <div><label>Nombre Dirección</label><span>{{$pedido->nombre_direccion}}</span></div>
                    @endif

                    @if($pedido->tipo_residencia)
                        <div>
                            <label>Tipo de residencia</label>
                            <span>{{ ucfirst($pedido->tipo_residencia) }}</span>
                        </div>
                    @endif

                    <div>
                        <label>Dirección</label>
                        <span>
                            @if($pedido->estado_direccion == 'recoge')
                                Cliente recoge en sucursal
                            @elseif($pedido->estado_direccion == 'pendiente')
                                Dirección pendiente por definir
                            @else
                                {{$pedido->direccion}}
                                @if($pedido->ciudad), {{$pedido->ciudad}} @endif
                                @if($pedido->estado), {{$pedido->estado}} @endif
                                @if($pedido->codigo_postal), C.P. {{$pedido->codigo_postal}} @endif
                            @endif
                        </span>
                    </div>

                    @if($pedido->celular)
                        @php
                            $celular = preg_replace('/[^0-9]/', '', $pedido->celular);
                        @endphp
                        <div>
                            <label>Celular</label>
                            <span>
                                <a href="https://wa.me/52{{$celular}}" target="_blank">
                                    {{$pedido->celular}}
                                </a>
                            </span>
                        </div>
                    @endif

                    @if($pedido->telefono)
                        @php
                            $telefono = preg_replace('/[^0-9]/', '', $pedido->telefono);
                        @endphp
                        <div>
                            <label>Telefono</label>
                            <span>
                                <a href="https://wa.me/52{{$telefono}}" target="_blank">
                                    {{$pedido->telefono}}
                                </a>
                            </span>
                        </div>
                    @endif

                    
                    @if($pedido->nombre_recibe)
                        <div><label>Persona que recibe</label><span>{{$pedido->nombre_recibe}}</span></div>
                    @endif
                    
                    @if($pedido->url_mapa)
                        <div>
                            <label>URL</label>
                            <span>
                                <a href="{{$pedido->url_mapa}}" target="_blank" rel="noopener noreferrer">
                                    {{$pedido->url_mapa}}
                                </a>
                            </span>
                        </div>
                    @endif
                    
                    @if(!empty($RequerimientosTexto))
                        <div>
                            <label>Requerimientos</label>
                            <span>{{ implode(', ', $RequerimientosTexto) }}</span>
                        </div>
                    @endif

                    
                    @if($pedido->instrucciones)
                        <div><label>Instrucciones</label><span>{{$pedido->instrucciones}}</span></div>
                    @endif
                    
                    <div>
                        <label>Estado Dirección</label>
                        <span>{{ucfirst($pedido->estado_direccion)}}</span>
                    </div>

                @endif

            </fieldset>

        <div class="padded flex-float">
            <a class="powerLink modalShow" href="{{  url('pedidos2/historial/'.$pedido->id) }}">Historial</a>  


            @if ($user->role->id == 1  || in_array($user->department->id, [2,3,4,7]) )    
                <div class="block"><a class="powerLink" onclick="MostrarMainInfo()">Cambiar datos principales</a></div>
            @endif
            
            @if($user->role->id == 1 || in_array($user->department->id, [2,3,4,7]) && $pedido->origin != "R")
                <div class="block">
                    <a class="powerLink" onclick="MostrarDireccion()"> Modificar direccion </a>
                </div>
            @endif


            @if ($follow == null)
                <a href="{{  url('pedidos2/set_followno/'.$pedido->id.'/'.$user->id) }}" 
                hrefno="{{  url('pedidos2/set_follow/'.$pedido->id.'/'.$user->id) }}" class="followBtn no"> A mis pedidos</a>
            @else
            <a href="{{  url('pedidos2/set_followno/'.$pedido->id.'/'.$user->id) }}" 
                hrefno="{{  url('pedidos2/set_follow/'.$pedido->id.'/'.$user->id) }}" class="followBtn"> Dejar de seguir</a>
            @endif

        </div>
        
    </form> 
    

<?php

$statusName = $pedido->status_name;
$pedidoStatusId = $pedido->status_id;

?>

    <div class="BigEstatusSet">
    <div class="BigEstatus E{{ $pedidoStatusId }}"><span >{{ $statusName }}</span></div>
    <a class="reload" href="{{ url()->current() }}"></a>
    </div>
    


    <div class="center">Último cambio: {{ Tools::fechaMedioLargo($pedido->updated_at, true) }}</div>


    <blockquote class="Notes">
    @foreach ($notes as $note)
        <div class="Note">
        <p>{{$note->note}}</p>
        <div><small>{{ $note->getUserOf($note->id)->name }} {{Tools::fechaMedioLargo($note->created_at) }}</small></div>
        </div>
    @endforeach

        
    
    </blockquote>

    <div class="FilaAddNotes"><a class="clickable" onclick="MostrarNotasForm()">+ Nota</a></div>
    

    <form class="NotasForm" action="{{ url('pedidos2/add_nota/'.$pedido->id) }}" method="post">
    @csrf
    <div class="Fila">
        <label>Agregar Nota</label>
            <div class="Fila"><textarea name="texto" class="form-control semivisible" maxlength="180" cols="30" rows="2"></textarea></div>
            <div class="Fila"><input type="submit" class="form-control" value="Agregar" /></div>
      
    </div>
    </form>



    <p>&nbsp;</p>
</div>

@if(in_array(auth()->user()->role->name, ['Administrador', 'ALEJANDRO GALICIA']) || in_array(auth()->user()->department_id, [2, 4]))
<div class="card Fila">
    <div class="FormRow">
        <label>Fecha de entrega programada</label>
        <form method="POST" action="{{ url('pedidos2/entrega-programada/'.$pedido->id) }}">
            @csrf
            <div class="flex-start">
                <input type="date" name="entrega_programada_at" class="form-control" value="{{ $pedido->entrega_programada_at ? \Carbon\Carbon::parse($pedido->entrega_programada_at)->format('Y-m-d') : '' }}">
                <input type="submit" class="form-control" value="Guardar" style="width:150px; margin-left:10px;" />
            </div>
        </form>
    </div>
</div>
@endif





<div class="card">
    <div class="headersub">
    Progreso
    </div>

    <div class="Cuerpo" id="CuerpoActualizar">       

    <?php                                                                                                                                                                          

    ?>
        <div class="Eleccion">
        @if ($pedido->status_id == 1 )

            @if ($user->role_id == 1 || $user->department_id == 4)
            <a class="Accion generico" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=recibido') }}">Recibido por embarques</a>
            @endif

        @endif

        
        @if ( 
           
        $parciales->isEmpty()==true && $pedido->origin !="R" 
        && $pedido->status_5==0 && $pedido->status_6==0 
        && ($user->role_id ==1 || in_array($user->department_id,[2,8]) )
        )  
        <!--  !empty($pedido->invoice_number) &&   -->
            <a class="Accion enpuerta" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>        


        @elseif (empty($pedido->invoice_number) && $parciales->isEmpty()==true && $pedido->origin !="R")
            <!-- <span>Falta # de Factura para sacar a puerta</span> -->
            <span class="Alerta" title="Falta # de Factura para sacar a puerta">!</span> 
        @endif


        <!-- <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a> -->
        
        @if($pedido->status_id == 5 && (in_array(auth()->user()->role->name, ["Administrador","Empleado"]) && in_array(auth()->user()->department->name, ["Administrador", "Embarques"])))
            <a class="Accion alerta modalShow" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=noexitosa') }}">
            Entrega no exitosa
            </a>
        @endif


        @if (  $pedido->status_id < 6 && 
            ($user->role_id ==1 || in_array($user->department_id,[4,5,6,7,8,9]))
          )

        <a class="Accion entregado" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=entregar') }}">Entregado (Factura amarilla)</a>

        @endif


        @if ( ($user->role_id ==1 || in_array($user->department->id,[2,9])) && $pedido->status_id == 10  )

        <a class="Accion desauditoria" title="Deshacer recibido por auditoria" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=desauditoria') }}">Deshacer Recibido por Auditoria</a>

        @endif


        @if (  $pedido->status_id < 10 && 
            ($user->role_id ==1 || in_array($user->department_id,[9]))
          )

        <a class="Accion audita" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=audita') }}">Recibido por Auditoria</a>

        @endif

        
        </div>
    </div>



    @if ( $shipments->isEmpty() == false)
 
    @foreach ($shipments as $ship)
    <aside class="Proceso">
        <div class="gridThree">
            <span class="a"><div class="MiniEstatus E5"> Paso por Puerta: {{  ($ship->type == 1) ? "Chofer entrega" : "Recogió Cliente" }}</div></span>
            <span class="b">
             {{ Tools::fechaMedioLargo($ship->created_at) }}
            </span>
            <span class="last">

            @if ($user->role_id==1 || in_array($user->department_id,[2,4,8]))    
                <a class="btn  editapg" href="{{ url('pedidos2/shipment_edit/'.$ship->id) }}">Editar</a>
            @endif 

            </span>
        </div>
    
    <section mode="view" class='attachList' rel='ship_{{ $ship->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=shipments&shipment_id='.$ship->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship_'. $ship->id .'&mode=view&catalog=shipments&shipment_id='.$ship->id) }}"></section> 
    </aside>

    @endforeach
    @endif


    @if ($pedido->status_6 == 1)
    <aside class="Proceso">
        <div class="gridThree">
            <span class="a"><div class="MiniEstatus E6">Entrega</div></span>
            <span class="b">
            &nbsp;
            </span>
            <span class="last">

            @if ($user->role_id==1 || in_array($user->department_id,[2,4,8]))    
            <a class="btn  editapg" href="{{ url('pedidos2/entregar_edit/'.$pedido->id) }}">Editar</a>
            @endif

            </span>
        </div>

    <section mode="view" class='attachList' rel='entrega_{{ $pedido->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=pictures&event=entregar&order_id='.$pedido->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship_'. $pedido->id .'&mode=view&catalog=pictures&event=entregar&order_id='.$pedido->id) }}">
    </section> 
    
    </aside>
    @endif



@if ( isset($stockreq->id) ) 
    <aside class="Proceso">
        <div class="space-between ">
            <div class="a">
                <div><b>Requisicion Stock</b></div>  
                <div>#{{$stockreq->number}}</div> 
            </div>
            
            <div class="b">
            {{ !empty($stockreq->document) ? view('pedidos2/view_storage_item',["path"=>$stockreq->document]) :"" }}
            </div>

            @if ($pedido->status_4 == 1)
            <div class="statusItem">
                <div class="MiniEstatus E4">Surtido</div>
            </div>
            @endif

            @if ($pedido->status_6 == 1)
            <div class="statusItem">
                <div class="MiniEstatus E6">Entregado</div>
            </div>
            @endif
            
            <div class="last">

            @if ($user->role_id==1 || in_array($user->department_id,[2,4,8]))    
                <a class="btn  editapg" href="{{ url('pedidos2/stockreq_edit/'.$stockreq->id) }}">Editar</a>
            @endif

            </div>
        </div>    
    </aside>
@endif
    
</div>












<div class="card">

    <div class="headersub">
     Sub Procesos 
    </div>

    
    <div class="Eleccion ">

    @if ( $user->role_id == 1 || ( in_array($user->department_id, [2,4,5] ) && !in_array($pedido->status_id,[6,7,8,10]) )  )
        <a class="Candidato" rel="smaterial" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=smaterial') }}">+ Salida de Materiales</a>
    @endif


    @if ( ($user->role_id == 1 || 
        ( in_array($user->department_id,[2,7]) &&  !in_array($pedido->status_id,[6,7,10])    ) ) 
      )
        <a class="Candidato" rel="requisicion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=requisicion') }}">+ Requisición</a>
    @endif


    @if ( $user->role_id == 1 || (in_array($user->department_id, [4,5, 7]) && !in_array($pedido->status_id, [6,7,10]) ) )  
        <a class="Candidato" rel="ordenf" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=ordenf') }}">+ Orden de fábricación</a>
    @endif


    @if ( 
            !empty($pedido->invoice_number) && $pedido->origin != "R" && 
            ( $user->role_id == 1 || (in_array($user->department_id,[2,4]) &&  !in_array($pedido->status_id,[6,7,10]) && !empty($pedido->invoice_number) ) ) 
            )
        <a class="NParcial Candidato subp" href="{{ url('pedidos2/parcial_nuevo/'.$pedido->id) }}">+ Salida Parcial</a>
    @endif

    @if ($user->role_id == 1 || (in_array($user->department_id,[2,8]) && !in_array($pedido->status_id,[10])))
        <a class="Candidato" rel="devolucion_parcial" href="{{ url('pedidos2/devolucion_parcial_nueva/'.$pedido->id) }}">+ Devolucion</a>
    @endif


    @if ($user->role_id == 1 || (in_array($user->department_id,[2,8]) && !in_array($pedido->status_id,[10])) )
        <a class="Candidato" rel="devolucion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=devolucion') }}">Devolución</a> 
     @endif 

    @if ($user->role_id == 1 || (in_array($user->department_id,[3,7]) && in_array($pedido->status_id, [7]) && !isset($rebilling->id)) )

        <a class="Candidato" rel="refacturacion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=refacturacion') }}">Refacturación</a>
    @endif
    
    </div>


    <div id="DevolucionesParcialesDiv" href="{{ url('pedidos2/devoluciones_parciales_lista/'.$pedido->id) }}" class="SubprocesoContainer"></div>

    <div id="DevolucionDiv" href="{{ url('pedidos2/devolucion_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="SmaterialDiv" href="{{ url('pedidos2/smaterial_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="RequisicionDiv" href="{{ url('pedidos2/requisicion_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>  

    <div id="OrdenfDiv" href="{{ url('pedidos2/ordenf_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="ParcialesDiv" href="{{ url('pedidos2/parcial_lista/'.$pedido->id) }}"></div>



    <div id="RefacturacionDiv" class="SubProcesoContainer">
    @if(isset($rebilling->id))
    {{ view('pedidos2/refacturacion/ficha',['ob'=>$rebilling,'reasons'=>$reasons,"evidences"=>$evidences,"user"=>$user]) }}
    @endif
    </div>

    <p>&nbsp;</p>
</div>


{{-- NUEVA SECCIÓN PARA RUTAS --}}

@if(in_array($user->role_id, [1,2]) && in_array($user->department_id, [2,4,6]) && isset($RutasAsociadas) && count($RutasAsociadas) > 0)
    <div class="card">
        <div class="headersub"> Rutas </div>
        <div class="Eleccion">
            <table class="table table-bordered table-sm" style="width:100%; margin-top:10px;">
                <thead style="background-color:#f2f2f2;">
                    <tr>
                        <th> Número Ruta </th>
                        <th> Cliente </th>
                        <th> Estatus Pago </th>
                        <th> Monto por Cobrar </th>
                        <th> Nombre Recibe </th>
                        <th> Celular / Teléfono </th>
                        <th> Dirección </th>
                        <th> URL Mapa </th>
                        <th> Estatuses </th>
                        <th> Unidad </th>
                        <th> Chofer </th>
                        <th> Fecha / Hora </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($RutasAsociadas as $ruta)
                        @php
                            $codigo = $ruta->cliente_codigo ?? null;
                            $nombre = $ruta->cliente_nombre ?? null;

                            $telefonos = [];
                            if($ruta->celular){
                                $telefonos[] = '<a href="tel:'.$ruta->celular.'">'.$ruta->celular.'</a>';
                            }
                            if($ruta->telefono){
                                $telefonos[] = '<a href="tel:'.$ruta->telefono.'">'.$ruta->telefono.'</a>';
                            }
                        @endphp
                        <tr>
                            <td>{{ $ruta->numero_ruta ?? '-' }}</td>
                            <td>
                                @if($codigo)
                                    {{ $nombre ? "$codigo - $nombre" : $codigo }}
                                @else
                                    Sin cliente
                                @endif
                            </td>
                            <td>{{ ucfirst($ruta->estatus_pago ?? 'Pagado') }}</td>
                            <td>${{ number_format($ruta->monto_total ?? 0,2) }}</td>
                            <td>{{ $ruta->nombre_recibe ?? '-' }}</td>
                            <td>{!! count($telefonos) ? implode('/', $telefonos):'-' !!}</td>
                            <td>{{ $ruta->direccion ?? '-' }}</td>
                            <td>
                                @if($ruta->url_mapa)
                                    <a href="{{ $ruta->url_mapa }}" target="_blank"> Ver Mapa</a></td>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ ucfirst($ruta->estatus_entrega ?? 'enrutado') }}</td>
                            <td>{{ $ruta->unidad ?? 'Sin unidad' }}</td>
                            <td>{{ $ruta->chofer ?? 'Sin chofer' }}</td>
                            <td>
                                {{ $ruta->rp_created_at ? \Carbon\Carbon::parse($ruta->rp_created_at)->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif



{{-- NUEVA SECCIÓN DE ETIQUETADO --}}

    @php
        $user = auth()->user();

        $etiquetasOucltasVentas = ['PERDIDA', 'NO ESTA'];

        $EtiquetasDisponiblesOcultas = $EtiquetasDisponibles;
                                                                    
            if(in_array(auth()->user()->role->name,["Administrador", "Empleado"]) && !in_array(auth()->user()->department->name,["Administrador", "Auditoria"])) {
                $EtiquetasDisponiblesOcultas = $EtiquetasDisponibles->filter(function($etiqueta) use ($etiquetasOucltasVentas) {
                    return !in_array($etiqueta->nombre, $etiquetasOucltasVentas);
                });
            }

    @endphp

    {{-- ETIQUETAS ACTIVAS --}}

    @if(count($EtiquetasAsignadas) > 0)
        <div class="card etiquetas-card">
            <div class="headersub">Etiquetas activas(SOLO LECTURA)</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        @if(in_array($etiqueta->id, $EtiquetasAsignadas))
                            <div class="etiqueta-item">
                                <input type="checkbox" disabled checked id="etiqueta_view_{{$etiqueta->id }}" class="etiqueta-checkbox">
                                <label class="Candidato etiqueta-label checked" for="etiqueta_view_{{ $etiqueta->id }}" style="background-color: {{ $etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                    {{strtoupper($etiqueta->nombre)}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
        </div>
    @endif

    {{-- ETIQUETAS PARA ADMINISTRACIÓN--}}

    @if(in_array(auth()->user()->department->name,["Administrador"]) && in_array($user->role->name, ["Administrador", "Empleado"]))
        <form method="POST" action="{{route('pedido.etiquetas.guardar', ['id' => $id]) }}">
            @csrf
            <div class="card etiquetas-card">
                <div class="headersub">Etiquetas disponibles</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        <div class="etiqueta-item">
                            <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{$etiqueta->id}}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : ''}}>
                            <label class="Candidato etiqueta-label {{in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" for="etiqueta_{{ $etiqueta->id }}" style="background-color: {{ $etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                {{strtoupper($etiqueta->nombre)}}
                            </label>
                        </div>
                    @endforeach
                </div>
                <br>
                <button class="btn btn-dark" type="submit"> Guardar </button>
            </div>
        </form>
    @endif


    {{-- ETIQUETAS PARA EMBARQUES --}}

     @if($user->department->name =="Embarques" && in_array($user->role->name, ["Administrador", "Empleado"]))
        <form method="POST" action="{{route('pedido.etiquetas.guardar', ['id' => $id]) }}">
            @csrf
            <div class="card etiquetas-card">
                <div class="headersub"> Etiquetas disponibles - Fabricación LN</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        @if(!in_array($etiqueta->nombre, ['N1', 'N2', 'N3', 'N4', 'PARCIALMENTE TERMINADO (SP)', 'PEDIDO EN PAUSA (SP)', 'PARCIALMENTE TERMINADO (LN)', 'PEDIDO EN PAUSA (LN)']))
                            <div class="etiqueta-item">
                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{ $etiqueta->id }}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}>
                                <label class="Candidato etiqueta-label {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" for="etiqueta_{{$etiqueta->id}}" style="background-color: {{$etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                    {{strtoupper($etiqueta->nombre)}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
                <button class="btn btn-dark" type="submit">Guardar</button>
            </div>
        </form>
    @endif

    {{-- ETIQUETAS PARA FABRICACIÓN NORIA --}}

    @if($user->department->name =="Fabricación" && in_array($user->role->name, ["Administrador", "Empleado"]) && $user->office == "La Noria")
        <form method="POST" action="{{route('pedido.etiquetas.guardar', ['id' => $id]) }}">
            @csrf
            <div class="card etiquetas-card">
                <div class="headersub"> Etiquetas disponibles - Fabricación LN</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        @if(in_array($etiqueta->nombre, ['N3', 'N4', 'PARCIALMENTE TERMINADO (LN)', 'PEDIDO EN PAUSA (LN)']))
                            <div class="etiqueta-item">
                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{ $etiqueta->id }}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}>
                                <label class="Candidato etiqueta-label {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" for="etiqueta_{{$etiqueta->id}}" style="background-color: {{$etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                    {{strtoupper($etiqueta->nombre)}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
                <button class="btn btn-dark" type="submit">Guardar</button>
            </div>
        </form>
    @endif

    {{-- ETIQUETAS PARA FABRICACIÓN SAN PABLO --}}

    @if($user->department->name =="Fabricación" && in_array($user->role->name, ["Administrador", "Empleado"]) && $user->office == "San Pablo")
        <form method="POST" action="{{route('pedido.etiquetas.guardar', ['id' => $id]) }}">
            @csrf
            <div class="card etiquetas-card">
                <div class="headersub"> Etiquetas disponibles - Fabricación LN</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        @if(in_array($etiqueta->nombre, ['N1', 'N2', 'PARCIALMENTE TERMINADO (SP)', 'PEDIDO EN PAUSA (SP)']))
                            <div class="etiqueta-item">
                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{ $etiqueta->id }}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}>
                                <label class="Candidato etiqueta-label {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" for="etiqueta_{{$etiqueta->id}}" style="background-color: {{$etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                    {{strtoupper($etiqueta->nombre)}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
                <button class="btn btn-dark" type="submit">Guardar</button>
            </div>
        </form>
    @endif
    

    @if($user->department->name =="Auditoria" && in_array($user->role->name, ["Administrador", "Empleado"]))
        <form method="POST" action="{{route('pedido.etiquetas.guardar', ['id' => $id]) }}">
            @csrf
            <div class="card etiquetas-card">
                <div class="headersub"> Etiquetas disponibles - Fabricación LN</div>
                <div class="Eleccion">
                    @foreach($EtiquetasDisponiblesOcultas as $etiqueta)
                        @if(in_array($etiqueta->nombre, ['NO ESTA', 'PERDIDA', 'GERENCIA']))
                            <div class="etiqueta-item">
                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{ $etiqueta->id }}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}>
                                <label class="Candidato etiqueta-label {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" for="etiqueta_{{$etiqueta->id}}" style="background-color: {{$etiqueta->color ?? '#CCCCCC' }}; color:white;">
                                    {{strtoupper($etiqueta->nombre)}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
                <button class="btn btn-dark" type="submit">Guardar</button>
            </div>
        </form>
    @endif

{{-- FIN DE SECCIÓN DE ETIQUETADO --}}



@if(in_array(auth()->user()->department->name, ["Ventas", "Administrador", "Fabricación"]) && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]))

    <div class="card">

        <div class='center Fila'>
        
        
        @if ( !in_array($pedido->status_id, [7,10]) )

            <a class="cancelar" title="¿Confirma que desea cancelar este pedido?" 
            href="{{ url('pedidos2/cancelar/'.$pedido->id) }}">Cancelar</a>
        
            
        @elseif ($pedido->status_id == 7 && ($user->role_id== 1 || in_array($user->department_id, [7]) ) )

            <a class="cancelar" title="¿Confirma que desea quitar la cancelación de este pedido?" 
            href="{{ url('pedidos2/descancelar/'.$pedido->id) }}">Deshacer cancelación</a>

        @endif


        </div>

    </div>
@endif






<input type="hidden" name="urlConfirmaEntregado" value="{{ url('pedidos2/set_accion_entregar/'.$pedido->id) }}" />
</main>



<?php //var_dump($pedido); ?>

@endsection

@push('js')
<!-- <script type="text/javascript" src="{{asset('js/jquery.mobile-1.4.5.js')}}"></script>-->
<script type="text/javascript" src="{{asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('js/piedramuda.js?x='.rand(0,999))}}"></script>
<script type="text/javascript" src="{{asset('js/attachlist2.js?x='.rand(0,999))}}"></script>

<script type="text/javascript" src="{{asset('js/etiquetas/etiquetas.js?x='.rand(0,999))}}"></script>
<script>
$(document).ready(function(){
        

        timeoutAccion=null;

        ACCIONHTML="";
    /*
        let isMobile = isMobileOrTablet();
    if(isMobile){
    // SetEleccionAccionListenerMobile();
    }else{
    // SetEleccionAccionListener();
    }
    */


    $(".Eleccion .Accion.generico").click(function(e){
        e.preventDefault();
        $(".Eleccion .Accion").removeClass("activo");
        $(this).addClass("activo");
        AccionPresionado(this);
    });


    $(".Eleccion .Accion.enpuerta").click(function(e){
        e.preventDefault();
        $(".Eleccion .Accion").removeClass("activo");
        $(this).addClass("activo");
        AccionPresionadoEnPuerta(this);
    });

    $(".Eleccion .Accion.entregado").click(function(e){
        e.preventDefault();
        $(".Eleccion .Accion").removeClass("activo");
        $(this).addClass("activo");
        AccionPresionadoEntregado(this);
    });

    $(".Eleccion .Accion.audita").click(function(e){
        e.preventDefault();
        $(".Eleccion .Accion").removeClass("activo");
        $(this).addClass("activo");
        AccionPresionadoAudita(this);
    });



    $(".attachList").each(function(){
        console.log($(this).attr("rel"));
        AttachList($(this).attr("rel"));
    });


    $("a.cancelar").click(function(e){
        e.preventDefault();

        let tit = $(this).attr("title");
        if(!confirm(tit)){return false;}

        let href=$(this).attr("href");
        window.location.href=href;
    });


    $("body").on("click", ".modalShow", function(e){
        e.preventDefault();
        let href =$(this).attr("href");
        $.ajax({
            url:href,
            success:function(h){
                MiModal.exitButton=true;
                MiModal.content(h);
                MiModal.show();
            }
        });
    });



    $(".NParcial").click(function(e){
    e.preventDefault();
    let href = $(this).attr("href");
    AjaxGet(href,FormaNuevoParcial);
    });


    $("body").on("click",".editarparcial",function(e){
    e.preventDefault();
    AjaxGet($(this).attr("href"),FormaEditarParcial);
    });

    $("body").on("click",".editarsm",function(e){
    e.preventDefault();
    AjaxGet($(this).attr("href"),FormaEditarSmaterial);
    });

    $("body").on("click",".editof",function(e){
    e.preventDefault();
    AjaxGet($(this).attr("href"),FormaEditarOrdenf);
    });

    $("body").on("click",".editarrequisicion",function(e){
    e.preventDefault();
    AjaxGet($(this).attr("href"),FormaEditarRequisicion);
    });

    $("body").on("click",".desauditoria",function(e){
    e.preventDefault();
    //let tit = $(this).attr("title");
    let href = $(this).attr("href");
    // if(!confirm(tit)){return false;}
        AjaxGet(href,FormaDesauditoria);
    //AjaxGetJson(href,()=>{window.location.reload();});

    });



    $("body").on("uploaded",".attachList[rel='entregar']",function(e){
    let href = $("[name='urlConfirmaEntregado']").val();
    AjaxGetJson(href,RespuestaConfirmaEntregado);    
    $("#filaTerminarEntrega").show();
    });

    $("body").on("click",".editref",function(e){
    e.preventDefault();
    AjaxGet($(this).attr("href"),FormaEditarRefacturacion);
    });


    $("body").on("click",".deshacerEntregado",function(e){
        e.preventDefault();
        let href = $(this).attr("href");
        let tit = $(this).attr("title");
        if(!confirm(tit)){return false;}
        AjaxGetJson(href,RespuestaDeshacerEntregado);
    });

    $("body").on("click",".rehacerEntregado",function(e){
        e.preventDefault();
        let href = $(this).attr("href");
        let tit = $(this).attr("title");
        if(!confirm(tit)){return false;}
        AjaxGetJson(href,RespuestaDeshacerEntregado);
    });


    $("body").on("click",".editapg",function(e){
    e.preventDefault();
    let spc = $(this).closest(".SubProcesoContainer");
        if(spc.length > 0){
            $(spc).addClass("recargame");
        }
    AjaxGet($(this).attr("href"),FormaEditarProcesoGeneral);
    });



    $("body").on("click",".followBtn", function(e){
        e.preventDefault();
        let hrefyes = $(this).attr("href");
        let hrefno = $(this).attr("hrefno");
        let isno = $(this).hasClass("no");

        if(isno){
            AjaxGetJson(hrefno,FollowNoRespuesta);
        }else{
            AjaxGetJson(hrefyes,FollowRespuesta);
        }
        
    });



    $("body").on("click",".deshacersub",function(e){
    e.preventDefault();
    let href = $(this).attr("href");
    let tit = $(this).attr("title");
    let rel = $(this).attr("rel");

        if(!confirm(tit)){
            return false;
        }

        if(rel=="smaterial"){
            AjaxGetJson(href,RespuestaDeshacerEntregadoSM);
        }
        else if(rel=="ordenf"){
            AjaxGetJson(href,RespuestaDeshacerEntregadoOf);
        }
        else{
            AjaxGetJson(href,RespuestaDeshacerEntregadoParcial);
        }

    });





    $(".Alerta").tooltip();

    /*
    $('.Alerta').on({
    "click": function() {
        $(this).tooltip({ items: ".Alerta", content: "Displaying on click"});
        $(this).tooltip("open");
    },
    "mouseout": function() {      
        $(this).tooltip("disable");   
    }
    });
    */



    $(".Candidato").click(function(e){
    e.preventDefault();
    let href = $(this).attr("href");
    let rel = $(this).attr("rel");
        if(rel == "smaterial"){
            AjaxGet(href,FormaNuevoSmaterial);
        }
        else if(rel == "requisicion"){
            AjaxGet(href,FormaNuevoRequisicion);     
        }
        else if(rel == "ordenf"){
            AjaxGet(href,FormaNuevoOrdenf);        
        }
        else if(rel == "devolucion"){
            AjaxGet(href,FormaNuevoDevolucion);        
        }
        else if(rel == "refacturacion"){
            AjaxGet(href,FormaNuevoRefacturacion);        
        }
        else if(rel == "devolucion_parcial"){
            AjaxGet(href,FormaNuevoDevolucionParcial);
        }

    });


    $(".Eleccion .Accion.alerta").click(function(e){

        e.preventDefault();
        $(".Eleccion .Accion").removeClass("activo");
        $(this).addClass("activo");
        AccionPresionadoNoExitosa(this);

    });

    ////////////////////////
    //DEVOLUCIONES PARCIALES
    ///////////////////////

    $("body").on("click", ".editapg", function(e){
        e.preventDefault();
        AjaxGet($(this).attr("href"), FormaEditarDevolucionParcial);
    });

    $("body").on("submit", ".formCancelarDevolucion", function(e){

        e.preventDefault();

        if(!confirm("¿Estás seguro de cancelar esta devolución?")){
            return;
        }

        let form =$(this);
        let url = form.attr("action");

        $.post(url, form.serialize(), function(json){
            if(json.status == 1){
                CargarDevolucionesParciales();
            }else{
                alert("No se pudo cancelar");
            }
        });

    });
    ////////////////////////////////
    //FIN DE DEVOLUCIONES PARCIALES
    ///////////////////////////////

    ////////////////////////////////
    //SECCION MODIFICACION DIRECCION
    ////////////////////////////////

    function toggleDireccion(){
        let estado = $("#estado_direccion").val();

        $("#bloqueDireccionExistente").hide();
        $("#bloqueDireccionCompleta").hide();

        if(estado === 'completa'){
            if($("#modo_direccion").length){
                toggleModoDireccion();
            }else{
                $("#bloqueDireccionCompleta").slideDown();
            }
        }
    }

    function toggleModoDireccion(){
        let modo = $("#modo_direccion").val();

        $("#bloqueDireccionExistente").hide();
        $("#bloqueDireccionCompleta").hide();

        if(modo === 'existente'){
            $("#bloqueDireccionExistente").slideDown();
        }else if(modo === 'nueva'){
            $("#bloqueDireccionCompleta").slideDown();
        }
    }

    function CargarDireccionCliente(id){
        if(!id){
            return;
        }

        $.get("{{ url('pedidos2/direccion-cliente') }}/" + id, function(resp){
            if(resp.status !== 1){
                alert("No se pudo cargar la direccion");
                return;
            }

            let d = resp.data;

            $("input[name='nombre_direccion']").val(d.nombre_direccion);
            $("select[name='tipo_residencia']").val(d.tipo_residencia);
            $("input[name='direccion']").val(d.direccion);
            $("input[name='colonia']").val(d.colonia);
            $("input[name='ciudad']").val(d.ciudad);
            $("input[name='estado']").val(d.estado);
            $("input[name='codigo_postal']").val(d.codigo_postal);
            $("input[name='celular']").val(d.celular);
            $("input[name='telefono']").val(d.telefono);
            $("input[name='nombre_recibe']").val(d.nombre_recibe);
            $("input[name='url_mapa']").val(d.url_mapa);
            $("textarea[name='instrucciones']").val(d.instrucciones);

            $("input[name='requerimientos[]']").prop("checked", false);

            if(d.requerimientos){
                d.requerimientos.forEach(function(reqId){
                    $("input[name='requerimientos[]'][value='"+reqId+"']").prop("checked", true);
                });
            }
        });
    }

    function initDireccion(){
        let estado = $("#estado_direccion").val();
        let clienteDireccionId = $("#cliente_direccion_id").val();

        $("#bloqueDireccionExistente").hide();
        $("#bloqueDireccionCompleta").hide();

        if(estado === 'completa'){
            if($("#modo_direccion").length){
                if(clienteDireccionId){
                    $("#modo_direccion").val("existente");
                    $("#bloqueDireccionExistente").show();
                    CargarDireccionCliente(clienteDireccionId);
                }else{
                    $("#modo_direccion").val("nueva");
                    $("#bloqueDireccionCompleta").show();
                }
            }else{
                $("#bloqueDireccionCompleta").show();
            }
        }
    }

    $("#estado_direccion").on("change", function(){
        toggleDireccion();
    });

    $("#modo_direccion").on("change", function(){
        toggleModoDireccion();
    });

    $("#cliente_direccion_id").on("change", function(){
        CargarDireccionCliente($(this).val());
    });

    $("#btnGuardarDireccion").on("click", function(){

        if(typeof validarDireccionVisual === "function"){
            if(!validarDireccionVisual()){
                return;
            }
        }

        let cont = $("#formDireccionPedido");
        let url = cont.data("url");

        $.ajax({
            url: url,
            type: 'POST',
            data: cont.find("input, select, textarea").serialize(),
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            success: function(resp){

                let json = resp;

                if(typeof resp === "string"){
                    try{
                        json = JSON.parse(resp);
                    }catch(e){
                        alert("Respuesta invalida del servidor");
                        return;
                    }
                }
                if(json.status == 1){
                    alert(json.messages || "Direccion guardada correctamente");
                    location.reload();
                }else{
                    alert(json.messages || "No se pudo guardar la direccion");
                }
            },error: function(){
                alert("Error de comunicacion con el servidor");
            }
        });

    });

    

    ////////////////////////////
    //FIN MODIFICACION DIRECCION
    ////////////////////////////


    CargarParciales();
    CargarSmateriales();
    CargarOrdenf();
    CargarRequisiciones();
    CargarDevoluciones();
    CargarDevolucionesParciales();
    toggleDireccion();
    initDireccion();
    //$("#CuerpoActualizar").hide();


});



function FormaNuevoParcial(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    FormaNuevoParcial2();
}
function FormaNuevoParcial2(){
    $("#FSetParcial").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        success:function(h){
            MiModal.content(h);
            MiModal.show();            

            if($("#atlSlot").length > 0 ){
            let uploadto = $("#atlSlot").attr("uploadto");
            let listHref = $("#atlSlot").attr("listHref");
            let val = $("#atlSlot").attr("val");
            let event = $("#atlSlot").attr("event");
            AttachListCreate("#atlSlot","nparc",uploadto, listHref,"pictures","partial_id", val, "edit",event);
            }              

            $("input[name='parcialterminar']").click(function(){
                MiModal.exit();
               // CargarParciales();
               window.location.reload();
            });     

        }
    });
}


function FormaEditarParcial(h){
    MiModal.content(h);
   // MiModal.after = FormaEditarParcial2;
    MiModal.show();
console.log("formaEditarParcial");
    $("#FSetParcial").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarParciales();
            }else{
                console.log(json);
            }
        }
    });

    let monTexts =new Array();
    monTexts[4] ="Se guardó que el parcial fue generado.";
    monTexts[5] = "Puede agregar imágenes como evidencia";
    monTexts[6] = "Sube evidencia. Puede ser Fotografía o Escaneo de la Hoja Parcial Física firmada por el cliente.";
    monTexts[7] = "Sube una foto de la hoja parcial con el sello de cancelado.";

    let uploadto = $("#FSetParcial [name='uploadto']").val();
    let listHref = $("#FSetParcial [name='listHref']").val();
    let pid = $("#FSetParcial [name='partial_id']").val();


    let urlSS = $("#FSetParcial [name='urlSetStatus']").val();
    $("#continuarEditParcial").click(function(e){
        e.preventDefault();
        let sid = $("#FSetParcial [name='status_id'] option:selected").val();
        urlSS=updateUrlParameter(urlSS,"status_id",sid);
        $("#FSetParcial [name='status_id']").prop("readonly",true);
        AjaxGetJson(urlSS,null);
        $("#terminarEditParcial").show();
        $("#filaParcialContinuar").hide();   

        $("#monitorEditParcial").text(monTexts[sid]); 

        if(sid ==5 || sid ==6 || sid == 7){
        $(".divAgregarImagenes").show();
            
        AttachListInsert("#alContenedor","edpar_"+sid, uploadto, listHref, "pictures", "partial_id", pid,"edit", sid);
        }
        
    });

    
   
/*
    $("#FSetParcial [name='status_id']").change(function(){
        let sid = $(this).val();
       // AttachListSetEvent("edparcial",sid);
       //AttachListInsert(contenedorPath,rel,uploadto, listHref,catalog,key,value, mode,event,callback)
       $(".divAgregarImagenes").show();
        AttachListInsert("#alContenedor","edpar_"+sid, uploadto, listHref, "pictures", "partial_id", pid,"edit", sid);
    });
    */

}



function MostrarNotasForm(){
    $(".NotasForm").show();
    $(".FilaAddNotes").hide();
}



function FormaNuevoSmaterial(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();
    

    FormaNuevoSmaterial2();
}
function FormaNuevoSmaterial2(){
    console.log("Forma")

    if($("#atlSlot").length>0){
        let uploadto = $("#atlSlot").attr("uploadto");
        let listHref = $("#atlSlot").attr("listHref");
        let val = $("#atlSlot").attr("val");
        let event = $("#atlSlot").attr("event");
        AttachListCreate("#atlSlot","nsmat",uploadto, listHref,"pictures","smaterial_id", val, "edit", event); 
        FormaNuevoSmaterial3();
    }  

    $("input[name='parcialterminar']").click(function(){
        MiModal.exit();
        CargarSmateriales();
    });  

    $("body").on("MiModal-exit",function(){
        CargarSmateriales();
        $("body").off("MiModal-exit");
    });

/*
    $(".AccionForm [name='status_id']").change(function(){
        let val = $(this).val();
       
    });
    */

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        success:function(h){
            
            MiModal.content(h);
            MiModal.exitButton=false; 
            MiModal.show();     
            FormaNuevoSmaterial2();    
        }
    });

}

function FormaNuevoSmaterial3(){


    $("body").on("activated",".attachList[rel='nsmat']",function(){

        $(".attachList[rel='nsmat']").on("uploaded",function(){
        $("#smTerminar").show();        
        });

    });

    $("#smTerminar").show();   
}




function FormaEditarSmaterial(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                MiModal.exitButton=true;
                CargarSmateriales();
            }else{
                console.log(json);
            }
        }
    });


    let monTexts =new Array();
    monTexts[4] = "Se guardó que la salida de material fue generado.";
    monTexts[5] = "Puede agregar imágenes como evidencia";
    monTexts[6] = "Sube evidencia. Puede ser Fotografía o Escaneo de la Hoja Parcial Física firmada por el cliente.";
    monTexts[7] = "Sube una foto de la hoja parcial con el sello de cancelado.";

    let uploadto = $("#FSetAccion [name='uploadto']").val();
    let listHref = $("#FSetAccion [name='listHref']").val();
    let smid = $("#FSetAccion [name='smaterial_id']").val();
    let urlSS = $("#FSetAccion [name='urlSetStatus']").val();

    $("#FSetAccion #continuarEdit").click(function(e){
        e.preventDefault();
        let sid = $("#FSetAccion [name='status_id'] option:selected").val();
        urlSS=updateUrlParameter(urlSS,"status_id",sid);
        $("#FSetAccion [name='status_id']").prop("readonly",true);
        AjaxGetJson(urlSS,null);
        $(".terminarEdit").show();
        $("#filaContinuar").hide();   

        $("#FSetAccion .monitor").text(monTexts[sid]); 

        if(sid ==5 || sid ==6 || sid == 7){
        $(".agregarImagenes").show();
        }

        //AttachListCreate("#atlSlot","nsmat",uploadto, listHref,"pictures","smaterial_id", val, "edit", event); 
        AttachListInsert("#alContenedor","nsmat_"+sid, uploadto, listHref, "pictures", "smaterial_id", smid, "edit", sid);
    });

}




function FormaNuevoOrdenf(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    FormaNuevoOrdenf2();
}
function FormaNuevoOrdenf2(){
    $("body").bind("MiModal-exit",function(){
        CargarOrdenf();
        $("body").unbind("MiModal-exit");
    });

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarOrdenf();
            }
            else{alert(json.errors);} 
        }
    });

    let monTexts =new Array();
    monTexts[3] = "Por favor adjunta foto o PDF escaneado de la orden de fabricación.";
    monTexts[4] = "Se guardará el estatus Fabricado";
    monTexts[7] = "Por favor sube foto de la orden de fabricación con el sello de cancelado.";

    $("#FSetAccion [name='status_id']").change(function(){
        let sid = $(this).val();
        $(".monitor").text(monTexts[sid]);
        if(sid==4){$(".Fila[rel='archivo']").hide();}
        else{$(".Fila[rel='archivo']").show();}
    });


    let sid = $("#FSetAccion [name='status_id']").val();
    $(".monitor").text(monTexts[sid]);

    $("[name='document']").change(function(){
        $("#rowContinuar").show();
    });

}


function FormaEditarOrdenf(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarOrdenf();
            }else{
                console.log(json);
            }
        }
    });

    let monTexts =new Array();
    monTexts[1] = "Por favor adjunta foto o PDF escaneado de la orden de fabricación.";
    monTexts[3] = "Agrega o sustituye la foto o PDF escaneado de la orden de fabricación si faltaba.";
    monTexts[4] = "Se guardará el estatus Fabricado";
    monTexts[7] = "Por favor sube foto de la orden de fabricación con el sello de cancelado.";

    $("#FSetAccion [name='status_id']").change(function(){
        let sid = $(this).val();
        $(".monitor").text(monTexts[sid]);
        $(".Fila[rel='archivo']").hide();
        $(".Fila[rel='archivoc']").hide();
        if(sid==1 || sid == 3){$(".Fila[rel='archivo']").show();}
        else if(sid==7){$(".Fila[rel='archivoc']").show();}
    });

    let sid = $("#FSetAccion [name='status_id']").val();
    $(".monitor").text(monTexts[sid]);

    $("#FSetAccion [name='status_id']").change();

}





function FormaNuevoRequisicion(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    FormaNuevoRequisicion2();
}
function FormaNuevoRequisicion2(){
    $("body").bind("MiModal-exit",function(){
        CargarRequisiciones();
        $("body").unbind("MiModal-exit");
    });

    $("select[name='status_id']").change(function(){
        let v = $(this).val();
        if(v=="2"){$("#rowFolioSM").show();}
        else{$("#rowFolioSM").hide();}
    });
    $("select[name='status_id']").change();


    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarRequisiciones();
            }
            else{alert(json.errors);} 
        }
    });

    $("#SubmitButtonRow").hide();
    $(".AccionForm [name='number']").change(function(){
        let valor = $(this).val();
        if(valor != ""){
            $("#SubmitButtonRow").show(); 
        }        
    });

}


function FormaEditarRequisicion(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                //CargarOrdenf();
                CargarRequisiciones();
            }else{
                console.log(json);
            }
        }
    });

    
    $(".Fila.archivo").hide();
    $(".AccionForm  [name='status_id']").change(function(){
        let val = $(this).val();
        if(val==1 || val==5 || val==6 | val==7){
            $(".Fila.archivo").hide();
            $(".Fila.archivo[rel='"+val+"']").show();

            let hayArchivo = $(".Fila.archivo[rel='"+val+"'] a").length;
            /*
            if(hayArchivo < 1){
                $(".Fila.archivo[rel='"+val+"'] input[type='file']").change(function(){$("#SubmitButtonRow").show();});
                $("#SubmitButtonRow").hide();
            }else{
                $("#SubmitButtonRow").show();
            }
            */
        }
    });

    $(".Fila.archivo[rel='"+ $(".AccionForm  [name='status_id']").val() +"']").show();

    let hayArchivo = $("#fileDiv").length;

    if(hayArchivo < 1){
        $("#SubmitButtonRow").hide();
    }


}


function FormaEditarProcesoGeneral(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                let rme =$(".recargame"); 
                if(rme.length > 0){

                    $(rme).removeClass("recargame");
                    $.get($(rme).attr("href"),function(h){
                        $(rme).html(h);
                        $(rme).find(".attachList").each(function(){
                        AttachList($(this).attr("rel"));
                        });
                    $("#filaTerminarEntrega").show();
                    });
                    MiModal.exit();
                }else{
                    window.location.reload();
                }
                
            }else{
                console.log(json);
            }
        }
    });

    $("#dialogo .attachList").each(function(){
        AttachList($(this).attr("rel"));
    });

    $("body").bind("MiModal-exit",function(){
        window.location.reload();
        $("body").unbind("MiModal-exit");
    });

}








function FormaNuevoDevolucion(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    FormaNuevoDevolucion2();
}
function FormaNuevoDevolucion2(){


    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
       // dataType:"json", 
        success:function(h){

            MiModal.exitButton=true; 
            MiModal.content(h); 
            MiModal.show();

            //AttachListCreate();
            let uploadto = $("#atlSlot").attr("uploadto");
            let listHref = $("#atlSlot").attr("listHref");
            let val = $("#atlSlot").attr("val");

            AttachListCreate("#atlSlot","debopics", uploadto, listHref, "evidence", "debolution_id", val, "edit");

                $("body").on("MiModal-exit",function(){
                CargarDevoluciones();
                $("body").off("MiModal-exit");
                });

        }
    });

}


function FormaEditarDevolucion(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                //CargarOrdenf();
                CargarDevoluciones();
            }else{
                console.log(json);
            }
        }
    });

}






function FormaNuevoRefacturacion(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    FormaNuevoRefacturacion2();
}
function FormaNuevoRefacturacion2(){

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status == 1){
                MiModal.exit();
                window.location.reload();
            }else{
                alert(json.errors);
            }
        }
    });


    $("body").on("MiModal-exit",function(){
        CargarRefacturaciones();
        $("body").off("MiModal-exit");
    });

}

function FormaEditarRefacturacion(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                window.location.reload();
            }else{
                console.log(json);
                alert(json.errors);
            }
        }
    });

}

function FormaNuevoDevolucionParcial(h){

    MiModal.exitButton = true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        datatype: "json",
        error:function(err){
            alert("Error al registrar devolución: " + err.statusText);
        },

        success: function(json){
            if(json.status == 1){
                MiModal.exit();
                CargarDevolucionesParciales();
            }else{
                alert("Error inesperado.");
            }
        }
    });

}

function FormaEditarDevolucionParcial(h){

    MiModal.exitButton = true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({

        dataType: "json",
        error: function(err){
            alert("Error al guardar: " + err.statusText);
        },
        success: function(json){
            if(json.status == 1){
                MiModal.exit();
                CargarDevolucionesParciales();
            }else{
                alert("Error inesperado.");
            }
        }

    });

}



function MostrarMainInfo(){
    $(".MainInfo").slideDown();
    $(".MiniInfo").hide();
}
function EsconderMainInfo(){
    $(".MainInfo").slideUp();
    $(".MiniInfo").show();
}

function MostrarDireccion(){
    $(".MainInfo").hide();
    $(".MiniInfo").hide();
    $(".DireccionInfo").slideDown();
    initDireccion();
}

function EsconderDireccion(){
    $(".DireccionInfo").slideUp();
    $(".MainInfo").show();
    $(".MiniInfo").show();
}

 function AccionPresionado(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        AccionPresionadoLlena(ob, h);
    }
   });

 }


 function AccionPresionadoLlena(ob,html){
    if(typeof(ob)=="undefined"){ob=null;}
    $(ob).addClass("completo");
    //$("#AccionSection").html(html);

    MiModal.content(html);
    MiModal.show();
    //MiModal(html);
    FormaAccionSet();
 }
 function FormaAccionSet(){

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json",
        success:function(json){
            if(json.status==1){
                window.location.reload();
            }
            else if(json.status == 2){
                AccionCargarPaso(json.url); 
            }
        }
    });


    $(".setto").click(function(e){
        e.preventDefault();

        let rel = $(this).attr("rel");
        let val = $(this).attr("val");
        $(this).closest("form").find("[name='"+rel+"']").val(val);
        $(this).closest("form").submit();
    });



    $("#dialogo .attachList").each(function(){
    AttachList($(this).attr("rel"));
    });

    $("body").on("MiModal-exit",function(){
        window.location.reload();
    });

}

function AccionCargarPaso(href){

$.ajax({
 url:href,
 type:"get",
 error:function(err){alert(err.statusText);},
 success:function(h){
     $("#AccionSection").html(h);

     MiModal.content(h);
     //MiModal.after =FormaAccionSet;
     MiModal.show();

     FormaAccionSet();

     }
});

}



function AccionPresionadoEnPuerta(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        AccionEnPuertaForma(h,ob);
    }
   });

 }


 function AccionEnPuertaForma(html,ob){
    if(typeof(ob)=="undefined"){ob=null;}
    else{
        $(ob).addClass("completo");
    }    

    MiModal.content(html);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json",
        success:function(json){
            if(json.status==1){
                window.location.reload();
            }
            else if(json.status == 2){
                AccionCargarPasoEnPuerta(json.url); 
            }
        }
    });


    $("#FSetAccion .setto").click(function(e){
        e.preventDefault();

        let rel = $(this).attr("rel");
        let val = $(this).attr("val");
        $(this).closest("form").find("[name='"+rel+"']").val(val);
        $(this).closest("form").submit();

    });
}

function AccionCargarPasoEnPuerta(href){

$.ajax({
 url:href,
 type:"get",
 error:function(err){alert(err.statusText);},
 success:function(h){
        
        MiModal.exitButton=false;
        MiModal.content(h);
        MiModal.show();        

        setTimeout(()=>{MiModal.exitButton=true;},100);     

        AttachList("enp");

        $(document).on("click", ".attachList[rel='enp']", function(){
            $("#filaEnpuertaN").show();
            $(document).off("click", ".attachList[rel='enp']");
        });


        $("body").on("MiModal-exit",function(){
        window.location.reload();
        });


     }
});

}


//Quitar Estatus EN Puerta 

function AccionPresionadoNoExitosa(ob){

    let href = $(ob).attr("href");

    $.ajax({

        url: href,
        type: "get",
        error: function(err){ alert(err.statusText); },
        success: function(html){
            AccionNoExitosaForma(html, ob);
        }

    });

}

function AccionNoExitosaForma(html, ob){

    if(typeof(ob) !== "undefined"){
        $(ob).addClass("Completo");
    }

    MiModal.content(html);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error: function(err){alert(err.statusText);},
        dataType: "json",
        success: function(json){
            if(json.status == 1){
                window.location.reload();
            }else{
                alert(json.errors);
            }
        }
    });

    $("body").on("MiModal-exit", function(){
        window.location.reload();
        $("body").off("MiModal-exit");
    });
}



function AccionPresionadoEntregado(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        AccionEntregadoForma(h,ob);
    }
   });

 }
 function AccionEntregadoForma(html,ob){
    if(typeof(ob)=="undefined"){ob=null;}
    else{
        $(ob).addClass("completo");
    }    

    MiModal.content(html);
    MiModal.show();

    $("body").on("MiModal-exit",function(){
        window.location.reload();
    });


    AttachList("entregar");
}



function AccionPresionadoAudita(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        AccionAuditaForma(h,ob);
    }
   });

 }
 function AccionAuditaForma(html,ob){
    if(typeof(ob)=="undefined"){ob=null;}
    else{
        $(ob).addClass("completo");
    }    

    MiModal.content(html);
    MiModal.show();

    $("#dialogo form").ajaxForm({
        dataType:"json",
        success:function(json){
            if(json.status==1){
                window.location.reload();
            }else{
                alert(json.errors);
            }
        }
    });

    $("body").on("MiModal-exit",function(){
        window.location.reload();
    });    
}












 function MostrarActualizar(){
    $("#CuerpoActualizar").slideDown();
 }


function CargarParciales(){
    let href = $("#ParcialesDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){
            $("#ParcialesDiv").html("<aside class='SubprocesoError'>Salidas Parciales no cargadas por error: "+err.statusText+". Si deseas verlas, por favor <a onclick='javascript:window.location.reload()'>recarga la página</a>.</aside>");

        },
        success:function(h){
            $("#ParcialesDiv").html(h);
            $("#ParcialesDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
        }
    });
    
}

function CargarSmateriales(){
    let href = $("#SmaterialDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){
            $("#SmaterialDiv").html("<aside class='SubprocesoError'>Salidas de Material no cargadas por error: "+err.statusText+". Si deseas verlas, por favor <a onclick='javascript:window.location.reload()'>recarga la página</a>.</aside>");
        },
        success:function(h){
            $("#SmaterialDiv").html(h);
            $("#SmaterialDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
        }
    });
    
}

function CargarOrdenf(){
    let href = $("#OrdenfDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){
            $("#OrdenfDiv").html("<aside class='SubprocesoError'>Ordenes de manufactura no cargadas por error: "+err.statusText+". Si deseas verlas, por favor <a onclick='javascript:window.location.reload()'>recarga la página</a>.</aside>");
        },
        success:function(h){
            $("#OrdenfDiv").html(h);            
        }
    });
    
}


function CargarRequisiciones(){
    let href = $("#RequisicionDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){
        $("#RequisicionDiv").html("<aside class='SubprocesoError'>Requisiciones no cargadas por error: "+err.statusText+". Si deseas verlas, por favor <a onclick='javascript:window.location.reload()'>recarga la página</a>.</aside>");
        },
        success:function(h){
            $("#RequisicionDiv").html(h);
        }
    });
    
}

function CargarDevoluciones(){
    let href = $("#DevolucionDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){
            $("#DevolucionDiv").html("<aside class='SubprocesoError'>Devoluciones no cargadas por error: "+err.statusText+". Si deseas verlas, por favor <a onclick='javascript:window.location.reload()'>recarga la página</a>.</aside>");
        },
        success:function(h){
            $("#DevolucionDiv").html(h);
            
            $("#DevolucionDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
            
        }
    });
    
}

function CargarDevolucionesParciales(){

    let href = $("#DevolucionesParcialesDiv").attr("href");

    $.ajax({
        url:href,
        error: function(err){

            $("#DevolucionesParcialesDiv").html("<aside class='SubprocesoError'> Error al cargar devoluciones: " + err.statusText + "</aside>");

        },
        success: function(html){
            $("#DevolucionesParcialesDiv").html(html);
        }
    });

}


function RespuestaConfirmaEntregado(json){
    if(json.status==0){
        alert("No se logró registrar el cambio de estatus a entregado.");
    }
}





function RespuestaDeshacerEntregado(json){
    if(json.status == 1){
        window.location.reload();
    }else{
        alert(json.errors);
    }
}


function RespuestaDeshacerEntregadoSM(json){
    if(json.status == 1){
        MiModal.exit();
        CargarSmateriales();
    }else{
        alert(json.errors);
    }
}

function RespuestaDeshacerEntregadoOf(json){
    if(json.status == 1){
        MiModal.exit();
        CargarOrdenf();
    }else{
        alert(json.errors);
    }
}


function RespuestaDeshacerEntregadoParcial(json){
    if(json.status == 1){
        MiModal.exit();
        CargarParciales();
    }else{
        alert(json.errors);
    }
}



function FormaDesauditoria(html){
    MiModal.content(html);
    MiModal.show();
    
}




function FollowRespuesta(json){
    if(json.status == 1){
        $(".followBtn").addClass("no");
        $(".followBtn").text("A mis pedidos");
    }else{
        alert(json.errors);
    }
}
function FollowNoRespuesta(json){
    if(json.status == 1){
        $(".followBtn").removeClass("no");
        $(".followBtn").text("Dejar de seguir");
    }else{
        alert(json.errors);
    }
}



</script>    
@endpush
