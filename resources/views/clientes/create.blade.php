@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if($errors->any())
                        <div style="color: red; margin-bottom: 12px">
                            <strong>Corrige los siguientes errores:</strong>
                            <ul style="margin: 6px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{route('clientes.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title"> Crear cliente </h4>
                                        <p class="card-category"> Información para crear Cliente</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{url()->previous()}}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">arrow_back</span>
                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="nombre" type="text" value="{{old('nombre')}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Código del Cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="codigo_cliente" type="text" value="{{old('codigo_cliente')}}">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 style="margin: 6px 0 14px;">Direcciones adicionales</h5>
                                        <p class="text-muted" style="margin-top:-6px">Agrega una o más direcciones.</p>
                                        <button type="button" id="btn-add-direccion" class="btn btn-sm btn-secondary">+ Añadir dirección</button>
                                    </div>
                                </div>

                                <div id="direcciones-wrapper" class="mt-3">
                                    @php $oldDirs = old('direcciones', []); @endphp
                                    @foreach($oldDirs as $i => $d)
                                        <div class="card p-3 mb-3 direccion-item" data-index="{{ $i }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Dirección #{{ $i + 1 }}</strong>
                                                <button type="button" class="btn btn-xs btn-danger btn-remove-direccion">Eliminar</button>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Tipo de residencia</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="direcciones[{{ $i }}][tipo_residencia]" required>
                                                        <option value="">Seleccione</option>
                                                        <option value="residencial" {{ ($d['tipo_residencia'] ?? '')=='residencial'?'selected':'' }}>Residencial</option>
                                                        <option value="obra" {{ ($d['tipo_residencia'] ?? '')=='obra'?'selected':'' }}>Obra</option>
                                                        <option value="taller" {{ ($d['tipo_residencia'] ?? '')=='taller'?'selected':'' }}>Taller</option>
                                                        <option value="industria" {{ ($d['tipo_residencia'] ?? '')=='industria'?'selected':'' }}>Industria</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Nombre de la dirección</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" name="direcciones[{{ $i }}][nombre_direccion]" type="text" value="{{ $d['nombre_direccion'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Dirección</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][direccion]" type="text" value="{{ $d['direccion'] ?? '' }}">
                                                </div>

                                                <label class="col-sm-1 col-form-label text-right">Colonia</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][colonia]" type="text" value="{{ $d['colonia'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Ciudad</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][ciudad]" type="text" value="{{ $d['ciudad'] ?? '' }}">
                                                </div>

                                                <label class="col-sm-1 col-form-label text-right">Estado</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][estado]" type="text" value="{{ $d['estado'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">CP</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][codigo_postal]" type="text" value="{{ $d['codigo_postal'] ?? '' }}">
                                                </div>

                                                <label class="col-sm-1 col-form-label text-right">Celular</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][celular]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" value="{{ $d['celular'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Teléfono</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][telefono]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" value="{{ $d['telefono'] ?? '' }}">
                                                </div>

                                                <label class="col-sm-1 col-form-label text-right">¿Quién recibe?</label>
                                                <div class="col-sm-3">
                                                    <input class="form-control" name="direcciones[{{ $i }}][nombre_recibe]" type="text" value="{{ $d['nombre_recibe'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Mapa (URL)</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" name="direcciones[{{ $i }}][url_mapa]" type="text" value="{{ $d['url_mapa'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label class="col-sm-2 col-form-label">Requerimientos especiales</label>
                                                <div class="col-sm-7">
                                                    @foreach($RequerimientosEspeciales as $Req)
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" name="direcciones[{{ $i }}][requerimientos][]" value="{{ $Req->id }}">
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
                                                    <textarea class="form-control" name="direcciones[{{ $i }}][instrucciones]" rows="2">{{ $d['instrucciones'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <template id="direccion-template">
                                    <div class="card p-3 mb-3 direccion-item" data-index="__INDEX__">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Dirección #__HUMAN_INDEX__</strong>
                                            <button type="button" class="btn btn-xs btn-danger btn-remove-direccion">Eliminar</button>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Nombre de la dirección</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" name="direcciones[__INDEX__][nombre_direccion]" type="text">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Tipo de residencia</label>
                                            <div class="col-sm-3">
                                                <select class="form-control" name="direcciones[__INDEX__][tipo_residencia]" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="residencial">Residencia</option>
                                                    <option value="obra">Obra</option>
                                                    <option value="taller">Taller</option>
                                                    <option value="industria">Industria</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Dirección</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][direccion]" type="text">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">Colonia</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][colonia]" type="text">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Ciudad</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][ciudad]" type="text">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">Estado</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][estado]" type="text">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">CP</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][codigo_postal]" type="text">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">Celular</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][celular]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Teléfono</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][telefono]" type="text" inputmode="numeric" pattern="[0-9]{10}" maxlength="10">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">¿Quién recibe?</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][nombre_recibe]" type="text">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Mapa (URL)</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" name="direcciones[__INDEX__][url_mapa]" type="text">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Requerimientos especiales</label>
                                            <div class="col-sm-7">
                                                @foreach($RequerimientosEspeciales as $Req)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="direcciones[__INDEX__][requerimientos][]" value="{{ $Req->id }}">
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
                                                <textarea class="form-control" name="direcciones[__INDEX__][instrucciones]" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary"> Guardar </button>
                                <a href="{{route('clientes.index')}}" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
    (function(){
        const wrapper = document.getElementById('direcciones-wrapper');
        const btnAdd = document.getElementById('btn-add-direccion');
        const tpl = document.getElementById('direccion-template')?.innerHTML || '';

        function nextIndex(){
            const items = wrapper.querySelectorAll('.direccion-item');
            let max = -1;
            items.forEach(el =>{
                const idx = parseInt(el.getAttribute('data-index'), 10);
                if (!isNaN(idx) && idx > max) max = idx;
            });
            return max + 1;
        }

        function addDireccion(){
            if (!tpl) return;
            const idx = nextIndex();
            const html = tpl.replaceAll('__INDEX__', idx).replaceAll('__HUMAN_INDEX__', idx + 1);
            const temp = document.createElement('div');
            temp.innerHTML = html.trim();
            const node = temp.firstChild;
            wrapper.appendChild(node);
            bindRemove(node);
        }

        function bindRemove(scope){
            (scope || document).querySelectorAll('.btn-remove-direccion').forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.direccion-item');
                    if (card) card.remove();
                });
            });
        }

        if (btnAdd) btnAdd.addEventListener('click', addDireccion);
        bindRemove(document);
    })();
    </script>
@endpush
