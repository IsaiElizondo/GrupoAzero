@extends('layouts.app')

@section('content')
<div class="container">


    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    



    <table style="margin-top: 60px;" border="1" cellpadding="10" cellspacing="0" width="100%">


        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etiquetas as $etiqueta)
                <tr>
                    <td>{{ $etiqueta->id }}</td>
                    <td>{{ $etiqueta->nombre }}</td>
                    <td>{{ $etiqueta->descripcion }}</td>
                    <td>
                        <a href="{{ route('etiquetas.edit', $etiqueta->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('etiquetas.delete', $etiqueta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar esta etiqueta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay etiquetas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="text-align: center; margin-bottom: 15px;">
        <a href="{{ route('etiquetas.create') }}" class="btn btn-primary">
            + NUEVA ETIQUETA
        </a>
</div>
@endsection
