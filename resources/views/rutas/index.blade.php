@extends('layouts.app', ['activePage' => 'rutas', 'titlePage' => __('Rutas')])

@section('content')
    <div class="content">
        @if(session('success'))
            <div style="color:green; font-weight:bold;">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                <h4 class="card-title">Rutas</h4>
                                <p class="card-category">Listado de rutas con pedidos</p>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                <a href="{{ route('rutas.create') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">add_circle_outline</span>
                                        Nueva ruta
                                </a>
                                </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table data-table" id="rutas">
                                <thead class="text-primary">
                                    <tr>
                                        <th> # </th>
                                        <th> Número Ruta </th>
                                        <th> Unidad </th>
                                        <th> Chofer </th>
                                        <th> Pedido (Folio) </th>
                                        <th> Estatus de Pago </th>
                                        <th> Monto por Cobrar </th>
                                        <th> Cliente </th>
                                        <th> Fecha / Hora </th>
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                        <th width="50px">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rutas as $ruta)
                                        @foreach($ruta->orders as $order)
                                            <tr>
                                                <td>{{ $ruta->id }}</td>
                                                <td>{{ $ruta->numero_ruta }}</td>
                                                <td>{{ $ruta->unidad->nombre ?? 'Sin unidad' }}</td>
                                                <td>{{ $ruta->chofer->name ?? 'Sin chofer' }}</td>
                                                <td>{{ $order->invoice_number ?? 'Sin folio' }}</td>
                                                <td>{{ ucfirst($order->pivot->estatus_pago ?? 'pendiente') }}</td>
                                                <td>${{ number_format($order->pivot->monto_por_cobrar ?? 0, 2) }}</td>
                                                <td>{{ $ruta->cliente->nombre ?? 'Sin cliente' }}</td>
                                                <td>{{ $ruta->fecha_hora ? \Carbon\Carbon::parse($ruta->fecha_hora)->format('d/m/Y H:i') : '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('rutas.show', $ruta->id) }}" class="btn btn-sm btn-info" title="Ver">
                                                        <span class="material-icons"> visibility </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('rutas.edit', $ruta->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                                        <span class="material-icons"> edit </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <span class="material-icons"> delete </span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
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
            $('#rutas').DataTable({
                order: [[2, 'asc']], 
                language:{
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
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
