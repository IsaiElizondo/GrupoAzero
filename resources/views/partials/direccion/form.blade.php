{{-- PARTIAL: FORMULARIO DE DIRECCIÓN --}}
{{-- VARIABLES ESPERADAS:
     $prefix (string)
     $data (array)
     $requerimientos (collection)
--}}

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Nombre de la dirección</label>
    <div class="col-sm-7">
        <input class="form-control" name="{{ $prefix }}[nombre_direccion]" value="{{ $data['nombre_direccion'] ?? '' }}" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Tipo de residencia</label>
    <div class="col-sm-3">
        <select class="form-control"
                name="{{ $prefix }}[tipo_residencia]">
            <option value="">Seleccione</option>
            <option value="residencial" {{ ($data['tipo_residencia'] ?? '')=='residencial'?'selected':'' }}>Residencial</option>
            <option value="obra" {{ ($data['tipo_residencia'] ?? '')=='obra'?'selected':'' }}>Obra</option>
            <option value="taller" {{ ($data['tipo_residencia'] ?? '')=='taller'?'selected':'' }}>Taller</option>
            <option value="industria" {{ ($data['tipo_residencia'] ?? '')=='industria'?'selected':'' }}>Industria</option>
        </select>
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Dirección</label>
    <div class="col-sm-7">
        <input class="form-control" name="{{ $prefix }}[direccion]" value="{{ $data['direccion'] ?? '' }}" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">C.P.</label>
    <div class="col-sm-3">
        <input class="form-control cp-input" name="{{ $prefix }}[codigo_postal]" value="{{ $data['codigo_postal'] ?? '' }}" type="text" inputmode="numeric" pattern="[0-9]{5}" maxlength="5">
    </div>

    
    <label class="col-sm-1 col-form-label">Estado</label>
    <div class="col-sm-3">
        <input class="form-control estado-input" name="{{ $prefix }}[estado]" value="{{ $data['estado'] ?? '' }}" type="text">
    </div>   
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Ciudad</label>
    <div class="col-sm-3">
        <input class="form-control ciudad-input" name="{{ $prefix }}[ciudad]" value="{{ $data['ciudad'] ?? '' }}" type="text">
    </div>

    <label class="col-sm-1 col-form-label">Colonia</label>
    <div class="col-sm-3">
        <select class="form-control colonia-input" name="{{ $prefix }}[colonia]" data-selected="{{ $data['colonia'] ?? '' }}"></select>
    </div> 
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Celular</label>
    <div class="col-sm-3">
        <input class="form-control" name="{{ $prefix }}[celular]" value="{{ $data['celular'] ?? '' }}" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
    </div>

    <label class="col-sm-1 col-form-label">Teléfono</label>
    <div class="col-sm-3">
        <input class="form-control" name="{{ $prefix }}[telefono]" value="{{ $data['telefono'] ?? '' }}" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">¿Quién recibe?</label>
    <div class="col-sm-7">
        <input class="form-control" name="{{ $prefix }}[nombre_recibe]" value="{{ $data['nombre_recibe'] ?? '' }}" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">URL Mapa</label>
    <div class="col-sm-7">
        <input class="form-control" name="{{ $prefix }}[url_mapa]" value="{{ $data['url_mapa'] ?? '' }}" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Requerimientos especiales</label>
    <div class="col-sm-7">
        @foreach($requerimientos as $Req)
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="{{ $prefix }}[requerimientos][]" value="{{ $Req->id }}" {{ in_array($Req->id, $data['requerimientos'] ?? []) ? 'checked' : '' }}>
                    {{ $Req->nombre }}
                    <span class="form-check-sign">
                        <span class="check"></span>
                    </span>
                </label>
            </div>
        @endforeach
    </div>
</div>


<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Instrucciones</label>
    <div class="col-sm-7">
        <textarea class="form-control" name="{{ $prefix }}[instrucciones]" rows="2">{{ $data['instrucciones'] ?? '' }}</textarea>
    </div>
</div>
