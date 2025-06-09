<?php
use App\Pedidos2;
use App\Libraries\Tools;

$estatuses =[2=>"Recibido por embarques",3=>"En fabricación",4=>"Fabricado"];



    if($user->role_id != 1 && $user->department_id == 4){
        $estatuses = [2=>"Recibido por embarques"];
    }

    if($user->role_id != 1 && $user->department_id == 5){
        $estatuses = [3=>"En fabricación",4=>"Fabricado"];
    }

    if($user->role_id == 1 || $user->department_id == 9){
        if($user->department_id == 9){$estatuses=[];} //VACIAR SI ES AU DITORIA
        $estatuses[10]="Recibido por Auditoría";
    }

 

?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos y ordenes de fabricación')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/multie.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/etiquetas/etiquetamultie.css?x='.rand(0,999)) }}" />

<main class="content">



    <div class="card Fila">
        <center> <a class="regresar" href="{{ url('pedidos2') }}">&laquo; Regresar</a> </center>
    </div>

    <div class="card">
        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Cambio de estatus masivo</h4>
            </div>
        </div>

        <p>&nbsp;</p>

        <div class="center" style="margin-bottom: 15px;">
            <button type="button" class="btn btn-secondary" id="modoEstatus"> Cambiar estatus</button>
            @if(in_array(auth()->user()->role->name, ['Administrador', 'ALEJANDRO GALICIA']) || in_array(auth()->user()->department_id, [2, 4]))
                <button type="button" class="btn btn-secondary" id="modoEtiquetas"> Cambiar etiquetas</button>
            @endif
        </div>

        {{-- FORMULARIO INICIA AQUÍ --}}
        <form id="festatus" action="{{url('pedidos2/multie')}}" method="get">
            @csrf
            <input type="hidden" name="modo" id="inputModo" value="estatus" />

            <div id="bloqueEstatus" style="display:none;">
                <select name="estatus" class="form-control">
                    <option value=""> -Elija uno- </option>
                    @foreach ($estatuses as $k => $v)
                    <option value="{{$k}}" {{ ($k==$estatus) ? "selected" : "" }}>{{$v}}</option>
                    @endforeach
                </select>
            </div>

            <center>
                <div class="dropdown-checkbox">
                    <button type="button" class="dropdown-toggle">
                        Seleccionar etiquetas
                    </button>
                    <div class="dropdown-menu-checkboxes">
                        @foreach($etiquetas as $etiqueta)
                        <label class="dropdown-item-checkbox">
                            <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}">
                            <span class="etiqueta-color" style="background-color: {{$etiqueta->color ?? 'CCC'}}">
                                {{ strtoupper($etiqueta->nombre) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                {{--
                    <div style="margin-top: 10px;">
                        <button type="button" class="btn btn-primary" id="confirmarEtiquetasBtn">
                            Aplicar etiquetas
                        </button>
                    </div>
                --}}
                    <div style="margin-top: 6px;">
                        <label style="font-size: 14px;">
                            <input type="checkbox" id="modoEliminarEtiquetas"/>
                            Quitar etiquetas
                        </label>
                    </div>
            </center>

        </form>
        {{-- FORMULARIO TERMINA AQUÍ --}}

        <div class="Cuerpo">
            <p>Escriba un shipment en el campo de texto. Aparecerá uno o más pedidos en la lista.</p>
            <p>Puede usar la tecla de <b>Flecha Abajo</b> o la tecla de <b>Flecha Arriba</b> para recorrer varios resultados.</p>
            <p>Use la tecla <b>ENTER</b> para elegir el pedido para cambiar al estatus elegido.</p>

            <section class="SearchZone">
                <div class="SearchDiv">
                    <div class="Filita">
                        <input type="text" class="" name="shipment" size="14" maxlength="16" href="{{ url('pedidos2/multie_lista') }}" />
                        <span class="buscar"></span>
                    </div>
                    <div id="ShipsListaDiv"></div>
                </div>

                <div>
                    <center><h4><b>Elegidos</b></h4></center>
                    <div class="ResultsDiv" id="ResultsEstatus"></div>
                    <div class="ResultsDiv" id="ResultsEtiquetas" style="display:none;"></div>
                </div>

                <div>
                    <input type="button" href="{{ url('pedidos2/set_multistatus') }}"
                        class="form-control" id="confirmButton" value="Confirmar" />
                </div>
            </section>
        </div>
    </div>

</main>


@endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 
<script type="text/javascript" src="{{ asset('js/etiquetas/etiquetasmultie.js') }}"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("[name='estatus']").change(function(){
        $(".Cuerpo").show();
    });

    $("[name='shipment']").change(function(){
        let href = $(this).attr("href");
        let val = $(this).val();
        let estatus = $("[name='estatus']").val();
        let modo = $("#inputModo").val();

        let etiquetas = [];
        $("[name='etiquetas[]']:checked").each(function(){
            etiquetas.push($(this).val());
        });

        $.ajax({
            url: href,
            data: {
                term: val,
                estatus: estatus,
                etiquetas: etiquetas,
                modo: modo
            },
            error: function(err){
                alert(err.statusText);
            },
            success: function(h){
                $("#ShipsListaDiv").html(h);
                ActivaShipments();
            }
        });
    });

    $("body").on("click", ".SearchDiv .Pedido", function(){
        Enfoca(this);
        setTimeout(AgregarALista, 100);        
    });

    $("body").on("click",".PedidoPar a.del",function(){
        $(this).closest(".PedidoPar").remove();
        
        if ($(".PedidoPar").length == 0) {
            $("#confirmButton").hide(); // ⬅️ CAMBIO
        }
    });

    
    $("[name='shipment']").on("click",function(){
        $(".Pedido").removeClass("focus"); 
    });

    
    $("#confirmButton").click(function(){
        Enviar();
    }).hide();

$("#confirmarEtiquetasBtn").click(function(){

    let hayEtiquetas = $("[name='etiquetas[]']:checked").length > 0;
    if(!hayEtiquetas){

        alert("Ni ha seleccionado etiquetas");
        return;

    }

    let hayPedidos = $("ResultsEtiquetas .Pedido").length > 0;
    if(!hayPedidos){

        alert("No ha agregado ningún pedido para aplicar etiquetas");
        return;

    }

    $("#inputModo").val("etiquetas");
    Enviar();

})


    //SIN ESCOGER ESTATUS
    $("#bloqueEstatus").hide();
    $(".dropdown-checkbox").hide();
    $("#confirmarEtiquetasBtn").hide();
    $("#modoEliminarEtiquetas").closest('div').hide();
    $("#bloqueEtiquetas").hide();
    $(".Cuerpo").hide();

    //CAMBIAR A MODO ESTATUS
    $("#modoEstatus").click(function(){
    $("#bloqueEstatus").show();
    $(".dropdown-checkbox").hide();
    $("#confirmarEtiquetasBtn").hide();
    $("#modoEliminarEtiquetas").closest('div').hide();
    $(".Cuerpo").hide();
    $("#inputModo").val("estatus");
    $("#ResultsEstatus").show();
    $("#ResultsEtiquetas").hide().html("");
    $("[name='etiquetas[]']").prop("checked", false);
    $("#confirmButton").val("Confirmar estatus");
});

    //CAMBIAR A MODO ETIQUETAS
   $("#modoEtiquetas").click(function(){
    $("#bloqueEstatus").hide();
    $(".dropdown-checkbox").show();
    $("#confirmarEtiquetasBtn").show();
    $("#modoEliminarEtiquetas").closest('div').show();
    $(".Cuerpo").show();
    $("#inputModo").val("etiquetas");
    $("#ResultsEstatus").hide().html("");
    $("#ResultsEtiquetas").show();
    $("[name='estatus']").val("");
    $("#confirmButton").val("Aplicar etiquetas");
});

});

function Enfoca(ob){
    $(".Pedido").removeClass("focus");
    $(ob).focus();
    $(ob).addClass("focus");
}

function ActivaShipments(){
    let cuantos = $("#ShipsListaDiv .Pedido").length;
    if(cuantos == 1){
        $("#ShipsListaDiv .Pedido").eq(0).click();
    }
}

function AbrirConfirmacion(){
    let focused = $(".ShipsLista .Pedido.focus");
    let href = $(focused).attr("del");
    let strEstatus = $("[name='estatus']").find(":selected").text();
    let idEstatus = $("[name='estatus']").val();
    let txt =  "Confirma que este pedido cambiará al estatus '"+strEstatus+"'";
    if(confirm(txt)){
        $.ajax({
            url:href,
            datatype:"json",
            data:{ids:idEstatus},
            success:function(json){
                FocusBuscar();
                $("[name='shipment']").change();
            }
        });
    }
}

function FocusBuscar(){
    $(".SearchDiv [name='shipment']").focus();
}

function AgregarALista() {
    let focused = $(".ShipsLista .Pedido.focus");
    let rel = $(focused).attr("rel");
    let modo = $("#inputModo").val();

    $(focused).removeClass("focus");

    let item = "<div class='PedidoPar'>";
    item += "<div class='pc' rel='"+rel+"'></div>";
    item += "<div><a class='del'>X</a></div>";
    item += "</div>";

    if (modo === "estatus") {
        $("#ResultsEstatus").append(item);
        $("#ResultsEstatus .pc[rel='"+rel+"']").html(focused);
    } else {
        $("#ResultsEtiquetas").append(item);
        $("#ResultsEtiquetas .pc[rel='"+rel+"']").html(focused);
    }

    $("#confirmButton").show(); 
    FocusBuscar();
}

function Enviar(){
    let modo = $("#inputModo").val();
    let dd = new FormData();
    let href = $("#confirmButton").attr("href");
    dd.append("modo", modo);

    let selector = (modo === "estatus") ? "#ResultsEstatus" : "#ResultsEtiquetas";

    let quitarEtiquetas = $("#modoEliminarEtiquetas").is(":checked");
    dd.append("quitar_etiquetas", quitarEtiquetas ? 1: 0);

    $(selector + " .Pedido").each(function(){
        dd.append("lista[]", $(this).attr("rel"));
    });

    dd.append("_token", $("[name='_token']").val());

    if(modo === "estatus") {
        let est = $("[name='estatus']").val();
        if(!est) {
            alert("Selecciona un estatus.");
            return;
        }
        dd.append("catalogo", (est == 2 || est == 10) ? "order" : "morder");
        dd.append("status_id", est);
    }

    if(modo === "etiquetas") {
        if($("[name='etiquetas[]']:checked").length === 0){
            alert("Selecciona al menos una etiqueta.");
            return;
        }
        $("[name='etiquetas[]']:checked").each(function(){
            dd.append("etiquetas[]", $(this).val());
        });
    }
    
    $.ajax({
        url: href,
        data: dd,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: "json",
        success: function(json){
            if(json.status == 1){
                alert(json.value + " registros cambiados");
                $(selector).html("");
                $("#confirmButton").hide();
            } else {
                alert(json.errors || "Error al procesar la solicitud.");
            }
        }
    });
}

</script>


@endpush

