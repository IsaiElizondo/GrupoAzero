@extends('layouts.app', ['activePage' => 'client-management', 'titlePage' => __('Clientes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                <h4 clas="card-title"> {{$cliente->nombre ?? 'N/A'}}</h4>
                                <p class="card-category">Código: {{ $cliente->codigo_cliente ?? 'N/A'}}</p>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{route('clientes.index')}}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">arrow_back</span>
                                        Regresar
                                </a>
                               <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons"> edit </span>
                                        Editar Cliente
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-7">
                                <input class="form-control" type="text" value="{{ $cliente->nombre }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-2 col-form-label"> Código del Cliente</label>
                            <div class="col-sm-7">
                                <input class="form-control"  type="text" value="{{ $cliente->codigo_cliente }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-2 col-form-label"> Creado el </label>
                            <div class="col-sm-7">
                                <input class="form-control" type="text" value="{{ $cliente->created_at->toDateString() }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-2 col-form-label"> Última actualización </label>
                            <div class="col-sm-7">
                                <input class="form-control" type="text" value="{{ $cliente->updated_at->toDateTimeString() }}" disabled>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mt-3"> Direcciones </h5>
                        @if($cliente->direcciones->count() > 0)
                            @foreach($cliente->direcciones as $dir)
                                <div class="card p-3 mb-2">
                                    <h3>{{ $dir->nombre_direccion }} </h3>
                                    <h4><strong>{{ $dir->direccion }}</strong></h4>
                                    <h5 class="mb-1">{{ $dir->ciudad }}, {{ $dir->estado }} - CP: {{ $dir->codigo_postal }} - Celular: {{$dir->celular}} - Recibe: {{$dir->nombre_recibe}}</h5>
                                    @if($dir->url_mapa)
                                        <p class="mb-1">
                                            <a href="{{ $dir->url_mapa }}" target="_blank">Ver en mapa</a>
                                        </p>
                                    @endif
                                    @if($dir->instrucciones)
                                        <h5 class="text-muted">Instrucciones: {{ $dir->instrucciones }}</h5>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Este cliente no tiene direcciones registradas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection