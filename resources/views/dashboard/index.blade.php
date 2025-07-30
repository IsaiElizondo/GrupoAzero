<?php 

use App\Pedidos;
use App\Http\Controllers\Pedidos2Controller;

?>

@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Pendientes')])

@section('content')
    <link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pedidos2/index.css').'?x='.rand(0,999) }}" />
    <link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/paginacion.css').'?x='.rand(0,999) }}" />
    <link rel="stylesheet" href="{{ asset('css/piedramuda.css') }}" />
    <link rel="stylesheet" href="{{ asset('jqueryui/jquery-ui.min.css') }}" />


<main class="content">
    <div class="card">
        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Administrar mis pedidos</h4>
                <p class="card-category">Pedidos de {{ auth()->user()->name }}</p>
            </div>
        </div>

        <div class="card-body">
        


            <form id="fbuscar" action="#" method="POST">
		@csrf           
            
                <input type="hidden" name="p" value="1">
                <input type="hidden" name="excel" value="0">
                <input type="hidden" name="excel_dashboard" value="0">
                <input type="hidden" name="querystring" value="{}">

                <div class="row align-items-center mb-3">
                @php
                    date_default_timezone_set("America/Mexico_City");
                    $hoy = date("Y-m-d");
                    $inicio = (new DateTime())->modify("-7 month")->format("Y-m-d");
                @endphp

                <div class="col-md-2">
                    <input type="text" name="termino" class="form-control form-control-sm" placehoder="Buscar..." onkeydown="if(event.key == 'Enter'){envent.preventDefault(); $('#buscarBoton').click();}">
                </div>
                
                {{--
                <div class="col-md-3">
                    <label for="fechas"><span id="MuestraFecha"></span></label>
                    <input type="text" name="fechas" id="fechas" class="form-control form-control-sm"
                        value="{{ $inicio }} - {{ $hoy }}"
                        placeholder="Rango de Fechas">
                    <small class="form-text text-muted">Selecciona primero la fecha inicial y después la final</small>
                </div>
                --}}

                
                

                    <div class="col-md-3">
                        <button type="button" class="btn btn-sm btn-secondary w-100" data-toggle="modal" data-target="#modalBusquedaAvanzada">
                            Búsqueda Avanzada
                        </button>
                    </div>

                    @if(in_array(auth()->user()->role->name, ['Administrador', 'ALEJANDRO GALICIA']) || in_array(auth()->user()->department_id, [2, 4]))
                    <div class="col-md-2">
                        <select name="orden_recibido" class="form-control form-control-sm">
                            <option value="">Ordenar por recibido</option>
                            <option value="asc" {{ old('orden_recibido', request()->input('orden_recibido')) == 'asc' ? 'selected' : '' }}>Más antiguo primero</option>
			    <option value="desc" {{ old('orden_recibido', request()->input('orden_recibido')) == 'desc' ? 'selected' : '' }}>Más reciente primero</option>

                        </select>
                    </div>
                    @endif

                    <div class="col-md-2">
                        <button type="button" id="buscarBoton" class="btn btn-sm btn-primary w-100">Buscar</button>
                    </div>
                    
                    @if(in_array(auth()->user()->role->name, ['Administrador', 'ALEJANDRO GALICIA']) || in_array(auth()->user()->department_id, [2, 3, 4]))
                        <div class="col-md-3">
                            <button type="button" id="nuevoExcelBtn" class="btn btn-sm btn-success w-100"> Descargar Excel </button>
                        </div>
                    @endif

                    @if(auth()->user()->department->name == "Fabricación" && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]))
                        <div class="col-md-3">
                            <button type="button" id="excelFabricacionBtn" class="btn btn-sm btn-success w-100"> Descargar Excel </button>
                        </div>
                    @endif

                </div>

                                {{-- MODAL PARA BUSQUEDA AVANZADA --}}

                        <div class="modal fade" id="modalBusquedaAvanzada" tabindex="-1" role="dialog" aria-labelledby="modalBusquedaAvanzadaLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalBusquedaAvanzadaLabel">Filtros Avanzados</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        
                                    <div class="AvanzadosSet">

                                        @if(in_array(auth()->user()->role->name, ['Administrador', 'ALEJANDRO GALICIA']) || in_array(auth()->user()->department_id, [2,3,5,6,7,8,9]))

                                                <fieldset>
                                                    <legend>Status</legend>
                                            
                                                    @foreach ($estatuses as $k=>$v)
                                                        @if ( !in_array($k, [6, 7, 8,9, 10] ))
                                                        <div class="checkpair"><input type="checkbox" name="st[]" value="{{ $k }}" id="st_{{ $v }}"> <label for="st_{{ $v }}">{{ $v }}</label></div>

                                                        @endif
                                                    @endforeach
                                        @endif 

                                                </fieldset>
                                                <fieldset>
                                                    <legend>Subprocesos</legend>
                                                    @foreach ($events as $k=>$v)
                                                        @if ($k == "refacturar")
                                                            @continue
                                                        @endif 
                                                    <div class="checkpair parent" rel="{{ $k }}"><input type="checkbox" name="sp[]" value="{{ $k }}" id="sp_{{ $v }}"> <label for="sp_{{ $v }}">{{ ($k=="ordenc") ? "Requisición": $v }}</label></div>
                                                            @if ($k =="ordenc") 
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_1" id="spsub_{{$k}}_1"> <label for="spsub_{{$k}}_1">Elaborada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>                            
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>   
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>   
                                                            
                                                            @elseif ($k =="ordenf") 
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_1" id="spsub_{{$k}}_1"> <label for="spsub_{{$k}}_1">Elaborada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_3" id="spsub_{{$k}}_3"> <label for="spsub_{{$k}}_3">En fabricación</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Fabricada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelado</label></div>
                                                            
                                                            @elseif ($k =="parcial") 
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Elaborada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>

                                                            @elseif ($k =="sm") 
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Elaborada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>
                                                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>
                                                            @endif

                                                    @endforeach
                                                </fieldset>
                                                <fieldset>
                                                    <legend>Origen</legend>
                                                    <div class="checkpair parent" rel="C"><input type="checkbox" name="or[]" value="C" id="or_C"> <label for="or_C">Cotización</label></div>
                                                        <div class="checkpair sub" parent="C"><input type="checkbox" name="orsub[]" value="C_0" id="orsub_C_0"> <label for="orsub_C_0">Sin Factura</label></div>
                                                        <div class="checkpair sub" parent="C"><input type="checkbox" name="orsub[]" value="C_1" id="orsub_C_1"> <label for="orsub_C_1">Con Factura</label></div>
                                                    
                                                    <div class="checkpair"><input type="checkbox" name="or[]" value="F" id="or_F"> <label for="or_F">Factura</label></div>


                                                    <div class="checkpair" ><input type="checkbox" name="or[]" value="R" id="or_R"> <label for="or_R">Requisición Stock</label></div>
                                                


                                                    <legend>Recolección</legend>
                                                    <div class="checkpair"><input type="checkbox" name="rec[]" value="1" id="rec_1"> <label for="rec_1">Chofer Entrega</label></div>
                                                    <div class="checkpair"><input type="checkbox" name="rec[]" value="2" id="rec_2"> <label for="rec_2">Cliente recoge</label></div>

                                                </fieldset>

                                            @if(in_array(auth()->user()->role->name,["Administrador", "ALEJANDRO GALICIA"]) || in_array(auth()->user()->department->name,["Administrador", "Ventas"]))
                                                <fieldset>
                                                    <legend>Sucursal</legend>
                                                    <div class="checkpair"><input type="checkbox" name="suc[]" value="San Pablo" id="suc_S"> <label for="suc_S">San Pablo</label></div>
                                                    <div class="checkpair"><input type="checkbox" name="suc[]" value="La Noria" id="suc_N"> <label for="suc_N">La Noria</label></div>
                                                </fieldset>
                                            @endif

                                            @if(in_array(auth()->user()->department->name, ["Administrador", "Ventas", "Auditoria"]) && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]))
                                                <fieldset>
                                                    <legend>Etiquetas embarques</legend>
                                                    @foreach($etiquetas as $etiqueta)
                                                        <div class="checkpair">
                                                            <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etq_{{$etiqueta->id}}">
                                                            <label for="etq_{{ $etiqueta->id }}">{{ $etiqueta->nombre}}</label>
                                                        </div>
                                                    @endforeach
                                                </fieldset>
                                            @endif

                                            @if(auth()->user()->department->name == "Embarques" && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]))
                                                <fielset>
                                                    <legend> Etiquetas de embarques </legend>
                                                    @foreach($etiquetas as $etiqueta)
                                                        @if(!in_array($etiqueta->nombre, ['N1', 'N2', 'N3', 'N4', 'PARCIALMENTE TERMINADO (SP)', 'PEDIDO EN PAUSA (SP)', 'PARCIALMENTE TERMINADO (LN)', 'PEDIDO EN PAUSA (LN)']))
                                                            <div class="checkpair">
                                                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etq_{{$etiqueta->id}}">
                                                                <label for="etq_{{ $etiqueta->id }}">{{ $etiqueta->nombre}}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </fieldset>
                                            @endif


                                            @if(auth()->user()->department->name == "Fabricación" && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]) && auth()->user()->office == "San Pablo")
                                                <fieldset>
                                                    <legend>Etiquetas fabricación</legend>
                                                    @foreach($etiquetas as $etiqueta)
                                                        @if (in_array($etiqueta->nombre, ['N1', 'N2', 'PARCIALMENTE TERMINADO (SP)', 'PEDIDO EN PAUSA (SP)']))
                                                            <div class="checkpair">
                                                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etq_{{$etiqueta->id}}">
                                                                <label for="etq_{{ $etiqueta->id }}">{{ $etiqueta->nombre}}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </fieldset>
                                            @endif

                                            @if(auth()->user()->department->name == "Fabricación" && in_array(auth()->user()->role->name, ["Administrador", "Empleado"]) && auth()->user()->office == "La Noria")
                                                <fieldset>
                                                    <legend>Etiquetas fabricación</legend>
                                                    @foreach($etiquetas as $etiqueta)
                                                        @if (in_array($etiqueta->nombre, ['N3', 'N4', 'PARCIALMENTE TERMINADO (LN)', 'PEDIDO EN PAUSA (LN)']))
                                                            <div class="checkpair">
                                                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etq_{{$etiqueta->id}}">
                                                                <label for="etq_{{ $etiqueta->id }}">{{ $etiqueta->nombre}}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </fieldset>
                                            @endif
                                            
                                            


                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div> 


            </form>
            
            <h5> Total de Pedidos: <span id="contador-pedidos">{{ count($lista) }}</span></h5>

            <!-- SECCION DE PEDIDOS -->

            <div class="row" id="Lista">
                
            </div>
            
            <input type="hidden" name="listaUrl" value="{{ url('pedidos2/dashboard/lista') }}" />
            
        




        

    </div> 
</main>

@endsection








@push('js')
{{-- Comment --}}

<script type="text/javascript" src="{{ asset('js/drp/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>--}} 
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 

\<?php
$manana = new \DateTime();
$manana->modify("+1 day");
$mananaString = $manana->format("Y-m-d");
?>
<script type="text/javascript" >
let haBuscado = false;

$(document).ready(function(){
/*
    $('input[name="fechas"]').daterangepicker({
    timePicker: false,
    minDate: new Date("2021-10-11"),
    maxDate: new Date("{{ $mananaString }}"),
    maxSpans:{
        "years":3
    },
    linkedCalendars: false,
    showDropdowns:true,
    //autoApply:true,
    autoUpdateInput:true,
    locale: {
      format: 'YYYY-MM-DD',
      "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
    }
    });

    $('input[name="fechas"]').on('apply.daterangepicker', function(ev, picker) {
        FormatearFechaDeInput();
    });


    var start = moment().subtract(7, 'months');
    var end = moment();

    //FormatearFecha(start,end);
    FormatearFechaDeInput();
*/

   
    
    $("#MuestraAvanzada a").click(function(e){
        e.preventDefault();
    MuestraAvanzada();
    });
    $("#MuestraSimple a").click(function(e){
        e.preventDefault();
    MuestraSimple();
    });
    MuestraSimple();


    //Buscar
    $("#buscarBoton").click(function(e){

        e.preventDefault();
        if(!haBuscado){
            haBuscado = true;
            $("#fbuscar input[name='p']").val("1");
            GetLista();
        }else{
            
            haBuscado = false;

            //Limpiar las checkboxes
            $("#fbuscar input[type='checkbox']").prop("checked", false);
            $("#fbuscar input[type='text'][name='termino']").val("");
            $("#fbuscar input[type='text'][name='fechas']").val("");
            $("#fbuscar select[name='orden_recibido']").val("");

            //Lipiar valores
            $("[name='querystring']").val("{}");
            
            //Recargar Dashboard
            $("#fbuscar input[name='p']").val("1");

            GetLista();
        }
    });

 
$("#nuevoExcelBtn").click(function (e) {
    e.preventDefault();

    let raw = $("input[name='querystring']").val();

    let filtros = {};
    if (raw && raw !== "{}") {
        filtros = JSON.parse(raw);
    } else {

        filtros = {};
        const fromForm = $("#fbuscar").serializeArray();
        for (let i = 0; i < fromForm.length; i++) {
            let name = fromForm[i].name;
            let value = fromForm[i].value;

            if (name.endsWith("[]")) {
                name = name.slice(0, -2);
                if (!filtros[name]) {
                    filtros[name] = [];
                }
                filtros[name].push(value);
            } else {
                filtros[name] = value;
            }
        }

    }

    filtros._ = Date.now(); 
    const queryString = $.param(filtros);
    const url = "{{ route('dashboard.exportar.excel') }}?" + queryString;

    //console.log("Exportando Excel con:", queryString);
    window.location.href = url;
});




    //paginacion
    $("body").on("click", ".paginacion a", function(e){
        e.preventDefault();
        let rel=$(this).attr("rel");

        GetLista(parseInt(rel));
    });

    //Masinfo
    $("body").on("click", ".masinfo", function(e){
        e.preventDefault();
        let href=$(this).attr("href");

        MiModal.showBg();

        $.ajax({
            url:href,
            error:function(err){alert(err.statusText);},
            type:"get",
            success:function(h){            
            MiModal.content(h);
            MiModal.width ="75vw";
            MiModal.show();
            }
        });
    });

    //NUEVO
    $(".nuevo").click(function(){
        window.location.href = $(this).attr("href");
    });


    
    $("input[name='termino']").on("keyup",function(e){
        if(e.keyCode==13){$("#buscarBoton").click();}
    });



    
    //ICONS
    $("body").on("click",".iconSet a",function(e){
        e.preventDefault();
        e.stopPropagation();
        let href = $(this).attr("href"); 
        

        let isFB = $(this).hasClass("followBtn");
        if(isFB){
            Follow(this);
            return false;
        }

        
        $.ajax({
            url:href,
            success:function(h){
                MiModal.content(h);
                MiModal.show();     
            }
        });          

    });

    $("#fbuscar").on("submit", function(e){

        e.preventDefault();

    })








    Querystring();

    GetLista();

});



function Querystring(){
    let qs = $("[name='querystring']").val();
    var qsob = JSON.parse(qs);
    if(qsob == null){
        return;
    }
    console.log(qsob);

    if(typeof(qsob.termino) != "undefined" && qsob.termino != null ){
     $("[name='termino']").val(qsob.termino);  

    }
    //console.log(num);
    if(typeof(qsob.st) != "undefined"){
        for(i in qsob.st){
            $("[name='st[]'][value='"+qsob.st[i]+"']").prop("checked",true);  
        }    

    }
    if(typeof(qsob.fechas) != "undefined" && qsob.fechas != null){
     $("[name='fechas']").val(qsob.fechas);   
     var fechasArr = qsob.fechas.split(' - ');
     let ini = new Date(fechasArr[0]+"T00:00:00");
     let fin = new Date(fechasArr[1]+"T23:59:59");
     FormatearFechaDate(ini, fin,"qs");

    }
    //console.log(num);
    if(typeof(qsob.sp) != "undefined"){
        for(i in qsob.sp){
            $("[name='sp[]'][value='"+qsob.sp[i]+"']").prop("checked",true);  
        } 
 
    }
    if(typeof(qsob.or) != "undefined"){
        for(i in qsob.or){
            $("[name='or[]'][value='"+qsob.or[i]+"']").prop("checked",true);  
        }     
    }
    if(typeof(qsob.suc) != "undefined"){
        for(i in qsob.suc){
            $("[name='suc[]'][value='"+qsob.suc[i]+"']").prop("checked",true);  
        }   
    }

    if(typeof(qsob.orden_recibido) != "undefined"){
        $("[name='orden_recibido']").val(qsob.orden_recibido);
    }


}

function CuentaFiltros(){
    var num=0;
    num += ($("[name='termino']").val().length > 0 )? 1 : 0 ;
    num += ($("[name='st[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='sp[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='or[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='suc[]']:checked").length > 0 )? 1 : 0 ;
    let b = (num>0)?true:false;
    MuestraIconFiltros(b);
    console.log(num);
}


function MuestraAvanzada(){
    $(".Avanzados").slideDown();
    $("#MuestraSimple").show();
    $("#MuestraAvanzada").hide();
}
function MuestraSimple(){
    $(".Avanzados").slideUp();
    $("#MuestraSimple").hide();
    $("#MuestraAvanzada").show();
}



function GetLista(p) {
    if (typeof (p) === "undefined") { p = 0; }
    console.log("GetLista");

    if (p !== 0) {
        $("#fbuscar [name='p']").val(p);
    }

    $("#Lista").html("<p>Cargando...</p>");
    MuestraSimple();

    let href = $("[name='listaUrl']").val();

    $("#fbuscar").ajaxSubmit({
        url: href,
	type: 'POST',
        error: function (err) {
            alert(err.statusText);
        },
        success: function (h) {
            $("#Lista").html(h);

            setTimeout(() => {
                ActualizaContadorPedidos();
            }, 10);

            $("#fbuscar [name='p']").val(1);
            $("#Lista").tooltip();

           
            let filtrosActuales = $("#fbuscar").serializeArray();
            let queryObject = {};

            for (let i = 0; i < filtrosActuales.length; i++) {
                let name = filtrosActuales[i].name;
                let value = filtrosActuales[i].value;

                if (name.endsWith("[]")) {
                    name = name.slice(0, -2);
                    if (!queryObject[name]) {
                        queryObject[name] = [];
                    }
                    queryObject[name].push(value);
                } else {
                    if (queryObject[name]) {
                        if (!Array.isArray(queryObject[name])) {
                            queryObject[name] = [queryObject[name]];
                        }
                        queryObject[name].push(value);
                    } else {
                        queryObject[name] = value;
                    }
                }
            }

            $("[name='querystring']").val(JSON.stringify(queryObject));
            //console.log("querystring actualizado:", queryObject);

          
            
        }
    });

    
    $(".checkpair.sub").hide();
    $(".checkpair.parent").click(function () {
        let ch = $(this).find(":checkbox").is(":checked");
        let rel = $(this).attr("rel");
        if (ch) {
            $(".checkpair.sub[parent='" + rel + "']").show();
        } else {
            $(".checkpair.sub[parent='" + rel + "']").hide();
        }
    });
}




function GetListaExcel(){
    
    MuestraSimple();
    $("#fbuscar [name='excel']").val(1);

    $("#fbuscar").submit();

    //Subfiltros
    $(".checkpair.sub").hide();
    $(".checkpair.parent").click(function(){
        let ch = $(this).find(":checkbox").is(":checked");
        let rel = $(this).attr("rel");
        if(ch){
            $(".checkpair.sub[parent='"+rel+"']").show();
        }else{
            $(".checkpair.sub[parent='"+rel+"']").hide();
        }       
    });
    $("#fbuscar [name='excel']").val(0);
}



function GetListaPre(p){
    if(typeof(p)=="undefined"){p=0;}
    
    let href = $("[name='listaUrl']").val();
    let datos = GeneraFiltros();
    if(p>0){
        datos["p"]=p;
    }
    

    $("#Lista").html("<p>Cargando...</p>");
    $.ajax({
        url:href,
        method:"get",
        data:datos,
        error:function(err){alert(err.statusText);},
        success:function(h){
        $("#Lista").html(h);
        }
    });
}

function GeneraFiltros(){
    let terminoVal = $("[name='termino']").val();

    let fechas = $("[name='fechas']").val();
    let fechasArr = fechas.split(' - ');
    if(fechasArr.length < 2){alert("fechas Arr");}

    let desdeVal = fechasArr[0];
    let hastaVal = fechasArr[1];

console.log(desdeVal);
console.log(hastaVal);


return {termino:terminoVal, desde:desdeVal, hasta:hastaVal};
}


function FormatearFecha(start,end,label){
    const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    };
    let v = start._d.toLocaleDateString("es-MX",options)+" - "+end._d.toLocaleDateString("es-MX",options);
    $("#MuestraFecha").text(v);
}
function FormatearFechaDate(start,end,label){
    const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    };
    console.log(end);
    let v = start.toLocaleDateString("es-MX",options)+" - "+end.toLocaleDateString("es-MX",options);
    $("#MuestraFecha").text(v);
}

function FormatearFechaDeInput() {
    let completo = $("input[name='fechas']").val();

    if (typeof completo !== 'string' || !completo.includes(" - ")) {
        console.warn("Valor de 'fechas' no válido o vacío:", completo);
        return;
    }

    let partes = completo.split(' - ');
    if (partes.length !== 2) {
        console.warn("Formato inesperado en 'fechas':", completo);
        return;
    }

    let miStart = new Date(partes[0] + " 00:00:00");
    let miEnd = new Date(partes[1] + " 23:59:59");

    console.log(partes);
    console.log(miStart);
    console.log(miEnd);
    console.log("FormatearFechaDate");

    FormatearFechaDate(miStart, miEnd);
}

   

function MuestraIconFiltros(es){
    if(es){
        $(".filtrosIcon").css("display","inline-block");
    }else{
        $(".filtrosIcon").css("display","none");
    }
}

function LimpiaFiltros(){
    let fechaVal = $("[name='fechas']").val();
    
    document.getElementById("fbuscar").reset();  
    //alert("Limpia");
    $("[name='fechas']").val(fechaVal);
    FormatearFechaDeInput();
}




function Follow(ob){
    let hrefyes = $(ob).attr("href");
    let hrefno = $(ob).attr("hrefno");
    let isno = $(ob).hasClass("no");

    if(isno){
        AjaxGetJson(hrefno,FollowNoRespuesta);
        $(ob).removeClass("no");
        $(ob).attr("title","Dejar de seguir");
    }else{
        AjaxGetJson(hrefyes,FollowRespuesta);
        $(ob).addClass("no");
        $(ob).attr("title","A mis pedidos");
    }
    
}

function FollowRespuesta(json){
    if(json.status == 1){

    }else{
        alert(json.errors);
    }
}
function FollowNoRespuesta(json){
    if(json.status == 1){

    }else{
        alert(json.errors);
    }
}


//Función para actualizar el contador de pedidos
function ActualizaContadorPedidos(){

    const total = document.querySelectorAll('#Lista .pedido-item').length; 
    document.getElementById('contador-pedidos').textContent = total;

}


// Botón para Excel General
$("#excelDashboardBtn").click(function (e) {
    e.preventDefault();

    let raw = $("input[name='querystring']").val();
    let filtros = {};

    if (raw && raw !== "{}") {
        filtros = JSON.parse(raw);
    } else {
        const fromForm = $("#fbuscar").serializeArray();
        for (let i = 0; i < fromForm.length; i++) {
            let name = fromForm[i].name;
            let value = fromForm[i].value;

            if (name.endsWith("[]")) {
                name = name.slice(0, -2);
                if (!filtros[name]) filtros[name] = [];
                filtros[name].push(value);
            } else {
                filtros[name] = value;
            }
        }
    }

    filtros._ = Date.now();
    const queryString = $.param(filtros);
    const url = "{{ route('dashboard.exportar.excel') }}?" + queryString;
    window.location.href = url;
});


// Botón para Excel de Fabricación
$("#excelFabricacionBtn").click(function (e) {
    e.preventDefault();

    let raw = $("input[name='querystring']").val();
    let filtros = {};

    if (raw && raw !== "{}") {
        filtros = JSON.parse(raw);
    } else {
        const fromForm = $("#fbuscar").serializeArray();
        for (let i = 0; i < fromForm.length; i++) {
            let name = fromForm[i].name;
            let value = fromForm[i].value;

            if (name.endsWith("[]")) {
                name = name.slice(0, -2);
                if (!filtros[name]) filtros[name] = [];
                filtros[name].push(value);
            } else {
                filtros[name] = value;
            }
        }
    }

    filtros._ = Date.now();
    const queryString = $.param(filtros);
    const url = "{{ route('reportes.fabricacion-excel') }}?" + queryString;
    window.location.href = url;
});



</script>

@endpush

