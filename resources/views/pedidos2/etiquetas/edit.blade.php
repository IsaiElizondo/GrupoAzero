@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Etiqueta</h3>

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

    <form action="{{ route('etiquetas.update', $etiqueta->id) }}" method="POST">
        @csrf

        <div style="margin-bottom: 10px;">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $etiqueta->nombre) }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" id="descripcion" rows="3" style="width: 100%;">{{ old('descripcion', $etiqueta->descripcion) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('etiquetas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
