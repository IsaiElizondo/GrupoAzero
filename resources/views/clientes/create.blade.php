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
                                            <input class="form-control" name="nombre" id="input_nombre" type="text" placeholder="Nombre del cliente" value="{{old('nombre')}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Código del Cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="codigo_cliente" id="input_codigo" type="text" placeholder="Código único de cliente" value="{{old('codigo_cliente')}}" required>
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
                                    @if(is_array($oldDirs) && count($oldDirs) > 0)
                                        @foreach($oldDirs as $i => $d)
                                            <div class="card p-3 mb-3 direccion-item" data-index="{{ $i }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong>Dirección #{{ $i + 1 }}</strong>
                                                    <button type="button" class="btn btn-xs btn-danger btn-remove-direccion">Eliminar</button>
                                                </div>

                                                 <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label"> Nombre de la dirección </label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" name="direcciones[{{ $i }}][nombre_direccion]" type="text" placeholder="Nombre de la dirección" value="{{ $d['nombre_direccion'] ?? '' }}">
                                                    </div>
                                                </div>


                                                <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label">Dirección</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" name="direcciones[{{ $i }}][direccion]" type="text" placeholder="Calle, número, colonia" value="{{ $d['direccion'] ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label">Ciudad</label>
                                                    <div class="col-sm-3">
                                                        <input class="form-control" name="direcciones[{{ $i }}][ciudad]" type="text" placeholder="Ciudad" value="{{ $d['ciudad'] ?? '' }}">
                                                    </div>

                                                    <label class="col-sm-1 col-form-label text-right">Estado</label>
                                                    <div class="col-sm-3">
                                                        <input class="form-control" name="direcciones[{{ $i }}][estado]" type="text" placeholder="Estado" value="{{ $d['estado'] ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label">CP</label>
                                                    <div class="col-sm-3">
                                                        <input class="form-control" name="direcciones[{{ $i }}][codigo_postal]" type="text" placeholder="Código Postal" value="{{ $d['codigo_postal'] ?? '' }}">
                                                    </div>

                                                    <label class="col-sm-1 col-form-label text-right">Celular</label>
                                                    <div class="col-sm-3">
                                                        <input class="form-control" name="direcciones[{{ $i }}][celular]" type="text" placeholder="Celular" value="{{ $d['celular'] ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label">Mapa (URL)</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" name="direcciones[{{ $i }}][url_mapa]" type="text" placeholder="https://maps..." value="{{ $d['url_mapa'] ?? '' }}">
                                                    </div>
                                                </div>


                                                <div class="row mt-2">
                                                    <label class="col-sm-2 col-form-label">Instrucciones</label>
                                                    <div class="col-sm-7">
                                                        <textarea class="form-control" name="direcciones[{{ $i }}][instrucciones]" rows="2" placeholder="Referencias, horarios de entrega, etc...">{{ $d['instrucciones'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <template id="direccion-template">
                                    <div class="card p-3 mb-3 direccion-item" data-index="__INDEX__">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Dirección #__HUMAN_INDEX__</strong>
                                            <button type="button" class="btn btn-xs btn-danger btn-remove-direccion">Eliminar</button>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label"> Nombre de la dirección </label>
                                            <div class="col-sm-7">
                                                <input class="form-control" name="direcciones[__INDEX__][nombre_direccion]" type="text" placeholder="Nombre de la dirección">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Dirección</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" name="direcciones[__INDEX__][direccion]" type="text" placeholder="Calle, número, colonia">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Ciudad</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][ciudad]" type="text" placeholder="Ciudad">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">Estado</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][estado]" type="text" placeholder="Estado">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">CP</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][codigo_postal]" type="text" placeholder="Código Postal">
                                            </div>

                                            <label class="col-sm-1 col-form-label text-right">Celular</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" name="direcciones[__INDEX__][celular]" type="text" placeholder="Celular">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Mapa (URL)</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" name="direcciones[__INDEX__][url_mapa]" type="text" placeholder="https://maps...">
                                            </div>
                                        </div>


                                        <div class="row mt-2">
                                            <label class="col-sm-2 col-form-label">Instrucciones</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" name="direcciones[__INDEX__][instrucciones]" rows="2" placeholder="Referencias, horarios de entrega, etc..."></textarea>
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
(function() {
    const wrapper = document.getElementById('direcciones-wrapper');
    const btnAdd = document.getElementById('btn-add-direccion');
    const tpl = document.getElementById('direccion-template')?.innerHTML || '';

    function nextIndex() {
        const items = wrapper.querySelectorAll('.direccion-item');
        let max = -1;
        items.forEach(el => {
            const idx = parseInt(el.getAttribute('data-index'), 10);
            if (!isNaN(idx) && idx > max) max = idx;
        });
        return max + 1;
    }

    function addDireccion() {
        if (!tpl) return;
        const idx = nextIndex();
        const html = tpl.replaceAll('__INDEX__', idx).replaceAll('__HUMAN_INDEX__', idx + 1);
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();
        const node = temp.firstChild;
        wrapper.appendChild(node);
        bindRemove(node);
    }

    function bindRemove(scope) {
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
