<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Nombre de la dirección</label>
    <div class="col-sm-7">
        <input class="form-control" name="__PREFIX__[nombre_direccion]" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Tipo de residencia</label>
    <div class="col-sm-3">
        <select class="form-control" name="__PREFIX__[tipo_residencia]" required>
            <option value="">Seleccione</option>
            <option value="residencial">Residencial</option>
            <option value="obra">Obra</option>
            <option value="taller">Taller</option>
            <option value="industria">Industria</option>
        </select>
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Dirección</label>
    <div class="col-sm-7">
        <input class="form-control" name="__PREFIX__[direccion]" type="text">
    </div>
</div>

<div class="row mt-2">

    <label class="col-sm-2 col-form-label">C.P.</label>
    <div class="col-sm-3">
        <input class="form-control cp-input" name="__PREFIX__[codigo_postal]" type="text" inputmode="numeric" pattern="[0-9]{5}" maxlength="5">
    </div>

    <label class="col-sm-1 col-form-label">Estado</label>
    <div class="col-sm-3">
        <input class="form-control estado-input" name="__PREFIX__[estado]" type="text">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Ciudad</label>
    <div class="col-sm-3">
        <input class="form-control ciudad-input" name="__PREFIX__[ciudad]" type="text">
    </div>


    <label class="col-sm-1 col-form-label">Colonia</label>
    <div class="col-sm-3">
        <select class="form-control colonia-input" name="__PREFIX__[colonia]"></select>
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Celular</label>
    <div class="col-sm-3">
        <input class="form-control" name="__PREFIX__[celular]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
    </div>

    <label class="col-sm-1 col-form-label">Teléfono</label>
    <div class="col-sm-3">
        <input class="form-control" name="__PREFIX__[telefono]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">¿Quién recibe?</label>
    <div class="col-sm-7">
        <input class="form-control" name="__PREFIX__[nombre_recibe]">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">URL Mapa</label>
    <div class="col-sm-7">
        <input class="form-control" name="__PREFIX__[url_mapa]">
    </div>
</div>

<div class="row mt-2">
    <label class="col-sm-2 col-form-label">Requerimientos especiales</label>
    <div class="col-sm-7">
        @foreach($requerimientos as $Req)   
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="__PREFIX__[requerimientos][]" value="{{ $Req->id }}">
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
        <textarea class="form-control" name="__PREFIX__[instrucciones]" rows="2"></textarea>
    </div>
</div>
