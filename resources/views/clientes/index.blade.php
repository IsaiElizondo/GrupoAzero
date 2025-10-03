@extends('layouts.app', ['activePage' => 'client-management', 'titlePage' => __('Clientes')])

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
                                <h4 class="card-title">Clientes</h4>
                                <p class="card-category">Listado de Clientes</p>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">add_circle_outline</span>
                                    Nuevo cliente
                                </a>
                            </div>
                        </div> 
                    </div> 

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table data-table" id="clientes">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Código Cliente</th>                                        
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientes as $cliente)
                                        <tr>
                                            <td>{{ $cliente->id }}</td>
                                            <td>{{ $cliente->nombre }}</td>
                                            <td>{{ $cliente->codigo_cliente }}</td>                                            
                                            <td>
                                                <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-primary btn-link btn-sm">
                                                    <span class="material-icons">visibility</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-success btn-link btn-sm">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-link btn-sm" 
                                                   onclick="event.preventDefault();
                                                    if(confirm('¿Seguro que quiere eliminar este cliente?')){
                                                        document.getElementById('delete-form-{{ $cliente->id }}').submit();
                                                    }">
                                                    <span class="material-icons">delete</span>
                                                </a>

                                                <form id="delete-form-{{ $cliente->id }}" 
                                                      action="{{ route('clientes.destroy', $cliente->id) }}" 
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
            $('#clientes').DataTable({
                language:{
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ registros",
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
