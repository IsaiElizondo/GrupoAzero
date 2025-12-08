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
                                        <th>Número Ruta</th>
                                        <th>Factura (Folio)</th>
                                        <th>Folio SP</th>
                                        <th>Folio SM</th>
                                        <th>Cliente</th>
                                        <th>Estatus Pago</th>
                                        <th>Monto por Cobrar</th>
                                        <th>Nombre Recibe</th>
                                        <th>Telefono / Celular</th>
                                        <th>Dirección</th>
                                        <th>URL Mapa</th>
                                        <th>Estatus</th>
                                        <th>Unidad</th>
                                        <th>Chofer</th>
                                        <th>Fecha / Hora</th>
                                        <th>Comentarios</th>
                                        <th width="50px"></th>
                                        <th width="50px"></th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rutas as $ruta)
                                        @foreach($ruta->orders as $order)
                                            @php
                                                $codigo = $order->pivot->cliente_codigo ?? null;
                                                $nombre = $order->pivot->cliente_nombre ?? null;
                                            @endphp
                                            @php
                                                $telefonos = [];
                                                if($order->telefono){
                                                    $telefonos[] = '<a href="tel:'.$order->telefono.'">'.$order->telefono.'</a>';
                                                }
                                                if($order->celular){
                                                    $telefonos[] = '<a href="tel:'.$order->celular.'">'.$order->celular.'</a>';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $ruta->numero_ruta }}</td>
                                                <td>
                                                    <a href="{{ url('pedidos2/pedido/'.$order->id) }}">
                                                        {{ $order->invoice_number ?? 'Sin Folio'}}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($order->pivot->partial_folio)
                                                        <a href="{{ url('pedidos2/pedido/'.$order->id) }}">
                                                            {{ $order->pivot->partial_folio }}
                                                        </a>
                                                    @else 
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->pivot->smaterial_folio)
                                                        <a href="{{ url('pedidos2/pedido/'.$order->id) }}">
                                                            {{ $order->pivot->smaterial_folio }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($codigo)
                                                        {{ $nombre ? "$codigo — $nombre" : $codigo }}
                                                    @else
                                                        Sin cliente
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($order->pivot->estatus_pago ?? 'Pagado') }}</td>
                                                <td>{{ number_format($order->pivot->monto_por_cobrar ?? 0, 2) }}</td>
                                                <td>{{ $order->nombre_recibe ?? '-'}}</td>
                                                <td>{!! count($telefonos) ? implode(' / ', $telefonos) : '-' !!}</td>
                                                <td>{{ $order->direccion ?? '-'}}</td>
                                                <td>
                                                    @if($order->url_mapa)
                                                        <a href="{{ $order->url_mapa }}" target="_blank">Ver Mapa</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($ruta->estatus_entrega) }}</td>
                                                <td>{{ $ruta->unidad->nombre_unidad ?? 'Sin unidad' }}</td>
                                                <td>{{ $ruta->chofer->name ?? 'Sin chofer' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ruta->created_at)->format('d/m/Y H:i') }}</td>
                                                <td>{{ $ruta->motivo ?? '-'}}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('rutas.show', $ruta->id) }}" class="btn btn-sm btn-info" title="Ver">
                                                        <span class="material-icons">visibility</span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('rutas.edit', $ruta->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                                        <span class="material-icons">edit</span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <span class="material-icons">delete</span>
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
                    decimal: "",
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    lengthMenu: "Mostrar _MENU_ registros",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate:{
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });
        });
    </script>
@endpush
