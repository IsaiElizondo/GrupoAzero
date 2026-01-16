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
                                        <th>Celular / Telefono</th>
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
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rutas as $ruta)
                                        @foreach($ruta->pedidos as $pedido)
                                            @php
                                                $codigo = $pedido->cliente_codigo ?? null;
                                                $nombre = $pedido->cliente_nombre ?? null;

                                                $telefonos = [];
                                                if ($pedido->order->celular){
                                                    $celular = preg_replace('/[^0-9]/', '', $pedido->order->celular);
                                                    $telefonos[] = '<a href="https://wa.me/52'.$celular.'" target="_blank">'.$pedido->order->celular.'</a>';
                                                }
                                                if ($pedido->order->telefono){
                                                    $telefono = preg_replace('/[^0-9]/', '', $pedido->order->telefono);
                                                    $telefonos[] = '<a href="https://wa.me/52'.$telefono.'" target="_blank">'.$pedido->order->telefono.'</a>';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $ruta->numero_ruta }}</td>

                                                <td>
                                                    <a href="{{ url('pedidos2/pedido/'.$pedido->order_id) }}">
                                                        {{ $pedido->order->invoice_number ?? 'Sin Folio' }}
                                                    </a>
                                                </td>

                                                <td>
                                                    @if($pedido->partial_folio)
                                                        <a href="{{ url('pedidos2/pedido/'.$pedido->order_id) }}">
                                                            {{ $pedido->partial_folio }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($pedido->smaterial_folio)
                                                        <a href="{{ url('pedidos2/pedido/'.$pedido->order_id) }}">
                                                            {{ $pedido->smaterial_folio }}
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
                                                <td>
                                                    <select class="form-control form-control-sm estatus-pago"
                                                            data-id="{{ $pedido->id }}">
                                                        <option value="pagado" {{ $pedido->estatus_pago == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                        <option value="por_cobrar" {{ $pedido->estatus_pago == 'por_cobrar' ? 'selected' : '' }}>Por cobrar</option>
                                                        <option value="credito" {{ $pedido->estatus_pago == 'credito' ? 'selected' : '' }}>Crédito</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        class="form-control form-control-sm monto-por-cobrar"
                                                        data-id="{{ $pedido->id }}"
                                                        value="{{ $pedido->monto_por_cobrar }}">
                                                </td>
                                                <td>{{ $pedido->order->nombre_recibe ?? '-' }}</td>
                                                <td>{!! count($telefonos) ? implode(' / ', $telefonos) : '-' !!}</td>
                                                <td>{{ $pedido->order->direccion ?? '-' }}</td>
                                                <td>
                                                    @if($pedido->order->url_mapa)
                                                        <a href="{{ $pedido->order->url_mapa }}" target="_blank">Ver Mapa</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($pedido->estatus_entrega ?? 'enrutado') }}</td>
                                                <td>{{ $ruta->unidad->nombre_unidad ?? 'Sin unidad' }}</td>
                                                <td>{{ $ruta->chofer->name ?? 'Sin chofer' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ruta->created_at)->format('d/m/Y H:i') }}</td>
                                                <td>{{ $pedido->motivo ?? '-' }}</td>
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
                                                    <button class="btn btn-sm btn-success guardar-pago"
                                                            data-id="{{ $pedido->id }}">
                                                        <span class="material-icons">save</span>
                                                    </button>
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

        document.querySelectorAll('.guardar-pago').forEach(btn => {
            btn.addEventListener('click', function (){
                const id = this.dataset.id;
                const row = this.closest('tr');

                const estatus = row.querySelector('.estatus-pago').value;
                const monto = row.querySelector('.monto-por-cobrar').value;

                fetch('{{ route("rutas.pedido.pago") }}', {
                    method: 'POST',
                    headers:{
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ruta_pedido_id: id,
                        estatus_pago: estatus,
                        monto_por_cobrar: monto
                    })
                })
                .then(r => r.json())
                .then(() => alert('Actualizado correctamente'))
                .catch(() => alert('Error al guardar'));
            });
        });
    </script>
@endpush
