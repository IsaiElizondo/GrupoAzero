<?php

use App\ManufacturingOrder;
use App\Partial;
use App\PurchaseOrder;
use App\Smaterial;



$estatusCodes=[1=>"GEN",2=>"EMB",3=>"FAB", 4=>"FAB", 5=>"PUE", 6=>"ENT", 7=>"CNC",8=>"REF",9=>"DEV",10=>"AUD"];
    if($item->origin == "R"){
        $estatusCodes[6]="SUR";
        $estatuses[6]="Surtido";
    }

$estatusCodesSM=[1=>"GEN",2=>"EMB",3=>"FAB", 4=>"ELB", 5=>"PUE", 6=>"ENT", 7=>"CNC",8=>"REF",9=>"DEV",10=>"AUD"];
$estatusCodesSP=[1=>"GEN",2=>"EMB",3=>"FAB", 4=>"GEN", 5=>"PUE", 6=>"ENT", 7=>"CNC",8=>"REF",9=>"DEV",10=>"AUD"];

$estatusesSM=$estatuses;
$estatusesSM[4]="Elaborado";

$estatusesSP=$estatuses;
$estatusesSP[4]="Generado";


$origenes=[""=>"","C"=>"Cotización","F"=>"Factura", "R"=>"Requisición"];

$partials = Partial::where(["order_id"=>$item->id])->get();
$ordersf = ManufacturingOrder::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
$smateriales = Smaterial::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
$requisitions = PurchaseOrder::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
//var_dump($partials);

?>

<link rel="stylesheet" href="{{ asset('css/etiquetas/etiquetasM.css?x='.rand(0,999)) }}" />


<aside class="Pedido">

    <div class="EsquinaOrigen" title="Origen: {{ $origenes[$item->origin] }}">{{ $item->origin }}</div>

    <div class="estatusFlags">        

        <div class="estatus main E{{ $item->status_id }}" title="{{ $estatuses[$item->status_id] }}">{{ $estatusCodes[$item->status_id] }}</div>

    </div>


        <div class="SuperElementos">

            <a class="granliga pseudoa" href="{{ url('pedidos2/pedido/'.$item->id) }}" >  

            <div class="SuperElemento">
              
            <div class="Elementos" href="{{ url('pedidos2/pedido/'.$item->id) }}">
                
                
                @if (!empty($item->invoice_number)) 
                <div class="datito" rel="main"><label>Factura</label>{{$item->invoice_number}}</div>
                @elseif (!empty($item->invoice)) 
                <div class="datito" rel="main"><label>Cotización</label>{{$item->invoice}}</div>
                @else
                <div class="datito" rel="main"> &nbsp;</div>
                @endif


                @if (!empty($item->stockreq_id))
                
                <div class="datito" rel="sec"> 
                    @if (!empty($item->stockreq_document))
                    <label>Requisición Stock</label>
                    <span>{{$item->stockreq_number}} </span> 
                    @else 
                    <label>Requisición Stock</label><span> {{$item->stockreq_number}} </span>
                    @endif
                </div>
                
                @else 

                <div class="datito" rel="sec">
                    <!-- <label>Requisición</label><span>{{$item->requisition_code}}</span> -->
                </div>
                
                @endif
                
                
                
                <div class="datito" rel="cliente"><label>Cliente</label> {{$item->client}} </div>

                <div class="datito" rel="sucursal"> <label>Sucursal</label> {{$item->office}}  </div>
 
                
                <!--
                <div class="datito detalles" rel="fab">
                    <div class="head">Fabricacion</div>
                
                    <?php
                    
                    $n=0;
                    foreach($ordersf as $part){
                        $n++;
                        if($n==3){echo "<div>...</div>"; break;}
                        if(!isset($part["status_id"])){continue;}
                        echo "<p><b>".$part["number"]."</b> <br/>".$estatuses[$part["status_id"]]."</p>";
                    }
                    ?>
                
                </div>

                
               
                <div class="datito detalles" rel="par">
                    
                <div class="head"> Parciales</div>
                <?php
                //$partials = Partial::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
                $n=0;
                foreach($partials as $part){
                    $n++;
                    if($n==3){echo "<div>...</div>"; break;}
                    echo "<p><b>".$part["invoice"]."</b> <small>".$estatuses[$part["status_id"]]."</small></p>";
                }
                ?>
                
                </div>
                -->

                <!--
                <div class="datito detalles" rel="sm"><div class="head">Salidas Materiales</div>

                <?php
                
                $n=0;
                foreach($smateriales as $part){
                    $n++;
                    if($n==3){echo "<div>...</div>"; break;}
                    echo "<p><b>".$part["code"]."</b> <br/>".$estatuses[$part["status_id"]]."</p>";
                }
                ?>


                </div>

                 <div class="datito" rel=""> </div>  
                -->
               
                         

            </div>
            
            

            </div>
        
            </a>



        <a href="{{ url('pedidos2/pedido/'.$item->id) }}">  
            <div class="SuperElemento conSubprocesos">

            @foreach ($requisitions as $re)
            <div class="estatus E{{ $re->status_id }}" title="Requisición #{{$re->number}} {{ (isset($estatuses[$re->status_id])?$estatuses[$re->status_id]:0) }}"> <b>RE</b>:{{ (isset($estatusCodes[$re->status_id])?$estatusCodes[$re->status_id]:$re->status_id) }} 
                <small>{{$re->number}}</small>
            </div>
            @endforeach

            @foreach ($ordersf as $of)
            <div class="estatus E{{ $of->status_id }}" title="Orden de fabricación #{{$of->number}} {{ (isset($estatuses[$of->status_id])?$estatuses[$of->status_id]:0) }}"> <b>OF</b>:{{ (isset($estatusCodes[$of->status_id])?$estatusCodes[$of->status_id]:$of->status_id) }} 
                <small>{{$of->number}}</small>
            </div>
            @endforeach

            @foreach ($partials as $par)
            <div class="estatus E{{ $par->status_id }}" title="Parcial #{{$par->invoice}} {{ $estatusesSP[$par->status_id] }}"><b>SP</b>:{{ $estatusCodesSP[$par->status_id] }} <small>{{$par->invoice}}</small></div>
            @endforeach

            @foreach ($smateriales as $sm)
            <div class="estatus E{{ $sm->status_id }}" title="Salida de Materiales #{{$sm->code}} {{ $estatusesSM[$sm->status_id] }}"><b>SM</b>:{{ $estatusCodesSM[$sm->status_id] }} <small>{{$sm->code}}</small></div>
            @endforeach


            </div>
            @if (!empty($item->etiquetas_render))
                <div class="etiquetas-mini">
                    @foreach ($item->etiquetas_render as $etq)
                        <span class="etiqueta-circulo" style="background-color: {{ $etq['color'] }};" title="{{ $etq['nombre'] }}">
                            {{ $etq['iniciales'] }}
                        </span>
                    @endforeach
                </div>
            @endif

        </a>




                    <div class="SuperElemento conIconos">
                        
                        <div rel="icons" >
                            <div class='iconSet'>
                            <!--
                                <a class="parciales" title="Parciales" href="{{ url('pedidos2/fragmento/'.$item->id.'/parciales') }}"></a>
                                <a class="ordenf" title="Ordenes de Manufactura" href="{{ url('pedidos2/fragmento/'.$item->id.'/ordenf')}}"></a>
                                
                            -->
                                <a class="historial" title="Más Información" href="{{ url('pedidos2/masinfo/'.$item->id) }}"></a>
                                <a class="notas" title="Notas" href="{{ url('pedidos2/fragmento/'.$item->id.'/notas')}}"></a>
                                
                                <a class="followBtn {{ (($item->follows > 0) ? '':'no') }}" title="A mis favoritos"  
                                href="{{ url('pedidos2/set_followno/'.$item->id.'/'.$user->id)}}" hrefno="{{ url('pedidos2/set_follow/'.$item->id.'/'.$user->id)}}"></a>
                            </div>   


                       @if($item->recibido_embarques_at && in_array($item->status_id, [2, 5]))
                            <div class="datito">
                                <label>Días desde que se recibio</label>
                                <span class="dias-contador text-primary abrir-modal" style= "cursor:pointer;" data-toggle="modal" data-target= "#modalDias{{$item->id}}">
                                     <center>
                                        @php
                                            $recibido = \Carbon\Carbon::parse($item->recibido_embarques_at);
                                            $horas = $recibido->diffInHours(now());
                                        @endphp

                                        @if ($horas < 24)
                                            {{ number_format($horas, 2) }} {{ $horas == 1 ? 'hr' : 'hrs' }}
                                        @else
                                            @php
                                                $dias = $recibido->floatDiffInRealDays(now());
                                            @endphp
                                            {{ number_format($dias, 2) }} {{ round($dias, 2) == 1.00 ? 'día' : 'días' }}
                                        @endif
                                    </center>

                                </span>
                            </div>

                            {{--MODAL--}}

                            <div class="modal fade" id="modalDias{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{$item->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content p-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title"> Siguimiento de entrega </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-2">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Recibido por embarques</th>
                                                        <th>Días</th>
                                                        <th>Entrega Programada</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($item->recibido_embarques_at)->format('d-m-Y') }}</td>
                                                        <td>
                                                             @php
                                                                $recibido = \Carbon\Carbon::parse($item->recibido_embarques_at);
                                                                $horas = $recibido->diffInHours(now());
                                                            @endphp

                                                            @if ($horas < 24)
                                                                {{ number_format($horas, 2) }} {{ $horas == 1 ? 'hr' : 'hrs' }}
                                                            @else
                                                                @php
                                                                    $dias = $recibido->floatDiffInRealDays(now());
                                                                @endphp
                                                                {{ number_format($dias, 2) }} {{ round($dias, 2) == 1.00 ? 'día' : 'días' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->entrega_programada_at 
                                                            ? \Carbon\Carbon::parse($item->entrega_programada_at)->format('d-m-Y') 
                                                            : 'No programada' }}</td>   
                                                    </tr>
                                                </tbody>
                                            </table>

                                            @if($item->entrega_programada_at && \Carbon\Carbon::parse($item->entrega_programada_at)->isPast())
                                                <div class="alert alert-danger mt-2 p-1">
                                                    <strong>¡Atención!</strong> La entrega programada ya pasó.
                                                </div>
                                            @endif
                                        </div>
                                    </div>                                       
                            </div>
                        @endif    
                        
                        

                        </div>

                        
                    </div>

            </div>

</aside>
