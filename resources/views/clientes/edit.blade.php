@extends('layouts.app', ['activePage' => 'clientes', 'titlePage' => __('Clientes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div clss="row">
                <div class="col-md-12">
                    @if($errors->any())
                        <div style="color:red; margin-bottom:12px">
                            <strong>Corrige los siguientes errores:</strong>
                            <ul style="margin: 6px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('clientes.update', $cliente->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Editar Cliente</h4>
                                        <p class="card-category"> Información del cliente y sus direcciones</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{route('clientes.index')}}" class="btn btn-sm btn-primary">
                                            <span class="material-icons"> arrow_back</span>
                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-7">
                                        <div class="form-goup bmd-form-group is-filled">
                                            <input class="form-control" name="nombre" type="text" placeholder="Nombre del cliente" value="{{ old('nombre', $cliente->nombre) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Código del Cliente</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="codigo_cliente" type="text" placeholder="Código único de cliente" value="{{ old('codigo_cliente', $cliente->codigo_cliente) }}">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 style="margin:6px 0 14px;">Direcciones</h5>
                                        <p class="text-muted" style="margin-top:-6px">Editar, elimina o añade direcciones nuevas.</p>
                                        <button type="button" id="btn-add-direccion" class="btn btn-sm btn-secondary">+ Añadir dirección</button>
                                    </div>
                                </div>

                                <div id="direcciones-wrapper" class="mt-3">
                                    @php
                                        $oldDirs = old('direcciones');
                                        $renderDirs = is_array($oldDirs)
                                            ? $oldDirs
                                            : ($cliente->direcciones->map(function($d) use ($RequerimientosPorDireccion){
                                                return [
                                                    'id' => $d->id,
                                                    'nombre_direccion' => $d->nombre_direccion,
                                                    'tipo_residencia' => $d->tipo_residencia,
                                                    'direccion' => $d->direccion,
                                                    'colonia' => $d->colonia,
                                                    'ciudad' => $d->ciudad,
                                                    'estado' => $d->estado,
                                                    'codigo_postal' => $d->codigo_postal,
                                                    'celular' => $d->celular,
                                                    'telefono' => $d->telefono,
                                                    'nombre_recibe' => $d->nombre_recibe,
                                                    'url_mapa' => $d->url_mapa,
                                                    'instrucciones' => $d->instrucciones,
                                                    'requerimientos' => $RequerimientosPorDireccion[$d->id] ?? [],
                                                ];
                                            })->toArray());
                                    @endphp

                                    @foreach($renderDirs as $i => $d)
                                        <div class="card p-3 mb-3 direccion-item" data-index="{{ $i }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Dirección #{{ $i + 1 }}</strong>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-danger btn-remove-direccion">Eliminar</button>
                                                </div>
                                            </div>

                                            @if(!empty($d['id']))
                                                <input type="hidden" name="direcciones[{{ $i }}][id]" value="{{ $d['id'] }}">
                                            @endif

                                            <input type="hidden" name="direcciones[{{ $i }}][_delete]" value="0" class="flag-delete">

                                            @include('partials.direccion.form', ['prefix' => "direcciones[$i]", 'data' => $d, 'requerimientos' => $requerimientos])
                                        </div>
                                    @endforeach

                                </div>

                                <template id="direccion-template">
                                    <div class="card p-3 mb-3 direccion-item" data-index="__INDEX__">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Dirección #__HUMAN_INDEX__</strong>
                                            <div>
                                                <button type="button"
                                                        class="btn btn-xs btn-danger btn-remove-direccion">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="direcciones[__INDEX__][id]" value="">
                                        <input type="hidden" name="direcciones[__INDEX__][_delete]" value="0" class="flag-delete">
                                        @include('partials.direccion.form_template', [
                                            'requerimientos' => $requerimientos
                                        ])
                                    </div>
                                </template>
                            </div>

                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary"> Actualizar </button>
                                <a href="{{route('clientes.index')}}" class="btn btn-danger"> Cancelar </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sepomex.js') }}"></script>
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
            const prefix = `direcciones[${idx}]`;

            const html = tpl
                .replaceAll('__INDEX__', idx)
                .replaceAll('__HUMAN_INDEX__', idx + 1)
                .replaceAll('__PREFIX__', prefix);

            const temp = document.createElement('div');
            temp.innerHTML = html.trim();
            const node = temp.firstChild;
            wrapper.appendChild(node);
            bindRemove(node);
        }

        function bindRemove(scope) {
            (scope || document).querySelectorAll('.btn-remove-direccion').forEach(btn =>{
                btn.addEventListener('click', function() {
                    const card = this.closest('.direccion-item');
                    if (card) card.remove();
                });
            });
        }

        if(btnAdd) btnAdd.addEventListener('click', addDireccion);
        bindRemove(document);
    })();
    </script>
@endpush
