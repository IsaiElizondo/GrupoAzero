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
                            <option value="general">Cliente XAXX</option>
                            <option value="existente">Cliente Existente</option>
                            <option value="nuevo">Crear Cliente</option>
                        </select>

                        {{--///// BLOQUE CLIENTE GENERAL ////--}}
                        <div id="bloque_general" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <h5>Cliente General (XAXX)</h5>
                            <label class="mt-2">Código Cliente *</label>
                            <input type="text" name="client_general" class="form-control" maxlength="20" value="XAXX" readonly>
                            <label class="mt-3">Dirección del cliente *</label>
                            <label class="mt-2">Estado de dirección *</label>
                            <select name="estado_direccion_general" id="estado_direccion_general" class="form-control">
                                <option value="pendiente">Pendiente</option>
                                <option value="completa">Dirección completa</option>
                                <option value="recoge">Cliente recoge</option>
                            </select>

                            <div id="form_general_direccion" class="direccion-scope" style="display:none; border:1px solid #ddd; padding:10px; margin-top:8px; border-radius:6px;">

                                <h5>Dirección completa</h5>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Nombre de la dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="general_nombre_direccion" placeholder="Nombre para la direccion">
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Tipo de residencia * </label>
                                    <div class="col-sm-3">
                                        <select name="general_tipo_residencia" class="form-control">
                                            <option value=""> Seleccione </option>
                                            <option value="residencial"> Residencial </option>
                                            <option value="obra"> Obra </option>
                                            <option value="taller"> Taller </option>
                                            <option value="industria"> Industria </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="general_direccion" placeholder="Calle, número">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">C.P.</label>
                                    <div class="col-sm-3">
                                        <input class="form-control cp-input is-invalid" type="text" name="general_codigo_postal" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" placeholder="Código Postal">
                                    </div>
                                
                                    <label class="col-sm-1 col-form-label">Estado</label>
                                    <div class="col-sm-3">
                                        <input class="form-control estado-input" type="text" name="general_estado" placeholder="Estado">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Ciudad</label>
                                    <div class="col-sm-3">
                                        <input class="form-control ciudad-input" type="text" name="general_ciudad" placeholder="Ciudad">
                                    </div>

                                    <label class="col-sm-1 col-form-label">Colonia</label>
                                    <div class="col-sm-3">
                                        <select class="form-control colonia-input" name="general_colonia" id="colonia" placeholder="Colonia"></select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Celular</label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="general_celular" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="Celular">
                                    </div>

                                    <label class="col-sm-1 col-form-label">Teléf</label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="general_telefono" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="Teléfono">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">¿Quién recibe?</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="general_nombre_recibe" placeholder="Nombre del quien recibe">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">URL Mapa</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="general_url_mapa" placeholder="https://maps...">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Requerimientos especiales </label> 
                                    <div class="col-sm-7">
                                        @foreach($RequerimientosEspeciales as $Req)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="general_requerimientos[]" value="{{ $Req->id }}">
                                                    {{ $Req->nombre }}
                                                    <span class="form-check-sign"><span class="check"></span></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Instrucciones</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" rows="2" name="general_instrucciones"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--///// BLOQUE CLIENTE EXISTENTE /////--}}
                        <div id="bloque_existente" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <label class="mt-2">Buscar Cliente *</label>
                            <input type="text" id="buscar_cliente" class="form-control" placeholder="Buscar por nombre o código">
                            <input type="hidden" name="cliente_id" id="cliente_id">
                            <input type="hidden" name="client" id="cliente_codigo">
                            <div id="lista_clientes"></div>
                            <label class="mt-2">Dirección *</label>
                            <select name="mode_direccion_existente" id="modo_direccion_existente" class="form-control">
                                <option value="">--Selecciona una opcion--</option>
                                <option value="escoger">Escoger dirección existente</option>
                                <option value="nueva">Agregar nueva dirección</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="recoge">Cliente recoge</option>
                            </select>

                            {{--///// ESCOGER DIRECCIÓN EXISTENTE /////--}}
                            <div id="bloque_escoger_direccion" style="display:none; margin-top:10px">
                                <select name="cliente_direccion_id" id="cliente_direccion_id" class="form-control">
                                    <option value="">--Selecciona una dirección--</option>
                                </select>
                            </div>

                            {{--////// AGREGAR NUEVA DIRECCIÓN //////--}}
                            <div id="form_nueva_direccion" class="direccion-scope" style="display:none; border:1px solid #ddd; padding:10px; margin-top:8px; border-radius:6px;">
                                <h5>Nueva Dirección</h5>
                                <input type="hidden" name="cliente_id" id="cliente_id_ajax">
                                
                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Nombre de la dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nombre_direccion" id="nombre_direccion" placeholder="Nombre para la direccion">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Tipo de residencia * </label>
                                    <div class="col-sm-3">
                                        <select name="tipo_residencia" class="form-control">
                                            <option value=""> Seleccione </option>
                                            <option value="residencial"> Residencial </option>
                                            <option value="obra"> Obra </option>
                                            <option value="taller"> Taller </option>
                                            <option value="industria"> Industria </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="direccion" id="direccion" placeholder="Calle, número">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> C.P. </label>
                                    <div class="col-sm-3">
                                        <input class="form-control cp-input is-invalid" type="text" name="codigo_postal" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" id="codigo_postal" placeholder="Código Postal">
                                    </div>

                                    <label class="col-sm-1 col-form-label">Estado</label>
                                    <div class="col-sm-3">
                                        <input class="form-control estado-input" type="text" name="estado" id="estado" placeholder="Estado">
                                    </div>
                                    
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Ciudad</label>
                                    <div class="col-sm-3">
                                        <input class="form-control ciudad-input" type="text" name="ciudad" id="ciudad" placeholder="Ciudad">
                                    </div>

                                    <label class="col-sm-1 col-form-label"> Colonia </label>
                                    <div class="col-sm-3">
                                        <select class="form-control colonia-input" name="colonia" id="colonia" placeholder="Colonia"></select>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Celular </label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="celular" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" id="celular" placeholder="Celular">
                                    </div>
                                    <label class="col-sm-1 col-form-label"> Teléf </label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="telefono" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" id="telefono" placeholder="Teléfono">
                                    </div>
                                </div>
                                

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">¿Quién recibe?</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nombre_recibe" id="nombre_recibe" placeholder="Nombre del quien recibe">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">URL Mapa</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="url_mapa" id="url_mapa" placeholder="https://maps..">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Requerimientos especiales</label>
                                    <div class="col-sm-7">
                                        @foreach($RequerimientosEspeciales as $Req)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="requerimientos[]" value="{{ $Req->id }}">
                                                    {{ $Req->nombre }}
                                                    <span class="form-check-sign"><span class="check"></span></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Instrucciones</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" rows="2" name="instrucciones" id="instrucciones"></textarea>
                                    </div>
                                </div>
                                <button type="button" id="guardar_direccion" class="btn btn-sm btn-primary mt-2">Guardar Dirección</button>
                            </div>
                        </div>

                        {{--///// BLOQUE NUEVO CLIENTE /////--}}
                        <div id="bloque_nuevo" class="bloque-cliente" style="display:none; margin-top:10px;">
                            <label class="mt-2">Nombre Cliente *</label>
                            <input type="text" name="nuevo_nombre" class="form-control" maxlength="100">
                            <label class="mt-2">Código Cliente *</label>
                            <input type="text" name="nuevo_codigo" class="form-control" maxlength="20">
                            <label class="mt-3">Dirección del cliente *</label>
                            <select name="estado_direccion" id="estado_direccion_nuevo" class="form-control">
                                <option value="">--Selecciona una opción--</option>
                                <option value="completa">Agregar dirección</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="recoge">Cliente recoge</option>
                            </select>

                            <div id="bloque_direccion_detalle" class="direccion-scope" style="display:none; border:1px solid #ddd; padding:10px; margin-top:8px; border-radius:6px;">
                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Nombre de Dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nuevo_nombre_direccion" placeholder="Nombre para la direccion">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Tipo de residencia * </label>
                                    <div class="col-sm-3">
                                        <select name="nuevo_tipo_residencia" class="form-control">
                                            <option value=""> Seleccione </option>
                                            <option value="residencial"> Residencial </option>
                                            <option value="obra"> Obra </option>
                                            <option value="taller"> Taller </option>
                                            <option value="industria"> Industria </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Dirección *</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nuevo_direccion" placeholder="Calle, número">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> C.P. </label>
                                    <div class="col-sm-3">
                                        <input class="form-control cp-input is-invalid" type="text" name="nuevo_codigo_postal" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" placeholder="Código Postal">
                                    </div>

                                    <label class="col-sm-1 col-form-label"> Estado </label>
                                    <div class="col-sm-3">
                                        <input class="form-control estado-input" type="text" name="nuevo_estado" placeholder="Estado">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Ciudad </label>
                                    <div class="col-sm-3">
                                        <input class="form-control ciudad-input" type="text" name="nuevo_ciudad" placeholder="Ciudad">
                                    </div>

                                    <label class="col-sm-1 col-form-label"> Colonia </label>
                                    <div class="col-sm-3">
                                        <select class="form-control colonia-input" name="nuevo_colonia" id="colonia" placeholder="Colonia"></select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Celular </label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="nuevo_celular" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="Celular">
                                    </div>

                                    <label class="col-sm-1 col-form-label"> Teléf </label>
                                    <div class="col-sm-3">
                                        <input class="form-control is-invalid" type="text" name="nuevo_telefono" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="Teléfono">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">¿Quién recibe?</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nuevo_nombre_recibe" placeholder="Nombre del quien recibe">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">URL Mapa</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="nuevo_url_mapa" placeholder="https://maps..">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label"> Requerimientos especiales </label> 
                                    <div class="col-sm-7">
                                        @foreach($RequerimientosEspeciales as $Req)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="nuevo_requerimientos[]" value="{{ $Req->id }}">
                                                    {{ $Req->nombre }}
                                                    <span class="form-check-sign"><span class="check"></span></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <label class="col-sm-2 col-form-label">Instrucciones</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" rows="2" name="nuevo_instrucciones"></textarea>
                                    </div>
                                </div>
                            </div>
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
    <script src="{{ asset('js/sepomex.js') }}"></script>
    <script type="text/javascript">
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
            error:function(err){ alert(err.statusText); },
            success:function(json){
                if(json.status==0){ alert(json.errors); }
                else{ window.location.href = json.goto; }
            }
        });
    
        const codeInput = document.querySelector(".Tiposet input[name='code']");
        if(codeInput) codeInput.addEventListener("change", UnlockContinuar);
        const notaInput = document.querySelector(".Tiposet textarea[name='nota']");
        if(notaInput) notaInput.addEventListener("change", UnlockContinuar);

        $("#tipo_cliente").on("change", function(){
            $(".bloque-cliente").hide();
            let tipo = $(this).val();
            if(tipo){ $("#bloque_" + tipo).slideDown(200); }
        });

        $("#estado_direccion_general").on("change", function(){
            let v = $(this).val();
            if(v == "completa"){
                $("#form_general_direccion").slideDown(200);
            }else{
                $("#form_general_direccion").slideUp(200);
            }
        });

        $("#estado_direccion_nuevo").on("change", function(){
            let v = $(this).val();
            if(v == "completa"){
                $("#bloque_direccion_detalle").slideDown(200);
            }else{
                $("#bloque_direccion_detalle").slideUp(200);
            }
        });

        $('#buscar_cliente').on('keyup', function() {
            let q = $(this).val().trim();
            if(q.length < 2){
                $('#lista_clientes').html('');
                return;
            }

            $.get("{{ url('clientes/buscar') }}", { q: q }, function(data){
                $('#lista_clientes').html(data);
            }).fail(function(){
                alert("Error al buscar clientes");
            });
        });

        $(document).on('click', '.seleccionar-cliente', function(){
            let id = $(this).data('id');
            let nombre = $(this).data('nombre');
            
            $('#cliente_id').val(id);
            $('#buscar_cliente').val(nombre);
            $('#cliente_codigo').val($(this).data('codigo'));
            $('#lista_clientes').html(""); 
            
            $.get("{{ url('clientes') }}/" + id + "/direcciones", function(resp){
                let opciones = '<option value="">--Selecciona una dirección--</option>';
                resp.forEach(function(dir){
                    opciones += `<option value="${dir.id}">${dir.nombre_direccion}</option>`;
                });
                $('#cliente_direccion_id').html(opciones);
            }).fail(function(){
                alert("Error cargando direcciones del cliente");
            });
        });

        $('#modo_direccion_existente').on('change', function() {
            let modo = $(this).val();
            $('#bloque_escoger_direccion').hide();
            $('#form_nueva_direccion').hide();

            if (modo === 'escoger') {
                $('#bloque_escoger_direccion').slideDown(200);
            }
            if (modo === 'nueva') {
                $('#form_nueva_direccion').slideDown(200);
            }
        });

        $(document).on('click', '.seleccionar-cliente', function() {
            let id = $(this).data('id');
            $('#cliente_id_ajax').val(id);
        });

        $('#guardar_direccion').on('click', function(){

            let cont = $('#form_nueva_direccion');

            cont.find('.is-invalid').removeClass('is-invalid');
            cont.find('.invalid-feedback').remove();

            let inputCelular      = cont.find('input[name="celular"]');
            let inputTelefono     = cont.find('input[name="telefono"]');
            let inputCodigoPostal = cont.find('input[name="codigo_postal"]');

            let Celular      = inputCelular.val();
            let Telefono     = inputTelefono.val();
            let CodigoPostal = inputCodigoPostal.val();

            if(Celular && !/^\d{10}$/.test(Celular)){
                inputCelular.addClass('is-invalid')
                    .after('<div class="invalid-feedback">El celular debe tener 10 dígitos</div>');
                inputCelular.focus();
                return;
            }

            if(Telefono && !/^\d{10}$/.test(Telefono)){
                inputTelefono.addClass('is-invalid')
                    .after('<div class="invalid-feedback">El teléfono debe tener 10 dígitos</div>');
                inputTelefono.focus();
                return;
            }

            if(CodigoPostal && !/^\d{5}$/.test(CodigoPostal)){
                inputCodigoPostal.addClass('is-invalid')
                    .after('<div class="invalid-feedback">El código postal debe tener 5 dígitos</div>');
                inputCodigoPostal.focus();
                return;
            }

            let datos = {
                cliente_id: $('#cliente_id_ajax').val(),
                nombre_direccion: $('#nombre_direccion').val(),
                tipo_residencia: $('select[name="tipo_residencia"]').val(),
                direccion: $('#direccion').val(),
                colonia: $('#colonia').val(),
                ciudad: $('#ciudad').val(),
                estado: $('#estado').val(),
                codigo_postal: $('#codigo_postal').val(),
                celular: $('#celular').val(),
                telefono: $('#telefono').val(),
                nombre_recibe: $('#nombre_recibe').val(),
                url_mapa: $('#url_mapa').val(),
                instrucciones: $('#instrucciones').val(),
                _token: "{{ csrf_token() }}"
            };

            $.post("{{ url('clientes/storeDireccion') }}", datos, function(resp){
                if(resp.status == 1){
                    alert("Dirección guardada correctamente");
                    $('#cliente_direccion_id').append(
                        `<option value="${resp.direccion.id}">${resp.direccion.nombre_direccion}</option>`
                    );
                    $('#cliente_direccion_id').val(resp.direccion.id);
                    $('#form_nueva_direccion').slideUp();
                }else{
                    alert("Error al guardar dirección");
                }
            }).fail(function(){
                alert("Error en el servidor");
            });
        });

        $(".Tiposet.or").hide();
    });


    function ShowTiposet(r){   

        $(".Tiposet.or").show();
        $("#FNuevo [name='origin']").val(r);
        $("#FNuevo [name='sb']").hide();
        $(".Tiposet label[rel='code']").text($(".Tiposet label[rel='code']").attr(r));
        $(".Tiposet label[rel='archivo']").text($(".Tiposet label[rel='archivo']").attr(r));
        if(r=="F" || r=="C"){
            $("#dt_cliente, #dd_cliente").show();
            $("dt[rel='archivo'], dd[rel='archivo']").toggle(r=="C");
        }
        else if(r=="R"){
            $("#dt_cliente, #dd_cliente").hide(); 
            $("dt[rel='archivo'], dd[rel='archivo']").show();
        }
    }


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

        if(ori != "R" && tipoCliente == "general" && (!cli || cli.length < 3)){
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
