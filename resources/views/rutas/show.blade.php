@extends('layouts.app', ['activePage' => 'rutas', 'titlePage' => __('Detalles de Ruta')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <h4 class="card-title">Ruta #{{ $ruta->numero_ruta }}</h4>
                                    <p class="card-category">Detalles de la ruta seleccionada</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons">arrow_back</span>
                                        Regresar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                                $pedido = $ruta->orders->first();
                                $codigo = $pedido->pivot->cliente_codigo ?? null;
                                $nombre = $pedido->pivot->cliente_nombre ?? null;
                            @endphp
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Cliente:</strong>
                                    @if($codigo)
                                        {{ $nombre ? "$codigo — $nombre" : $codigo }}
                                    @else
                                        Sin cliente
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>Unidad:</strong>
                                    {{ $ruta->unidad->nombre_unidad ?? 'Sin unidad asignada' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Chofer:</strong>
                                    {{ $ruta->chofer->name ?? 'Sin chofer asignado' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha / Hora:</strong>
                                    {{ \Carbon\Carbon::parse($ruta->created_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Estatus de entrega:</strong>
                                    {{ $ruta->estatus_entrega ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Motivo:</strong>
                                    {{ $ruta->motivo ?? 'Sin motivo registrado' }}
                                </div>
                            </div>

                            <hr>

                            <h5 style="font-weight:bold;">Pedidos asignados a esta ruta</h5>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Folio Pedido</th>
                                            <th>Estatus de Pago</th>
                                            <th>Monto por Cobrar</th>
                                            <th>Fecha Pedido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ruta->orders as $order)
                                            <tr>
                                                <td>{{ $order->invoice_number ?? 'Sin folio' }}</td>
                                                <td>{{ ucfirst($order->pivot->estatus_pago ?? 'pendiente') }}</td>
                                                <td>${{ number_format($order->pivot->monto_por_cobrar ?? 0, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    No hay pedidos asignados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
