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
    if($user->department_id == 9){$estatuses=[];}
    $estatuses[10]="Recibido por Auditoría";
}
?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos y ordenes de fabricación')])

@section('content')
<?php $statuses = Pedidos2::StatusesCat(); ?>

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
            @if (in_array(auth()->user()->role->name, ["Administrador","Empleado"]) || in_array(auth()->user()->department->name, ["Administrador", "Embarques", "Fabricación"]))
                <button type="button" class="btn btn-secondary" id="modoEtiquetas"> Cambiar etiquetas</button>
            @endif
            <button type="button" class="btn btn-secondary" id="modoCombo"> Cambio de Estatus y Cambio de Etiquetas</button>
        </div>

        <form id="festatus" action="{{url('pedidos2/multie')}}" method="get">
            @csrf
            <input type="hidden" name="modo" id="inputModo" value="estatus" />

            <div id="bloqueEstatus" style="display:none;">
                <select name="estatus" class="form-control" id="selectEstatus">
                    <option value=""> -Elija uno- </option>
                    @foreach ($estatuses as $k => $v)
                        <option value="{{$k}}" {{ ($k==$estatus) ? "selected" : "" }}>{{$v}}</option>
                    @endforeach
                </select>
            </div>

            <center>
                <div class="dropdown-checkbox" id="bloqueEtiquetas">
                    <button type="button" class="dropdown-toggle">
                        Seleccionar etiquetas
                    </button>
                    <div class="dropdown-menu-checkboxes">

                    @php
                        $user = auth()->user();

                        $TipoEtiqueta = null;

                            if ($user->department->name === 'Administrador') {
                                $TipoEtiqueta = 'administrador';
                            }
                            elseif ($user->department->name === 'Embarques') {
                                $TipoEtiqueta = 'embarques';
                            }
                            elseif ($user->department->name === 'Fabricación' && $user->office === 'San Pablo') {
                                $TipoEtiqueta = 'fabricacion_sp';
                            }
                            elseif ($user->department->name === 'Fabricación' && $user->office === 'La Noria') {
                                $TipoEtiqueta = 'fabricacion_ln';
                            }
                            elseif ($user->department->name === 'Auditoria') {
                                $TipoEtiqueta = 'auditoria';
                            }

                        $EtiquetasVisibles = $etiquetas;

                            if(in_array($user->role->name, ['Administrador', 'Empleado']) && !in_array($user->department->name, ['Administrador', 'Auditoria'])){
                                $EtiquetasVisibles = $EtiquetasVisibles->filter(function ($etiqueta){
                                    return !in_array($etiqueta->nombre, config('etiquetas.ventas_ocultas'));
                                });
                            }

                        $EtiquetasFiltradas = collect();

                            if ($TipoEtiqueta == 'administrador'){
                                $EtiquetasFiltradas = $EtiquetasVisibles;
                            }

                            elseif ($TipoEtiqueta == 'embarques'){
                                $EtiquetasFiltradas = $EtiquetasVisibles->filter(fn($e) =>
                                    !in_array($e->nombre, config('etiquetas.embarques_excluir'))
                                );
                            }

                            elseif ($TipoEtiqueta == 'fabricacion_sp'){
                                $EtiquetasFiltradas = $EtiquetasVisibles->filter(fn($e) =>
                                    in_array($e->nombre, config('etiquetas.fabricacion_sp'))
                                );
                            }

                            elseif ($TipoEtiqueta == 'fabricacion_ln'){
                                $EtiquetasFiltradas = $EtiquetasVisibles->filter(fn($e) =>
                                    in_array($e->nombre, config('etiquetas.fabricacion_ln'))
                                );
                            }

                            elseif ($TipoEtiqueta == 'auditoria'){
                                $EtiquetasFiltradas = $EtiquetasVisibles->filter(fn($e) =>
                                    in_array($e->nombre, config('etiquetas.auditoria'))
                                );
                            }

                    @endphp

                        @if($EtiquetasFiltradas->count() > 0)
                            @foreach($EtiquetasFiltradas as $etiqueta)
                                <label class="dropdown-item-checkbox">
                                    <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}">
                                    <span class="etiqueta-color" style="background-color: {{ $etiqueta->color ?? '#CCC' }}">
                                        {{ strtoupper($etiqueta->nombre) }}
                                    </span>
                                </label>
                            @endforeach
                        @endif

                    </div>
                </div>

                <div style="margin-top: 6px;" id="bloqueQuitarEtiquetas">
                    <label style="font-size: 14px;">
                        <input type="checkbox" id="modoEliminarEtiquetas"/>
                        Quitar etiquetas
                    </label>
                </div>
            </center>
        </form>

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
                    <div class="ResultsDiv" id="ResultsCombo" style="display:none;"></div>
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
        if($("#inputModo").val()==='combo'){
            $("#bloqueEtiquetas").show();
            $("#bloqueQuitarEtiquetas").show();
        }
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
            $("#confirmButton").hide();
        }
    });

    $("[name='shipment']").on("click",function(){
        $(".Pedido").removeClass("focus");
    });

    $("#confirmButton").click(function(){
        Enviar();
    }).hide();

    $("#bloqueEstatus").hide();
    $("#bloqueEtiquetas").hide();
    $("#bloqueQuitarEtiquetas").hide();
    $(".Cuerpo").hide();

    $("#modoEstatus").click(function(){
        $("#bloqueEstatus").show();
        $("#bloqueEtiquetas").hide();
        $("#bloqueQuitarEtiquetas").hide();
        $(".Cuerpo").hide();
        $("#inputModo").val("estatus");
        $("#ResultsEstatus").show();
        $("#ResultsEtiquetas").hide().html("");
        $("#ResultsCombo").hide().html("");
        $("[name='etiquetas[]']").prop("checked", false);
        $("#confirmButton").val("Confirmar estatus");
        $("[name='shipment']").val("");
    });

    $("#modoEtiquetas").click(function(){
        $("#bloqueEstatus").hide();
        $("#bloqueEtiquetas").show();
        $("#bloqueQuitarEtiquetas").show();
        $(".Cuerpo").show();
        $("#inputModo").val("etiquetas");
        $("#ResultsEstatus").hide().html("");
        $("#ResultsEtiquetas").show();
        $("#ResultsCombo").hide().html("");
        $("[name='estatus']").val("");
        $("#confirmButton").val("Aplicar etiquetas");
        $("[name='shipment']").val("");
    });

    $("#modoCombo").click(function(){
        $("#bloqueEstatus").show();
        $("#bloqueEtiquetas").hide();
        $("#bloqueQuitarEtiquetas").hide();
        $(".Cuerpo").hide();
        $("#inputModo").val("combo");
        $("#ResultsEstatus").hide().html("");
        $("#ResultsEtiquetas").hide().html("");
        $("#ResultsCombo").show().html("");
        $("[name='etiquetas[]']").prop("checked", false);
        $("[name='estatus']").val("");
        $("#confirmButton").val("Aplicar todo");
        $("[name='shipment']").val("");
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
    } else if (modo === "etiquetas") {
        $("#ResultsEtiquetas").append(item);
        $("#ResultsEtiquetas .pc[rel='"+rel+"']").html(focused);
    } else {
        $("#ResultsCombo").append(item);
        $("#ResultsCombo .pc[rel='"+rel+"']").html(focused);
    }

    $("#confirmButton").show();
}

function Enviar(){
    let modo = $("#inputModo").val();
    let dd = new FormData();
    let href = $("#confirmButton").attr("href");
    dd.append("modo", modo);

    let selector = "#ResultsEstatus";
    if(modo === "etiquetas") selector = "#ResultsEtiquetas";
    if(modo === "combo") selector = "#ResultsCombo";

    let quitarEtiquetas = $("#modoEliminarEtiquetas").is(":checked");
    dd.append("quitar_etiquetas", quitarEtiquetas ? 1: 0);

    $(selector + " .Pedido").each(function(){
        dd.append("lista[]", $(this).attr("rel"));
    });

    dd.append("_token", $("[name='_token']").val());

    if(modo === "estatus" || modo === "combo") {
        let est = $("[name='estatus']").val();
        if(!est) {
            alert("Selecciona un estatus.");
            return;
        }
        dd.append("catalogo", (est == 2 || est == 10) ? "order" : "morder");
        dd.append("status_id", est);
    }

    if(modo === "etiquetas" || modo === "combo") {
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

