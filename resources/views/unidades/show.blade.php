@extends('layouts.app', ['activePage' => 'unit-management', 'titlePage' => __('Detalles de la unidad')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 text-left">
                                    <h4 class="card-title"> Detalles de la unidad </h4>
                                    <p class="card-category"> Información registrada </p>
                                </div>
                                <div class="col-md-6 col-sm-12 text-right">
                                    <a href="{{ route('unidades.index') }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons">arrow_back</span>
                                        Regresar
                                    </a>
                                    <a href="{{ route('unidades.edit', $unidad->id) }}" class="btn btn-sm btn-warning">
                                        <span class="material-icons">edit</span>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Nombre de la unidad </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->nombre_unidad }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Marca </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->marca ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Modelo </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->modelo ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Número de serie </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->numero_de_serie ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Placas </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->placas ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> EPP </label>
                                <div class="col-sm-7">
                                    <p class="form-control-static">{{ $unidad->epp ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"> Estatus </label>
                                <div class="col-sm-7">
                                    <span class="badge 
                                        @if($unidad->estatus == 'activo') badge-success 
                                        @elseif($unidad->estatus == 'mantenimiento') badge-warning 
                                        @else badge-secondary 
                                        @endif">
                                        {{ ucfirst($unidad->estatus) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer ml-auto mr-auto">
                            <a href="{{ route('unidades.index') }}" class="btn btn-primary"> Regresar </a>
                            <a href="{{ route('unidades.edit', $unidad->id) }}" class="btn btn-warning"> Editar </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
