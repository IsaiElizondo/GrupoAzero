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
            
                @if ($user->role_id ==1 || in_array($user->department_id,[3,4,8]) )
                <button class="Tipo" rel="F">Factura</button>
                @endif


                @if ($user->role_id ==1 || in_array($user->department_id,[3,4]) )
                <button class="Tipo" rel="C" title="Cotización">Pedido</button>
                @endif



                @if ($user->role_id ==1 || in_array($user->department_id,[5,7]) )
                <button class="Tipo" rel="R">Requisición Stock</button>
                @endif
            
            </div>

    </fieldset>

        <input type="hidden" name="origin" value=""/>
        <div>&nbsp;</div>

        <fieldset class="Tiposet or">
            <dl>
            <dt><label rel="code" F="Folio Factura *" C="Num Cotizacion *" R="Num Requisici[on *">Folio/Codigo</label></dt> 
            <dd><input type="text" name="code" class="form-control"  maxlength="24" autocomplete="off" placeholder="Min 4 caracteres" /></dd>
            
            {{--BLOQUE CLIENTE --}}
            <dt><label> Tipo de Cliente * </label></dt>
            <dd>
                <select name="tipo_cliente" id="tipo_cliente" class="form-control">
                    <option value="">--Selecciona una opción --</option>
                    <option value="general"> Cliente General </option>
                    <option value="existente"> Cliente Existente </option>
                    <option value="nuevo"> Crear Cliente </option>
                </select>
            {{-- CLIENTE GENERAL--}}
            <div id="bloque_general" class="bloque-cliente" style="display:none;">
                <dt><label> Código Cliente * </label></dt>
                <dd><input type="text" name="client" class="form-control" maxlength="45" placeholder="Código de Cliente General"></dd>
                
                <dt><label> Dirección * </label></dt>
                <dd><input type="text" name="direccion_general" class="form-control" maxlength="45" placeholder="Dirección del Cliente General"></dd>
            </div>

            {{-- CLIENTE EXISTENTE --}}
            <div id="bloque_existente" class="bloque-cliente" style="display:none;">
                <dt><label> Buscar Cliente * </label></dt>
                <dd>
                    <input type="text" id="buscar_cliente" class="form-control" placeholder="Buscar por Nombre o Código">
                    <input type="hidden" name="cliente_id" id="cliente_id">
                </dd>

                <dt><label> Dirección * </label></dt>
                <dd>
                    <select name="cliente_direccion_id" id="cliente_direccion_id" class="form-control">
                        <option value="">-- Selecciona una dirección --</option>
                    </select>
                    <button type="button" id="btn_nueva_direccion" class="btn btn-sm btn-secondary mt-1"> + Agregar NUeva Dirección </button>
                </dd>

                <div id="form_nueva_direccion" style="display:none; border:1px solid #ddd; padding:10px; margin-top:8px; border-radius:6px;">
                    <h5 style="margine:0 0 10px;"> Nueva Dirección </h5>
                    <input type="text" id="nombre_direccion" class="form-control mb-2" placeholder="Nombre de la dirección">
                    <input type="text" id="direccion" class="form-control mb" placeholder="Calle, número, colonia">
                    <div class="row">
                        <div class="col"><input type="text" id="ciudad" class="form-control mb-2" placeholder="Ciudad"></div>
                        <div class="col"><input type="text" id="estado" class="form-control mb-2" placeholder="Estado"></div>
                        <div class="col"><input type="text" id="codigo_postal" class="form-control mb-2" placeholder="CP"></div>
                    </div>
                    <div class="row">
                        <div class="col"><input type="text" id="celular" class="form-control mb-2" placeholder="Celular"></div>
                        <div class="col"><input type="text" id="nombre_recibe" class="form-control mb-2" placeholder="¿Quién recibe?"></div>
                    </div>
                    <input type="text" id="url_mapa" class="form-control mb-2" placeholder="https://maps...">
                    <textarea id="instrucciones" class="form-control mb-2" rows="2" placeholder="Instrucciones de entrega"></textarea>
                    
                    <button type="button" id="guardar_direccion" class="btn btn-sm btn-primary"> Guardar Dirección </button>
                </div>
            </div>

            {{-- CREAR NUEVO CLIENTE --}}
            <div id="bloque_nuevo" class="bloque-cliente" style="display:none;">
                <dt><label> Nombre Cliente * </label></dt>
                <dd><input type="text" name="nuevo_nombre" class="form-control" maxlength="100" placeholder="Nombre completo del Cliente"></dd>

                <dt><label> Código Cliente * </label></dt>
                <dd><input type="text" name="nuevo_nombre" class="form-control" maxlength="20" placeholder="Código unico para el Cliente"></dd>

                <dt><label> Dirección * </label></dt>
                <dd><input type="text" name="nuevo_direccion" class="form-control" maxlength="255" placeholder="Calle, número, colonia"></dd>

                <div class="row">
                    <div class="col">
                        <label> Ciudad * </label>
                        <input type="text" name="nuevo_ciudad" class="form-control" maxlength="100">
                    </div>

                    <div class="col">
                        <label> Estado * </label>
                        <input type="text" name="nuevo_estado" class="form-control" maxlength="100">
                    </div>

                    <div class="col">
                        <label> CP </label>
                        <input type="text" name="nuevo_cp" class="form-control" maxlength="20">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label> Celular </label>
                        <input type="text" name="nuevo_celular" class="form-control" maxlength="20">
                    </div>

                    <div class="col">
                        <label> ¿Quién recibe? </label>
                        <input type="text" name="nuevo_nombre_recibe" class="form-control" maxlength="100">
                    </div>
                </div>

                <div class="mt-2">
                    <label> URL Mapa </label>
                    <input type="text" name="nuevo_url_mapa" class="form-control" maxlength="255" placeholder="https://maps...">
                </div>

                <div class="mt-2">
                    <label> Instrucciones </label>
                    <textarea name="nuevo_instrucciones" class="form-control" row="2" maxlength="500" placeholder="Instrucciones de entrega"></textarea>
                </div>
            </div>
            {{--FIN BLOQUE CLIENTE--}}            

            @if ($user->role_id == 1 || in_array($user->department_id,[4,5,7]) )
            
            <dt rel='archivo'><label rel="archivo" F="Archivo Factura" C="Archivo Cotizacion" R="Archivo Requisición">Archivo</label> </dt> 
            <dd rel='archivo'><input type="file" name="archivo"  class="form-control" /></dd>
            
            @endif

            <dt><label>Note</label></dt> 
            <dd> <textarea  name="nota" class="form-control" maxlength="520" ></textarea></dd>

            <dt></dt> 
            <dd><input type="submit" name="sb" value="Guardar"><span id="preGuardar">Indique todos los datos obligatorios * para guardar</span></dd>
        </dl>
        </fieldset>

        <blockquote class="Monitor"></blockquote>

    
    </form>
</main>

    @endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js')  }}" ></script>
<script type="text/javascript" >

$(document).ready(function(){

$(".Eleccion button").click(function(e){
    e.preventDefault();

    $(".Eleccion button").removeClass("activo");
    $(this).addClass("activo");
    let r = $(this).attr("rel");

    ShowTiposet(r);
});





    $("#FNuevo").ajaxForm({
        dataType:"json",
        error:function(err){
            alert(err.statuText);
        },
        success:function(json){
            if(json.status==0){
                alert(json.errors);
            }else{
                window.location.href = json.goto;
            }
        }
    });


    const codeInput = document.querySelector(".Tiposet input[name='code']");
    codeInput.addEventListener("change",UnlockContinuar);

    const clientInput = document.querySelector(".Tiposet input[name='client']");
    clientInput.addEventListener("change",UnlockContinuar);

    const notaInput = document.querySelector(".Tiposet textarea[name='nota']");
    notaInput.addEventListener("change",UnlockContinuar);

/*
    $(".Tiposet input[name='code']").on("change",function(e){
    console.log("change");
    setTimeout(UnlockContinuar,100);
    });

    $(".Tiposet input[name='client']").on("change",function(e){
    setTimeout(UnlockContinuar,100);
    });
*/


    $(".Tiposet.or").hide();

    // TIPO DE CLIENTE
    $('#tipo_cliente').on('change', function(){
        let tipo = $(this).val();
        $('.bloque-cliente').hide();

        if(tipo == 'general'){
            $('#bloque_general').show();
        }else if(tipo == 'existente'){
            $('#bloque_existente').show();
        }else if(tipo == 'nuevo'){
            $('#bloque_nuevo').show();
        }
    });

    //BUSCAR CLIENTE

    let timerBusqueda = null;
    $('#buscar_cliente').on('keyup', function(){
        clearTimeout(timerBusqueda);
        let q = $(this).val().trim();
        if(q-length > 2) return;

        timerBusqueda = setTimeout(function(){
            $.ajax({
                url: "{{url('clientes/buscar') }}",
                data: {q: q},
                success: function(resp){
                    mostrarClientes(resp);
                },
                error: function(){
                    console.error('Error buscando cliente');
                }
            });
        }, 400);
    });

    function mostrarClientes(clientes){
        $('. sugerecias-clientes').remove();
        let lista = $('<ul class="sugerencias-clientes list-group mt-1"></ul>');
        if(clientes.length == 0){
            lista.append('<li class="list-group-item"> Sin Resultados</li>');
        }else{
            clientes.forEach(c => {
                let li = $('<li class="list-group-item list-group-item-action" style="cursor.pointer;"></li>');
                li.text(c.nombre + ' ('+ c.codigo +')');
                li.on('click', function(){
                    $('#buscar_cliente').val(c.nombre);
                    $('#cliente_id').val(c.id);
                    lista.remove();
                    cargarDirecciones(c.id);
                });
                lista.append(li);
            });
        }
        $('buscar_cliente').after(lista);
    }

    function cargarDirecciones(clienteId){
        $('#cliente_direccion_id').empty().append('<option value=""> Cargando... </option>');
        $.ajax({
            url: "{{ url('clientes') }}/" + clienteId + "/direcciones",
            success: function(resp){
                $('cliente_direccion_id').empty().append('<option value="">-- Selecciona una dirección --</option>');
                resp.forEach(dir => {
                    $('#cliente_direccion_id').append('<option value="${dir.id}">${dir.nombre_direccion} - ${dir.direccion}</option>');
                });
            },
            error: function(){
                alert('Error al cargar direcciones.');
            }
        });
    }

    //MOSTRAR FORMULARIO DIRECCIÓN NUEVA
    $('#btn_nueva_direccion').on('click', function(){
        $('#form_nueva_direccion').slideToggle();
    });

    //GUARDAR NUEVA DIRECCIÓN
    $('#guardar_direccion').on('click', function(){
        let clienteId = $('#cliente_id').val();
        if(!clienteId){
            alert('Primero selecciona un cliente');
            return;
        }
        
        let data= {
            cliente_id = clienteId,
            nombre_direccion: $('#nombre_direccion').val(),
            direccion: $('#direccion').val(),
            ciudad: $('#ciudad').val(),
            estado: $('#estado').val(),
            codigo_postal: $('#codigo_postal').val(),
            celular: $('#celular').val(),
            nombre_recibe: $('#nombre_recibe').val(),
            url_mapa: $('#instrucciones').val(),
            _token: '{{csrf_token()}}'
        };

        $.ajax({
            url: "{{ url('clientes/storeDireccion') }}",
            method: 'POST',
            data: data,
            success: function(resp){
                if(resp.status == 1){
                    alert('Direccion guardada correctamente');
                    $('#form_nueva_direccion').sideUp();
                    limpiarFormularioDireccion();
                    cargarDirecciones(clienteId);
                }else{
                    alert('Error al guardar direccion.');
                }
            },
        });

    }):

    function limpiarFormularioDireccion(){
        $('#form_nueva_direccion input, #form_nueva_direccion textarea').val('');
    }

});




function ShowTiposet(r){   

    $(".Tiposet.or").show();
    $("#FNuevo [name='origin']").val(r);
    $("#FNuevo [name='sb']").hide();
    $(".Tiposet label[rel='code']").text($(".Tiposet label[rel='code']").attr(r));
    $(".Tiposet label[rel='archivo']").text($(".Tiposet label[rel='archivo']").attr(r));

    if(r=="F"){
        $("dt[rel='archivo']").hide();
        $("dd[rel='archivo']").hide();
    }else{
        $("dt[rel='archivo']").show();
        $("dd[rel='archivo']").show();       
    }

    if(r=="R"){
        $("dt[rel='client']").hide();
        $("dd[rel='client']").hide();       
    }else{
        $("dt[rel='client']").show();
        $("dd[rel='client']").show();        
    }

}

function UnlockContinuar(){
    let cd = $(".Tiposet").find("[name='code']").val();
    let ar = $(".Tiposet").find("[name='archivo']").val();
    let cli = $(".Tiposet").find("[name='client']").val();
    let ori = $("[name='origin']").val();

    var mostrar = true;
    //var formato = true;
    var errMsg="";
    //Code Len
    if(cd.length < 4){ 
        mostrar=false;
        errMsg += "El folio/número debe contener mínimo 4 caracteres. ";
    }
    //Cliente
    if(ori!="R" && cli.length < 3){
    mostrar=false;
    errMsg += "El número de cliente debe contener mínimo 3 caracteres. ";
    }

    //Formato Factura
        if(cd.length>2){
            cd = cd.toLowerCase();
            let ini = cd.substr(0,2);            
            console.log(ini);
            if(ori == "F" && ini!="a0" && ini!="bb"){
                
                mostrar = false;
               // formato = false;
                errMsg += "El folio de la factura debe iniciar con A0 o BB. ";
            }
        }

    if(mostrar){
    $("#FNuevo [name='sb']").show();
    $("#preGuardar").hide();
    $(".Monitor").text("");
    }
    else{
        $("#FNuevo [name='sb']").hide();
        $("#preGuardar").show();
        $(".Monitor").text(errMsg);
    }
    
}


</script>

@endpush
