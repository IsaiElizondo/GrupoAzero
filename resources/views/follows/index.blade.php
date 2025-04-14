<?php
use \App\Pedidos2;
$origenes=[""=>"N/S","C"=>"Cotización","F"=>"Factura","R"=>"Requisición Stock"];
?>
@extends('layouts.app', ['activePage' => 'follows', 'titlePage' => __('Mis favoritos')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">

            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                            <h4 class="card-title ">Favoritos</h4>
                            <p class="card-category"> {{ auth()->user()->name }} / {{ $role->name }} / {{ $department->name }} </p>
                        </div>
                        {{-- @if ( $role->name == "Administrador" || $department->name == "Ventas" )
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">
                                        add
                                    </span>
                                    Nuevo pedido
                                </a>
                            </div>
                        @endif --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                    <table class="table data-table" id="orders">
                        <thead>
                            <tr>
                                {{-- <th>No</th> --}}
                                <th>Origen</th>
                                <th>Orden</th>
                                <th>Status</th>
                                <th>Cliente</th>
                                <th width="50px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($follows as $follow)
                                <tr>
                                    {{-- <td>{{ $follow->id }}</td> --}}
                                    <td>
                                        <p style="font-size: 1.3em">{{ $origenes[$follow->order->origin] }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ url('pedidos2/pedido/'. $follow->order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ Pedidos2::CodigoDe($follow->order)  }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('pedidos2/pedido/'. $follow->order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $follow->order->status->name }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('pedidos2/pedido/'. $follow->order->id) }}" class="btn btn-primary btn-link btn-sm">
                                            <p style="font-size: 1.3em">{{ $follow->order->client }}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('follows.destroy', $follow->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary btn-link btn-fab btn-fab-mini btn-round">
                                                <i class="material-icons"> delete_forever</i>
                                            </button>
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
    $(document).ready(function() {
        $('#follows').DataTable({
            // "serverSide": true,
            // "ajax": "{{ url('api/orders') }}",
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
            ajax: "{{ route('orders.index') }}",
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
