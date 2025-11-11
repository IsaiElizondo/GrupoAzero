@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Crear Pedido')])

@section('content')


<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<input type="hidden" name="url_previous" value="{{ url()->previous()  }}"/>


<main class="content" >
    <section class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido Nuevo</h4>
         </div>
    </div>



        <form action="{{ url('pedidos2/crear') }}" id="FNuevo" class="Cuerpo" method="post" enctype="multipart/form-data">
            @csrf

            <fieldset>
                <h3 class="center">Iniciar con </h3>
                <div class="Eleccion">
                    @if ($user->role_id ==1 || in_array($user->department_id,[3,4,8]))
                        <button class="Tipo" rel="F">Factura</button>
                    @endif
                    @if ($user->role_id ==1 || in_array($user->department_id,[3,4]))
                        <button class="Tipo" rel="C" title="Cotización">Pedido</button>
                    @endif
                    @if ($user->role_id ==1 || in_array($user->department_id,[5,7]))
                        <button class="Tipo" rel="R">Requisición Stock</button>
                    @endif
                </div>
            </fieldset>

            <input type="hidden" name="origin" value=""/>
            <div>&nbsp;</div>

            <fieldset class="Tiposet or">
                <dl>
                    <dt><label rel="code" F="Folio Factura *" C="Num Cotizacion *" R="Num Requisición *">Folio/Código</label></dt>
                    <dd><input type="text" name="code" class="form-control" maxlength="24" autocomplete="off" placeholder="Min 4 caracteres" /></dd>

                    
                    {{-- ///////// BLOQUE CLIENTE ////////// --}}
                    
                    <dt rel="client" id="dt_cliente" style="display:none;">
                        <label>Tipo de Cliente *</label>
                    </dt>
                    <dd rel="client" id="dd_cliente" style="display:none;">
                        <select name="tipo_cliente" id="tipo_cliente" class="form-control">
                            <option value="">--Selecciona una opción--</option>
                            <option value="general">Cliente General</option>
                            <option value="existente">Cliente Existente</option>
                            <option value="nuevo">Crear Cliente</option>
                        </select>

                        {{--///// BLOQUE CLIENTE GENERAL ////--}}
                        <div id="bloque_general" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <label class="mt-2">Código Cliente *</label>
                            <input type="text" name="client" class="form-control" maxlength="45" placeholder="Código de Cliente General">

                            <label class="mt-2">Dirección *</label>
                            <input type="text" name="direccion_general" class="form-control" maxlength="100" placeholder="Dirección del Cliente General">
                        </div>

                        {{--///// BLOQUE CLIENTE EXISTENTE /////--}}
                        <div id="bloque_existente" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <label class="mt-2">Buscar Cliente *</label>
                            <input type="text" id="buscar_cliente" class="form-control" placeholder="Buscar por nombre o código">
                            <input type="hidden" name="cliente_id" id="cliente_id">

                            <label class="mt-2">Dirección *</label>
                            <select name="cliente_direccion_id" id="cliente_direccion_id" class="form-control">
                                <option value="">--Selecciona una dirección--</option>
                            </select>
                            <button type="button" id="btn_nueva_direccion" class="btn btn-sm btn-secondary mt-2">+ Agregar Nueva Dirección</button>

                            <div id="form_nueva_direccion" style="display:none; border:1px solid #ddd; padding:10px; margin-top:8px; border-radius:6px;">
                                <h5>Nueva Dirección</h5>

                                <div class="form-group">
                                    <label>Nombre de la dirección *</label>
                                    <input type="text" id="nombre_direccion" class="form-control mb-2" placeholder="Nombre identificativo para la dirección">
                                </div>

                                <div class="form-group">
                                    <label>Dirección *</label>
                                    <input type="text" id="direccion" class="form-control mb-2" placeholder="Calle, número, colonia">
                                </div>

                                <div class="row">
                                    <div class="col-md-4"><input type="text" id="ciudad" class="form-control mb-2" placeholder="Ciudad"></div>
                                    <div class="col-md-4"><input type="text" id="estado" class="form-control mb-2" placeholder="Estado"></div>
                                    <div class="col-md-4"><input type="text" id="codigo_postal" class="form-control mb-2" placeholder="CP"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6"><input type="text" id="celular" class="form-control mb-2" placeholder="Celular"></div>
                                    <div class="col-md-6"><input type="text" id="nombre_recibe" class="form-control mb-2" placeholder="¿Quién recibe?"></div>
                                </div>

                                <input type="text" id="url_mapa" class="form-control mb-2" placeholder="https://maps...">
                                <textarea id="instrucciones" class="form-control mb-2" rows="2" placeholder="Instrucciones de entrega"></textarea>
                                <button type="button" id="guardar_direccion" class="btn btn-sm btn-primary">Guardar Dirección</button>
                            </div>
                        </div>

                        {{--///// BLOQUE NUEVO CLIENTE /////--}}
                        <div id="bloque_nuevo" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <label class="mt-2">Nombre Cliente *</label>
                            <input type="text" name="nuevo_nombre" class="form-control" maxlength="100" placeholder="Nombre completo del Cliente">

                            <label class="mt-2">Código Cliente *</label>
                            <input type="text" name="nuevo_codigo" class="form-control" maxlength="20" placeholder="Código único para el Cliente">

                            <label>Nombre de Dirección *</label> 
                            <input type="text" name="nuevo_nombre_direccion" class="form-control" maxlength="100" placeholder="Ejemplo: Principal o Sucursal Norte">

                            <label class="mt-2">Dirección *</label>
                            <input type="text" name="nuevo_direccion" class="form-control" maxlength="255" placeholder="Calle, número, colonia">

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label>Ciudad *</label>
                                    <input type="text" name="nuevo_ciudad" class="form-control" maxlength="100">
                                </div>
                                <div class="col-md-4">
                                    <label>Estado *</label>
                                    <input type="text" name="nuevo_estado" class="form-control" maxlength="100">
                                </div>
                                <div class="col-md-4">
                                    <label>CP</label>
                                    <input type="text" name="nuevo_cp" class="form-control" maxlength="20">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Celular</label>
                                    <input type="text" name="nuevo_celular" class="form-control" maxlength="20">
                                </div>
                                <div class="col-md-6">
                                    <label>¿Quién recibe?</label>
                                    <input type="text" name="nuevo_nombre_recibe" class="form-control" maxlength="100">
                                </div>
                            </div>

                            <label class="mt-2">URL Mapa</label>
                            <input type="text" name="nuevo_url_mapa" class="form-control" maxlength="255" placeholder="https://maps...">

                            <label class="mt-2">Instrucciones</label>
                            <textarea name="nuevo_instrucciones" class="form-control" rows="2" maxlength="500" placeholder="Instrucciones de entrega"></textarea>

                            {{-- Campo oculto para evitar error SQL --}}
                            <input type="hidden" name="nombre_direccion" value="Principal">
                        </div>
                    </dd>
        
                    {{-- /////// FIN BLOQUE CLIENTE ///////// --}}
                    
                    {{-- ARCHIVO SOLO PARA PEDIDO/COTIZACIÓN Y REQUISICIÓN --}}
                    @if ($user->role_id == 1 || in_array($user->department_id,[4,5,7]) )
                        <dt rel='archivo'><label rel="archivo" F="Archivo Factura" C="Archivo Cotizacion" R="Archivo Requisición">Archivo</label></dt>
                        <dd rel='archivo'><input type="file" name="archivo" class="form-control" /></dd>
                    @endif

                    <dt><label>Nota</label></dt>
                    <dd><textarea name="nota" class="form-control" maxlength="520"></textarea></dd>

                    <dt></dt>
                    <dd><input type="submit" name="sb" value="Guardar"><span id="preGuardar">Indique todos los datos obligatorios * para guardar</span></dd>
                </dl>
            </fieldset>

            <blockquote class="Monitor"></blockquote>
        </form>
    </section>
</main>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){

    //SELECCIÓN DE TIPO DE PEDIDO
    $(".Eleccion button").click(function(e){
        e.preventDefault();
        $(".Eleccion button").removeClass("activo");
        $(this).addClass("activo");
        let r = $(this).attr("rel");
        ShowTiposet(r);
    });

    $("#FNuevo").ajaxForm({
        dataType:"json",
        error:function(err){ alert(err.statusText); },
        success:function(json){
            if(json.status==0){ alert(json.errors); }
            else{ window.location.href = json.goto; }
        }
    });

    //ACTIVACIÓN DE BOTÓN GUARDAR
    const codeInput = document.querySelector(".Tiposet input[name='code']");
    if(codeInput) codeInput.addEventListener("change", UnlockContinuar);
    const notaInput = document.querySelector(".Tiposet textarea[name='nota']");
    if(notaInput) notaInput.addEventListener("change", UnlockContinuar);

    //ESCOGER TIPO DE CLIENTE
    $("#tipo_cliente").on("change", function(){
        $(".bloque-cliente").hide();
        let tipo = $(this).val();
        if(tipo){ $("#bloque_" + tipo).slideDown(200); }
    });

    $("#btn_nueva_direccion").on("click", function(){
        $("#form_nueva_direccion").slideToggle(200);
    });

    $(".Tiposet.or").hide();
});


//TIPO DE FORMULARIO DEPENDIENDO DEL TIPO DE CLIENTE
function ShowTiposet(r){   

    $(".Tiposet.or").show();
    $("#FNuevo [name='origin']").val(r);
    $("#FNuevo [name='sb']").hide();
    $(".Tiposet label[rel='code']").text($(".Tiposet label[rel='code']").attr(r));
    $(".Tiposet label[rel='archivo']").text($(".Tiposet label[rel='archivo']").attr(r));

    //OCULTAR EL ANTERIOR CAMPO DE CÓDIGO CLIENTE ACTUALMENTE EL CAMPO DE ESCOGER EL TIPO DE CLIENTE
    if(r=="F" || r=="C"){
        $("#dt_cliente, #dd_cliente").show();
        $("dt[rel='archivo'], dd[rel='archivo']").toggle(r=="C");
    }
    else if(r=="R"){
        $("#dt_cliente, #dd_cliente").hide(); 
        $("dt[rel='archivo'], dd[rel='archivo']").show();
    }
}


//VALIDAR DATOS PARA ACTIVAR BOTÓN GUARDAR
function UnlockContinuar(){
    let cd = $(".Tiposet").find("[name='code']").val();
    let cli = $(".Tiposet").find("[name='client']").val();
    let ori = $("[name='origin']").val();
    let tipoCliente = $("#tipo_cliente").val();

    var mostrar = true;
    var errMsg = "";

    if(cd.length < 4){ 
        mostrar = false;
        errMsg += "El folio/número debe contener mínimo 4 caracteres. ";
    }

    if(ori != "R" && tipoCliente === "general" && (!cli || cli.length < 3)){
        mostrar = false;
        errMsg += "El código de cliente debe contener mínimo 3 caracteres. ";
    }

    if(cd.length > 2){
        cd = cd.toLowerCase();
        let ini = cd.substr(0,2);            
        if(ori == "F" && ini != "a0" && ini != "bb"){
            mostrar = false;
            errMsg += "El folio de la factura debe iniciar con A0 o BB. ";
        }
    }

    if(mostrar){
        $("#FNuevo [name='sb']").show();
        $("#preGuardar").hide();
        $(".Monitor").text("");
    } else {
        $("#FNuevo [name='sb']").hide();
        $("#preGuardar").show();
        $(".Monitor").text(errMsg);
    }

}


</script>

@endpush
