@extends('layouts.app', ['activePage' => 'unit-management', 'titlePage' => __('Crear nueva unidad')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if($errors->any())
                        <div style="color: red; margin-bottom: 12px">
                            <strong>Corrige los siguientes errores:</strong>
                            <ul style="margin:6px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('unidades.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title"> Crear unidad </h4>
                                        <p class="card-category"> Información para crear Unidad </p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">arrow_back</span>
                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label"> Nombre de la unidad </label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="nombre_unidad" id="input_nombre_unidad" type="text" placeholder="Nombre de la unidad" value="{{ old('nombre_unidad') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label"> Marca </label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="marca" id="input_marca" type="text" placeholder="Marca" value="{{ old('marca') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <label class="col-sm-2 col-form-label"> Número de serie </label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="numero_de_serie" id="input_numero_de_serie" type="text" placeholder="Número de serie" value="{{ old('numero_de_serie') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <label class="col-sm-2 col-form-label"> Placas </label>
                                    <div class="col-sm-7"> 
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="placas" id="input_placas" type="text" placeholder="Placas" value="{{ old('placas') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label"> EPP </label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="epp" id="input_epp" type="text" placeholder="Equipo extra de la unidad" value="{{ old('epp') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 col-form-label"> Estatus </label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="estatus" id="select_estatus" required>
                                            <option value="activo" {{ old('estatus') == 'activo' ? 'selected' : '' }}> Activo </option>
                                            <option value="mantenimiento" {{ old('estatus') == 'mantenimiento' ? 'selected' : '' }}> Mantenimiento </option>
                                            <option value="inactivo" {{ old('estatus') == 'inactivo' ? 'selected' : '' }}> Inactivo </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary"> Guardar </button>
                                <a href="{{ route('unidades.index') }}" class="btn btn-danger"> Cancelar </a>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
@endsection
