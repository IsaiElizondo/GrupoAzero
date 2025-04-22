@extends('layouts.app')

@section('content')
<div class="content">
    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class= "row">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                            <h4 class="card-title ">Etiquetas</h4>
                            <p class="card-category"> Etiquetas de estado</p>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                            <a href="{{ route('etiquetas.create') }}" class="btn btn-sm btn-primary">
                                <span class="material-icons">
                                    add_circle_outline
                                </span>
                                Nueva etiqueta
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table data-table" id="etiquetas">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th width="50px">&nbsp;</th>
                                    <th width="50px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etiquetas as $etiqueta)
                                    <tr>
                                        <td>{{ $etiqueta->id }}</td>
                                        <td>{{ $etiqueta->nombre }}</td>
                                        <td>{{ $etiqueta->descripcion }}</td>
                                        <td>
                                            <a href="{{ route('etiquetas.edit', $etiqueta->id) }}" class="btn btn-primary btn-link btn-sm">
                                                <span class="material-icons">edit</span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-link btn-sm"
                                                onclick="event.preventDefault(); 
                                                if (confirm('¿Seguro que quieres eliminar esta etiqueta? Al eliminarla no podrás recuperarla.')) {
                                                    document.getElementById('delete-form-{{ $etiqueta->id }}').submit();
                                                }">
                                                <span class="material-icons">delete</span>
                                            </a>

                                            <form id="delete-form-{{ $etiqueta->id }}" action="{{ route('etiquetas.delete', $etiqueta->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>

@endsection


    


{{--
    <table style="margin-top: 8%;" border="1" cellpadding="10" cellspacing="0" width="100%">


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
                        <a href="{{ route('etiquetas.edit', $etiqueta->id) }}" class="btn btn-sm btn-danger">Editar</a>
                        <form action="{{ route('etiquetas.delete', $etiqueta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Seguro que quieres eliminar esta etiqueta?')">Eliminar</button>
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

--}}