@extends('layouts.app')

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('etiquetas.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                    <h4 class="card-title">Crear etiqueta</h4>
                                    <p class="card-category">Información básica de la etiqueta</p>
                                </div>
                                <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons">
                                            arrow_back
                                        </span>
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
                                        <input class="form-control" name="nombre" id="input-nombre" type="text" placeholder="Nombre" value="{{ old('nombre') }}" required="true" aria-required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">Descripción</label>
                                <div class="col-sm-7">
                                    <div class="form-group bmd-form-group is-filled">
                                        <textarea class="form-control" name="descripcion" id="exampleFormControlTextarea1" rows="3">{{ old('descripcion') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('etiquetas.index') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection

{{--
@section('content')
<div class="container">
  

    

    @if ($errors->any())
        <div style="color: red;">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form style="margin-top: 8%;" action="{{ route('etiquetas.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 10px; ">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" id="descripcion" rows="3" style="width: 100%;">{{ old('descripcion') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('etiquetas.index') }}" class="btn btn-danger">Cancelar</a>
    </form>
</div>
@endsection
--}}