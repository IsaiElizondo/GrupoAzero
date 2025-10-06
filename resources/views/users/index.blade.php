@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Usuarios')])

@section('content')
    <div class="content">
    <div class="container-fluid">
        <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Usuarios</h4>
                        <p class="card-category"> Todos los usuarios de la plataforma</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                person_add
                            </span>
                            Nuevo usuario
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive ">
                <table class="table data-table" id="users">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Sucursal</th>
                            <th>Depto</th>
                            <th>Rol</th>
                            <th width="50px">&nbsp;</th>
                            <th width="50px">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(auth()->user()->department->name == "Administrador" && auth()->user()->role->name == "Administrador")
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->office }}</td>
                                    <td>{{ $user->department->name }}</td>
                                    <td>{{ $user->role->name }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <span class="material-icons">
                                                visibility
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <span class="material-icons">
                                                edit
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        
                        @if(auth()->user()->department->name == "Embarques" && auth()->user()->role->name == "Empleado")
                            @foreach ($users as $user)
                                @if($user->role_id == 2 && $user->department_id == 6)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->office }}</td>
                                        <td>{{ $user->department->name }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        <td>
                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-link btn-sm">
                                                <span class="material-icons">
                                                    visibility
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-link btn-sm">
                                                <span class="material-icons">
                                                    edit
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
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
    $(document).ready(function() {
        $('#users').DataTable({
            // "serverSide": true,
            // "ajax": "{{ url('api/users') }}",
            // "columns": [
            //     {data: 'id'},
            //     {data: 'name'},
            //     {data: 'email'},
            //     {data: 'btn'},
            // ],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    });
</script>



{{-- <script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
  </script> --}}
@endpush
