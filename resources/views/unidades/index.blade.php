@extends('layouts.app', ['activePage' => 'unidades', 'titlePage' => __('Unidades (Camiones/Trailers)')])

@section('content')
    <div class="content">
        @if(session('success'))
            <div style="color:green; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                <h4 class="card-title"> Unidades </h4>
                                <p class="card-category"> Listado de Unidades </p>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{ route('unidades.create') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">add_circle_outline</span>
                                        Nueva Unidad
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table data-table" id="unidades">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Capacidad (KG)</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Número de serie</th>
                                        <th>Placas</th>
                                        <th>Tipo EPP</th>
                                        <th>EPP</th>
                                        <th>Estatus</th>
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unidades as $unidad)
                                        <tr>
                                            <td>{{ $unidad->nombre_unidad }}</td>
                                            <td>{{ $unidad->capacidad_kg ? number_format($unidad->capacidad_kg, 2).'kg':'-' }}</td>
                                            <td>{{ $unidad->marca ?? '-' }}</td>
                                            <td>{{ $unidad->modelo ?? '-' }}</td>
                                            <td>{{ $unidad->numero_de_serie ?? '-' }}</td>
                                            <td>{{ $unidad->placas ?? '-' }}</td>
                                            <td>{{ $unidad->tipo_epp ?? '-' }}</td>
                                            <td>{{ $unidad->epp ?? '-' }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($unidad->estatus == 'activo') badge-success 
                                                    @elseif($unidad->estatus == 'mantenimiento') badge-warning 
                                                    @else badge-secondary 
                                                    @endif">
                                                    {{ ucfirst($unidad->estatus) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('unidades.show', $unidad->id) }}" class="btn btn-primary btn-link btn-sm">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('unidades.edit', $unidad->id) }}" class="btn btn-success btn-link btn-sm">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-link btn-sm"
                                                   onclick="event.preventDefault();
                                                    if(confirm('¿Seguro que quiere eliminar esta unidad?')){
                                                        document.getElementById('delete-form-{{ $unidad->id }}').submit();
                                                    }">
                                                    <span class="material-icons">delete</span>
                                                </a>

                                                <form id="delete-form-{{ $unidad->id }}"
                                                      action="{{ route('unidades.destroy', $unidad->id) }}"
                                                      method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
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

@push('js')
    <script>
        $(document).ready(function(){
            $('#unidades').DataTable({
                language:{
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(Filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate":{
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
@endpush
