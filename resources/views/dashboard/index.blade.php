s<?php 

use App\Pedidos;
use App\Http\Controllers\Pedidos2Controller;

?>

@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

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
        


            <form id="fbuscar" action="#" method="GET">

            
            
                <input type="hidden" name="p" value="1">
                <input type="hidden" name="excel" value="0">
                <input type="hidden" name="querystring" value="{}">

                <div class="row align-items-center mb-3">
                @php
                    date_default_timezone_set("America/Mexico_City");
                    $hoy = date("Y-m-d");
                    $inicio = (new DateTime())->modify("-7 month")->format("Y-m-d");
                @endphp

                <div class="col-md-3">
                    <input type="text" name="termino" class="form-control form-control-sm" placeholder="Buscar...">
                </div>

                <div class="col-md-3">
                    <label for="fechas"><span id="MuestraFecha"></span></label>
                    <input type="text" name="fechas" id="fechas" class="form-control form-control-sm"
                        value="{{ $inicio }} - {{ $hoy }}"
                        placeholder="Rango de Fechas">
                    <small class="form-text text-muted">Selecciona primero la fecha inicial y después la final</small>
                </div>

                <div class="col-md-2">
                    <button type="button" id="buscarBoton" class="btn btn-sm btn-primary w-100">Buscar</button>
                </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-secondary w-100" data-toggle="modal" data-target="#modalBusquedaAvanzada">
                            Búsqueda Avanzada
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- SECCION DE PEDIDOS -->

            <div class="row" id="Lista">

            </div>
            
            <input type="hidden" name="listaUrl" value="{{ url('pedidos2/dashboard/lista') }}" />
            
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
                        {{-- AQUI VAN LOS FILTROS AVANZADOS --}}
                    </div> 
                </div> 
            </div> 
        </div> 

    </div> 
</main>

@endsection








@push('js')
{{-- Comment --}}

<script type="text/javascript" src="{{ asset('js/drp/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 

\<?php
$manana = new \DateTime();
$manana->modify("+1 day");
$mananaString = $manana->format("Y-m-d");
?>
<script type="text/javascript" >
    
$(document).ready(function(){

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
        GetLista();
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



function GetLista(p){
    if(typeof(p)=="undefined"){p=0;}
console.log("GetLista");
    if(p != 0){
        $("#fbuscar [name='p']").val(p);
    }


    $("#Lista").html("<p>Cargando...</p>");

    MuestraSimple();

    let href = $("[name='listaUrl']").val();

    $("#fbuscar").ajaxSubmit({
        
        url: href,
        error:function(err){alert(err.statusText);},
        success:function(h){
        $("#Lista").html(h);

        LimpiaFiltros();
        $("#fbuscar [name='p']").val(1);
        $("#Lista").tooltip();

        }
    });

    
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


    //$("#Lista").tooltip();

  //  CuentaFiltros();
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


function FormatearFechaDeInput(){
    let completo = $("input[name='fechas']").val();
    let partes = completo.split(' - ');
    let miStart = new Date(partes[0]+" 00:00:00");
    let miEnd = new Date(partes[1] + " 23:59:59");
    console.log(partes);
    console.log(miStart);
    console.log(miEnd);
    console.log("FormatearFechaDate");
    FormatearFechaDate(miStart,miEnd);
    /*
    const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    };
    let v = start._d.toLocaleDateString("es-MX",options)+" - "+end._d.toLocaleDateString("es-MX",options);
    $("#MuestraFecha").text(v);
    */
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



</script>

@endpush

